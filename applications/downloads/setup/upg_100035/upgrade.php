<?php
/**
 * @brief		4.0.9 Upgrade Code
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		19 Jun 2015
 */

namespace IPS\downloads\setup\upg_100035;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * 4.0.9 Upgrade Code
 */
class _Upgrade
{
	/**
	 * Update widgets
	 *
	 * @return	array	If returns TRUE, upgrader will proceed to next step. If it returns any other value, it will set this as the value of the 'extra' GET parameter and rerun this step (useful for loops)
	 */
	public function finish()
	{
		$areas = array( 'core_widget_areas' );
		if ( \IPS\Application::appIsEnabled('cms') )
		{
			$areas[] = 'cms_page_widget_areas';
		}
		
		foreach ( $areas as $table )
		{
			foreach ( \IPS\Db::i()->select( '*', $table ) as $area )
			{
				$widgetsColumn = $table == 'core_widget_areas' ? 'widgets' : 'area_widgets';
				$whereClause = $table == 'core_widget_areas' ? array( 'id=? AND area=?', $area['id'], $area['area'] ) : array( 'area_page_id=? AND area_area=?', $area['area_page_id'], $area['area_area'] );
				
				$widgets = json_decode( $area[ $widgetsColumn ], TRUE );
				$update = FALSE;
				
				foreach ( $widgets as $k => $widget )
				{
					if ( $widget['key'] == 'recentFiles' )
					{
						$widgets[ $k ]['key'] = 'fileFeed';
						if ( isset( $widgets[ $k ]['configuration']['number_to_show'] ) )
						{
							$widgets[ $k ]['configuration']['widget_feed_show'] = $widgets[ $k ]['configuration']['number_to_show'];
							unset( $widgets[ $k ]['configuration']['number_to_show'] );
						}
						
						$update = TRUE;
					}
					
					if ( $update )
					{
						\IPS\Db::i()->update( $table, array( $widgetsColumn => json_encode( $widgets ) ), $whereClause );
					}
				}
			}
		}

		return TRUE;
	}
}