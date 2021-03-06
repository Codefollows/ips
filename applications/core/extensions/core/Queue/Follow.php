<?php
/**
 * @brief		Background Task: Send Follow Notifications
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		27 May 2014
 */

namespace IPS\core\extensions\core\Queue;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Background Task: Send Follow Notifications
 */
class _Follow
{
	/**
	 * Run Background Task
	 *
	 * @param	mixed						$data	Data as it was passed to \IPS\Task::queue()
	 * @param	int							$offset	Offset
	 * @return	int							New offset
	 * @throws	\IPS\Task\Queue\OutOfRangeException	Indicates offset doesn't exist and thus task is complete
	 */
	public function run( &$data, $offset )
	{
		$classname = $data['class'];
        $exploded = explode( '\\', $classname );
        if ( !class_exists( $classname ) or !\IPS\Application::appIsEnabled( $exploded[1] ) )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}
		
		try
		{
			$item = $classname::load( $data['item'] );
		}
		catch( \OutOfRangeException $e )
		{
			/* Item no longer exists, so we're done here. */
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		/* Check soft delete */
		if( $item->hidden() == -2 )
		{
			/* Item is pending deletion, do not sent notifications */
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		$sentTo = isset( $data['sentTo'] ) ? $data['sentTo'] : array();
		$newOffset = $item->sendNotificationsBatch( $offset, $sentTo, isset( $data['extra'] ) ? $data['extra'] : NULL );
		$data['sentTo'] = $sentTo;

		if( $newOffset === NULL )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		return $newOffset;
	}
	
	/**
	 * Get Progress
	 *
	 * @param	mixed					$data	Data as it was passed to \IPS\Task::queue()
	 * @param	int						$offset	Offset
	 * @return	array( 'text' => 'Doing something...', 'complete' => 50 )	Text explaining task and percentage complete
	 * @throws	\OutOfRangeException	Indicates offset doesn't exist and thus task is complete
	 */
	public function getProgress( $data, $offset )
	{
		$classname = $data['class'];
        $exploded = explode( '\\', $classname );
        if ( !class_exists( $classname ) or !\IPS\Application::appIsEnabled( $exploded[1] ) )
		{
			throw new \OutOfRangeException;
		}
		
		$item				= $classname::loadAndCheckPerms( $data['item'] );
		$complete			= $data['followerCount'] ? round( 100 / $data['followerCount'] * $offset, 2 ) : 100;
		$title				= ( $item instanceof \IPS\Content\Comment ) ? $item->item()->mapped('title') : $item->mapped('title');

		return array( 'text' => \IPS\Member::loggedIn()->language()->addToStack('backgroundQueue_follow', FALSE, array( 'htmlsprintf' => array( \IPS\Theme::i()->getTemplate( 'global', 'core', 'global' )->basicUrl( $item->url(), TRUE, $title, FALSE ) ) ) ), 'complete' => $complete );
	}	
}