<?php
/**
 * @brief		Editor Extension: Record Form
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Content
 * @since		20 Feb 2014
 */

namespace IPS\cms\extensions\core\EditorLocations;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Editor Extension: Record Content
 */
class _Records
{
	/**
	 * @brief	Flag to indicate we don't want to be listed as a selectable area when configuring buttons
	 */
	public static $buttonLocation	= FALSE;

	/**
	 * Can we use HTML in this editor?
	 *
	 * @param	\IPS\Member	$member	The member
	 * @return	bool|null	NULL will cause the default value (based on the member's permissions) to be used, and is recommended in most cases. A boolean value will override that.
	 */
	public function canUseHtml( $member )
	{
		return NULL;
	}
	
	/**
	 * Can we use attachments in this editor?
	 *
	 * @param	\IPS\Member	$member	The member
	 * @param	\IPS\Helpers\Form\Editor $editor The editor instance
	 * @return	bool|null	NULL will cause the default value (based on the member's permissions) to be used, and is recommended in most cases. A boolean value will override that.
	 */
	public function canAttach( $member, $editor )
	{
		if( !$editor->options['allowAttachments'] )
		{
			return FALSE;
		}

		return NULL;
	}

	/**
	 * Permission check for attachments
	 *
	 * @param	\IPS\Member	$member		The member
	 * @param	int|null	$id1		Primary ID
	 * @param	int|null	$id2		Secondary ID
	 * @param	string|null	$id3		Arbitrary data
	 * @param	array		$attachment	The attachment data
	 * @param	bool		$viewOnly	If true, just check if the user can see the attachment rather than download it
	 * @return	bool
	 */
	public function attachmentPermissionCheck( $member, $id1, $id2, $id3, $attachment, $viewOnly=FALSE )
	{
		if ( ! $id3 )
		{
			throw new \OutOfRangeException;
		}
		
		try
		{
			$recordClass = '\IPS\cms\Records' . $id3;
			
			return $recordClass::load( $id1 )->canView( $member );
		}
		catch ( \OutOfRangeException $e )
		{
			return FALSE;
		}
	}
	
	/**
	 * Attachment lookup
	 *
	 * @param	int|null	$id1	Primary ID
	 * @param	int|null	$id2	Secondary ID
	 * @param	string|null	$id3	Arbitrary data
	 * @return	\IPS\Http\Url|\IPS\Content|\IPS\Node\Model
	 * @throws	\LogicException
	 */
	public function attachmentLookup( $id1, $id2, $id3 )
	{
		try
		{
			if ( $id3 )
			{
				$recordClass = '\IPS\cms\Records' . $id3;
				$return = $recordClass::load( $id1 );
				$return->url(); // Need to check that won't throw an exception later, which might happen if the database no longer has a page
				return $return;
			}
			else
			{
				return FALSE;
			}
		}
		catch ( \Exception $e )
		{
			return FALSE;
		}
	}
}