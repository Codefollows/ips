<?php
/**
 * @brief		Multi-Factor Authentication Area
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		26 Aug 2016
 */

namespace IPS\core\extensions\core\MFAArea;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Multi-Factor Authentication Area
 */
class _SecurityQuestions
{
	/**
	 * Is this area available and should show in the ACP configuration?
	 *
	 * @return	bool
	 */
	public function isEnabled()
	{
		return TRUE;
	}
}