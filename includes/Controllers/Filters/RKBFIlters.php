<?php
namespace BKBRKB\Controllers\Filters;

use Xenioushk\BwlPluginApi\Api\Filters\FiltersApi;
use BKBRKB\Callbacks\Filters\PostAccessCb;

/**
 * Class for registering the atfc tab.
 *
 * @since: 1.1.5
 * @package BKBRKB
 */
class RKBFIlters {

    /**
	 * Register filters.
	 */
    public function register() {

        // Initialize API.
        $filters_api = new FiltersApi();

        // Initialize callbacks.
        $post_access_cb = new PostAccessCb();

        // Filters.
        $filters = [
            [
                'tag'      => 'bkb_rkb_post_access',
                'callback' => [ $post_access_cb, 'check_status' ],
            ],
        ];

        $filters_api->add_filters( $filters )->register();
    }
}
