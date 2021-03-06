<?php
/**
 * @brief		Recent comments widget
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		25 Mar 2014
 */

namespace IPS\gallery\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Recent comments widget
 */
class _recentComments extends \IPS\Content\WidgetComment
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'recentComments';
	
	/**
	 * @brief	App
	 */
	public $app = 'gallery';
	
	/**
	 * @brief	Plugin
	 */
	public $plugin = '';

	/**
	 * @brief Class
	 */
	protected static $class = 'IPS\gallery\Image\Comment';

	/**
	 * @brief	Moderator permission to generate caches on [optional]
	 */
	protected $moderatorPermissions	= array( 'can_view_hidden_content', 'can_view_hidden_gallery_image_comment' );
}