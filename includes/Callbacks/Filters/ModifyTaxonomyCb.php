<?php
namespace BKBRKB\Callbacks\Filters;

use BKBRKB\Helpers\RkbHelpers;
use BKBRKB\Helpers\PluginConstants;

/**
 * Class for registering taxonomy callback.
 *
 * @package BKBRKB
 * @since: 1.0.0
 * @author: Mahbub Alam Khan
 */
class ModifyTaxonomyCb {

	/**
	 * Modify the content of the taxonomy.
	 *
	 * @param string $content The content to be modified.
	 * @return string
	 */
	public function modify_exceprt( $content ) {

		global $post;

		$options = PluginConstants::$plugin_options;

		if ( ! is_admin() && is_tax( 'bkb_category' ) && isset( $options['bkb_cat_default_tpl_ordering_status']['enabled'] ) && $options['bkb_cat_default_tpl_ordering_status']['enabled'] == 'on' ) {

				$bkb_rkb_post_access_result = apply_filters( 'bkb_rkb_post_access', $post->ID );

			if ( $bkb_rkb_post_access_result != 1 ) {
					return $bkb_rkb_post_access_result;
			} else {
					return $content;
			}
		} elseif ( ! is_admin() && is_tax( 'bkb_tags' ) && isset( $options['bkb_tag_default_tpl_ordering_status']['enabled'] ) && $options['bkb_tag_default_tpl_ordering_status']['enabled'] == 'on' ) {

				$bkb_rkb_post_access_result = apply_filters( 'bkb_rkb_post_access', $post->ID );

			if ( $bkb_rkb_post_access_result != 1 ) {
					return $bkb_rkb_post_access_result;
			} else {
					return $content;
			}
		} elseif ( ! is_admin() && get_post_type( $post->ID ) == 'bwl_kb' ) {

				$bkb_rkb_post_access_result = apply_filters( 'bkb_rkb_post_access', $post->ID );

			if ( $bkb_rkb_post_access_result != 1 ) {
					return $bkb_rkb_post_access_result;
			} else {
					return $content;
			}
		} else {
				return $content;
		}
	}
}
