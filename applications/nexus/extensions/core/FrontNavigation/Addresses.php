<?php
/**
 * @brief		Front Navigation Extension: Addresses
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		29 Apr 2014
 */

namespace IPS\nexus\extensions\core\FrontNavigation;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Front Navigation Extension: Addresses
 */
class _Addresses extends \IPS\core\FrontNavigation\FrontNavigationAbstract
{	
	/**
	 * Get Type Title which will display in the AdminCP Menu Manager
	 *
	 * @return	string
	 */
	public static function typeTitle()
	{
		return \IPS\Member::loggedIn()->language()->addToStack('client_addresses');
	}
		
	/**
	 * Can the currently logged in user access the content this item links to?
	 *
	 * @return	bool
	 */
	public function canAccessContent()
	{
		return ( \IPS\Member::loggedIn()->member_id AND \IPS\Member::loggedIn()->canAccessModule( \IPS\Application\Module::get( 'nexus', 'clients' ) ) );
	}
	
	/**
	 * Get Title
	 *
	 * @return	string
	 */
	public function title()
	{
		return \IPS\Member::loggedIn()->language()->addToStack('client_addresses');
	}
	
	/**
	 * Get Link
	 *
	 * @return	\IPS\Http\Url
	 */
	public function link()
	{
		return \IPS\Http\Url::internal( "app=nexus&module=clients&controller=addresses", 'front', 'clientsaddresses' );
	}
	
	/**
	 * Is Active?
	 *
	 * @return	bool
	 */
	public function active()
	{
		return \IPS\Dispatcher::i()->application->directory === 'nexus' and \IPS\Dispatcher::i()->module and \IPS\Dispatcher::i()->module->key === 'clients' and \IPS\Dispatcher::i()->controller == 'addresses';
	}
}