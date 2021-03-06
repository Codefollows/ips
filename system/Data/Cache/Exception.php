<?php
/**
 * @brief		Abstract Storage Class
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		07 May 2013
 */

namespace IPS\Data\Cache;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Cache Exception Class
 */
class _Exception extends \RuntimeException
{
	
}