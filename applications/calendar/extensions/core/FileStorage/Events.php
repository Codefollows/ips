<?php
/**
 * @brief		File Storage Extension: Events
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Calendar
 * @since		13 Jan 2014
 */

namespace IPS\calendar\extensions\core\FileStorage;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * File Storage Extension: Events
 */
class _Events
{
	/**
	 * Count stored files
	 *
	 * @return	int
	 */
	public function count()
	{
		return \IPS\Db::i()->select( 'COUNT(*)', 'calendar_events', 'event_cover_photo IS NOT NULL' )->first();
	}
	
	/**
	 * Move stored files
	 *
	 * @param	int			$offset					This will be sent starting with 0, increasing to get all files stored by this extension
	 * @param	int			$storageConfiguration	New storage configuration ID
	 * @param	int|NULL	$oldConfiguration		Old storage configuration ID
	 * @throws	\Underflowexception				When file record doesn't exist. Indicating there are no more files to move
	 * @return	void
	 */
	public function move( $offset, $storageConfiguration, $oldConfiguration=NULL )
	{
		$record	= \IPS\Db::i()->select( '*', 'calendar_events', 'event_cover_photo IS NOT NULL', 'event_id', array( $offset, 1 ) )->first();
		
		try
		{
			$file	= \IPS\File::get( $oldConfiguration ?: 'calendar_Events', $record['event_cover_photo'] )->move( $storageConfiguration );
			
			if ( (string) $file != $record['event_cover_photo'] )
			{
				\IPS\Db::i()->update( 'calendar_events', array( 'event_cover_photo' => (string) $file ), array( 'event_id=?', $record['event_id'] ) );
			}
		}
		catch( \Exception $e )
		{
			/* Any issues are logged and the \IPS\Db::i()->update not run as the exception is thrown */
		}
	}
	
	/**
	 * Fix all URLs
	 *
	 * @param	int			$offset					This will be sent starting with 0, increasing to get all files stored by this extension
	 * @return void
	 */
	public function fixUrls( $offset )
	{
		$record	= \IPS\Db::i()->select( '*', 'calendar_events', 'event_cover_photo IS NOT NULL', 'event_id', array( $offset, 1 ) )->first();
		
		if ( $new = \IPS\File::repairUrl( $record['event_cover_photo'] ) )
		{
			\IPS\Db::i()->update( 'calendar_events', array( 'event_cover_photo' => $new ), array( 'event_id=?', $record['event_id'] ) );
		}
	}
	
	/**
	 * Check if a file is valid
	 *
	 * @param	string	$file		The file path to check
	 * @return	bool
	 */
	public function isValidFile( $file )
	{
		try
		{
			$record	= \IPS\Db::i()->select( '*', 'calendar_events', array( 'event_cover_photo=?', (string) $file ) )->first();

			return TRUE;
		}
		catch ( \UnderflowException $e )
		{
			return FALSE;
		}
	}

	/**
	 * Delete all stored files
	 *
	 * @return	void
	 */
	public function delete()
	{
		foreach( \IPS\Db::i()->select( '*', 'calendar_events', 'event_cover_photo IS NOT NULL' ) as $event )
		{
			try
			{
				\IPS\File::get( 'calendar_Events', $event['event_cover_photo'] )->delete();
			}
			catch( \Exception $e ){}
		}
	}
}