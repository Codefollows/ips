<?php
/**
 * @brief		Background Task: Resynchronise the automatically generated topic created by other content items
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Content
 * @since		11 Jun 2018
 */

namespace IPS\core\extensions\core\Queue;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Background Task: Rebuild database records
 */
class _ResyncTopicContent
{
	/**
	 * @brief Number of content items to rebuild per cycle
	 */
	public $rebuild	= 100;

	/**
	 * Parse data before queuing
	 *
	 * @param	array	$data
	 * @return	array
	 */
	public function preQueueData( $data )
	{
		$classname = $data['class'];

		try
		{
			$category = $classname::load( $data['categoryId'] );

			$data['count'] = (int) $category->getContentItemCount();
		}
		catch( \Exception $ex )
		{
			throw new \OutOfRangeException;
		}

		if( $data['count'] == 0 )
		{
			return null;
		}

		return $data;
	}

	/**
	 * Run Background Task
	 *
	 * @param	mixed						$data	Data as it was passed to \IPS\Task::queue()
	 * @param	int							$offset	Offset
	 * @return	int							New offset
	 * @throws	\IPS\Task\Queue\OutOfRangeException	Indicates offset doesn't exist and thus task is complete
	 */
	public function run( $data, $offset )
	{
		$classname = $data['class'];
		$itemClass = $classname::$contentItemClass;
		$rebuilt = 0;
		try
		{
			$category = $classname::load( $data['categoryId'] );
		}
		catch ( \OutOfRangeException $e )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}


		try
		{
			$idColumn = $itemClass::$databaseColumnId;

			$where[] = array( $itemClass::$databasePrefix . $itemClass::$databaseColumnMap['container'] . '=' . $data['categoryId'] );

			$select = \IPS\Db::i()->select( '*', $itemClass::$databaseTable, $where, $itemClass::$databasePrefix . $idColumn . ' DESC', array( $offset, $this->rebuild ) );

			$iterator = new \IPS\Patterns\ActiveRecordIterator( $select, $itemClass );
			foreach( $iterator as $item )
			{
				$item->syncTopic();
			}
		}

		catch( \Exception $e )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		if( $rebuilt != $this->rebuild )
		{
			throw new \IPS\Task\Queue\OutOfRangeException;
		}

		return ( $offset + $this->rebuild );
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

		$title = $classname::load( $data['categoryId'] )->_title;
		return array( 'text' => \IPS\Member::loggedIn()->language()->addToStack('rebuilding_stuff', FALSE, array( 'sprintf' => array( $title ) ) ), 'complete' => $data['count'] ? ( round( 100 / $data['count'] * $offset, 2 ) ) : 100 );
	}
}