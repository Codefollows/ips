<?php
/**
 * @brief		Theme Changer
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		7 Oct 2014
 */

namespace IPS\core\modules\admin\system;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Theme Changer
 */
class _theme extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		parent::execute();
	}

	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Session::i()->csrfCheck();
		
		\IPS\Member::loggedIn()->acp_skin = (int) \IPS\Request::i()->id;
		\IPS\Member::loggedIn()->save();

		\IPS\Output::i()->redirect( \IPS\Request::i()->referrer() ?: \IPS\Http\Url::internal( '' ) );
	}
}