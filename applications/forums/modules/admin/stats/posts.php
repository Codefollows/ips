<?php
/**
 * @brief		posts
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Forums
 * @since		18 Aug 2014
 */

namespace IPS\forums\modules\admin\stats;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * posts
 */
class _posts extends \IPS\Dispatcher\Controller
{
	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'posts_manage' );

		$tabs		= array( 'total' => 'stats_posts_tab_total', 'byforum' => 'stats_posts_tab_byforum' );
		$activeTab	= ( isset( \IPS\Request::i()->tab ) and array_key_exists( \IPS\Request::i()->tab, $tabs ) ) ? \IPS\Request::i()->tab : 'total';

		if ( $activeTab === 'total' )
		{
			$chart = new \IPS\Helpers\Chart\Database( \IPS\Http\Url::internal( "app=forums&module=stats&controller=posts" ), 'forums_posts', 'post_date', '', array( 
				'isStacked' => FALSE,
				'backgroundColor' 	=> '#ffffff',
				'colors'			=> array( '#10967e' ),
				'hAxis'				=> array( 'gridlines' => array( 'color' => '#f5f5f5' ) ),
				'lineWidth'			=> 1,
				'areaOpacity'		=> 0.4
			) );
			$chart->addSeries( \IPS\Member::loggedIn()->language()->addToStack( 'stats_new_posts' ), 'number', 'COUNT(*)', FALSE );
			$chart->title = \IPS\Member::loggedIn()->language()->addToStack( 'stats_posts_title' );
			$chart->availableTypes = array( 'AreaChart', 'ColumnChart', 'BarChart' );
		}
		else
		{
			$chart = new \IPS\Helpers\Chart\Database( \IPS\Http\Url::internal( "app=forums&module=stats&controller=posts&tab=" . $activeTab ), 'forums_posts', 'post_date', '', array( 
					'isStacked' => FALSE,
					'backgroundColor' 	=> '#ffffff',
					'hAxis'				=> array( 'gridlines' => array( 'color' => '#f5f5f5' ) ),
					'lineWidth'			=> 1,
					'areaOpacity'		=> 0.4,
					'chartArea'			=> array( 'width' => '70%', 'left' => '5%' ),
					'height'			=> 400,
				),
				'ColumnChart',
				'monthly',
				array( 'start' => ( new \IPS\DateTime )->sub( new \DateInterval('P90D') ), 'end' => new \IPS\DateTime ),
				array(),
				'byforum' 
			);

			$chart->title = \IPS\Member::loggedIn()->language()->addToStack( 'stats_posts_title_byforum' );
			$chart->availableTypes = array( 'ColumnChart' );

			$chart->groupBy = 'forum_id';
			$chart->joins	= array( array( 'forums_topics', 'forums_topics.tid=forums_posts.topic_id' ) );

			foreach( \IPS\Db::i()->select( '*', 'forums_forums', array( 'posts>?', 0 ) ) as $forum )
			{
				$chart->addSeries( \IPS\Member::loggedIn()->language()->addToStack( 'forums_forum_' . $forum['id'] ), 'number', 'COUNT(*)', TRUE, $forum['id'] );
			}
		}

		if ( \IPS\Request::i()->isAjax() )
		{
			\IPS\Output::i()->output = (string) $chart;
		}
		else
		{	
			\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__forums_stats_posts');
			\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global', 'core' )->tabs( $tabs, $activeTab, (string) $chart, \IPS\Http\Url::internal( "app=forums&module=stats&controller=posts" ) );
		}
	}
}