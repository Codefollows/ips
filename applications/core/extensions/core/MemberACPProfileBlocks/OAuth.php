<?php
/**
 * @brief		ACP Member Profile: OAuth Apps
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		29 Nov 2017
 */

namespace IPS\core\extensions\core\MemberACPProfileBlocks;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * @brief	ACP Member Profile: Groups Block
 */
class _OAuth extends \IPS\core\MemberACPProfile\Block
{
	/**
	 * Get output
	 *
	 * @return	string
	 */
	public function output()
	{
		if ( \IPS\Member::loggedIn()->hasAcpRestriction( 'core', 'applications', 'oauth_tokens' ) and \IPS\Db::i()->select( 'COUNT(*)', 'core_oauth_clients', array( \IPS\Db::i()->findInSet( 'oauth_grant_types', array( 'authorization_code', 'implicit', 'password' ) ) ) )->first() )
		{

			$tokens = array();
			foreach ( \IPS\Db::i()->select( '*', 'core_oauth_server_access_tokens', array( 'member_id=?', $this->member->member_id ), 'issued DESC' ) as $token )
			{

				try
				{
					$client = \IPS\Api\OAuthClient::load( $token['client_id'] );
					
					$tokens[] = array(
						'title'					=> $client->_title,
						'use_refresh_tokens'	=> $client->use_refresh_tokens,
						'data'					=> $token
					);
				}
				catch ( \Exception $e ) {}
			}
			
			return \IPS\Theme::i()->getTemplate('memberprofile')->oauth( $this->member, $tokens );
		}
	}
}