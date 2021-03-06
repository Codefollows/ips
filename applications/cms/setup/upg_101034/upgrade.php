<?php
/**
 * @brief		4.1 Upgrade Code
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Content
 * @since		08 Apr 2016
 */

namespace IPS\cms\setup\upg_101034;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * 4.1 Upgrade Code
 */
class _Upgrade
{
		
	/**
	 * Finish - This is run after all apps have been upgraded
	 *
	 * @return	mixed	If returns TRUE, upgrader will proceed to next step. If it returns any other value, it will set this as the value of the 'extra' GET parameter and rerun this step (useful for loops)
	 * @note	We opted not to let users run this immediately during the upgrade because of potential issues (it taking a long time and users stopping it or getting frustrated) but we can revisit later
	 */
	public function finish()
	{
		\IPS\cms\Databases::rebuildReciprocalLinkMaps();
	}
}