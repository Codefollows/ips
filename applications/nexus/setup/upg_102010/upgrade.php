<?php
/**
 * @brief		4.2.3 Upgrade Code
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Commerce
 * @since		31 Jul 2017
 */

namespace IPS\nexus\setup\upg_102010;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * 4.2.3 Upgrade Code
 */
class _Upgrade
{
	/**
	 * Repair Custom Field URLs
	 *
	 * @return	array	If returns TRUE, upgrader will proceed to next step. If it returns any other value, it will set this as the value of the 'extra' GET parameter and rerun this step (useful for loops)
	 */
	public function finish()
	{
		$support = new \IPS\nexus\extensions\core\FileStorage\Support;
		\IPS\Task::queue( 'core', 'RepairFileUrls', array( 'storageExtension' => 'filestorage__nexus_Support', 'count' => $support->count() ), 1 );

		$customer = new \IPS\nexus\extensions\core\FileStorage\Customer;
		\IPS\Task::queue( 'core', 'RepairFileUrls', array( 'storageExtension' => 'filestorage__nexus_Customer', 'count' => $customer->count() ), 1 );
		return TRUE;
	}
}