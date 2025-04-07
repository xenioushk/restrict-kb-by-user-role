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
class TitleCb {


	public function modify( $title ) {

		global $post;

		$bkb_data = $this->baseController->bkb_data;

		$bkb_rkb_status = get_post_meta( $post->ID, 'bkb_rkb_status', true );

		if ( $bkb_rkb_status == 1 ) {
				$bkb_display_lock_icon = " <i class='fa fa-lock'></i>";
		} else {
				$bkb_display_lock_icon = '';
		}

		if ( isset( $bkb_data['bkb_rkb_lock_icon'] ) && $bkb_data['bkb_rkb_lock_icon'] == 1 ) {
				$bkb_display_lock_icon = '';
		}

		return $title . $bkb_display_lock_icon;
	}
}
