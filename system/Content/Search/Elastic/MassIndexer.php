<?php
/**
 * @brief		Elasticsearch Mass Indexer
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		15 Nov 2017
*/

namespace IPS\Content\Search\Elastic;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Elasticsearch Mass Indexer
 */
class _MassIndexer extends \IPS\Content\Search\Elastic\Index
{
	/**
	 * @brief	Data to store in bulk
	 */
	protected $data = array();
	
	/**
	 * Index an item
	 *
	 * @param	\IPS\Content\Searchable	$object	Item to add
	 * @return	void
	 */
	public function index( \IPS\Content\Searchable $object )
	{
		if ( $indexData = $this->indexData( $object ) and $indexData['index_permissions'] )
		{
			if ( $object instanceof \IPS\Content\Item )
			{
				$parent = $object;
			}
			elseif ( $object instanceof \IPS\Content\Comment )
			{
				$parent = $object->item();
			}
			
			$this->data[] = array(
				'index'		=> array(
					'_index'	=> trim( $this->url->data[ \IPS\Http\Url::COMPONENT_PATH ], '/' ),
					'_type'		=> 'content',
					'_id'		=> $this->getIndexId( $object ),
				)
			);
			$this->data[] = $indexData;
		}
	}
	
	/**
	 * Commit at the end of the request
	 *
	 * @return	void
	 */
	public function __destruct()
	{
		if ( $this->data )
		{
			$json = array();
			foreach ( $this->data as $data )
			{
				$json[] = json_encode( $data );
			}
			$json = implode( "\n", $json );
			
			$this->url->setPath( '/_bulk' )->request()->setHeaders( array( 'Content-Type' => 'application/x-ndjson' ) )->post( $json . "\n" );
		}
	}
}