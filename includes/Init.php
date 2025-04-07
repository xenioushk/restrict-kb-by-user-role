<?php

namespace BKBRKB;

/**
 * Class for Initialize plugin required searvices.
 *
 * @since: 1.1.0
 * @package BKBRKB
 */
class Init {

	/**
	 * Get all the services.
	 */
	public static function get_services() {

		/**
		 * Add plugin required classes.
        *
		 * @since 1.1.0
		 */

		$services = [];

		$service_classes = [
			'helpers' => self::get_helper_classes(),
			// 'base'     => self::get_base_classes(),
			// 'meta'     => self::get_meta_classes(),
			// 'notices'  => self::get_notices_classes(),
			// 'wpbakery' => self::get_wpbakery_classes(),
		];

		foreach ( $service_classes as $service_class ) {
			$services = array_merge( $services, $service_class );
		}

		return $services;

	}

	/**
	 * Registered all the classes.
     *
	 * @since 1.0.0
	 */
	public static function register_services() {

		if ( empty( self::get_services() ) ) {
			return;
		}

		foreach ( self::get_services() as $service ) {

			$service = self::instantiate( $service );

			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Instantiate all the registered service classes.
     *
     * @param   class $service The class to instantiate.
     * @author   Md Mahbub Alam Khan
     * @return  object
     * @since   1.1.0
	 */
	private static function instantiate( $service ) {
		return new $service();
	}

	/**
	 * Get Base classes.
	 *
	 * @return array
	 */
	private static function get_base_classes() {
		$classes = [
			Base\Enqueue::class,
			Base\CustomTheme::class,
			Base\AdminEnqueue::class,
			Base\FrontendInlineJs::class,
			Base\PluginUpdate::class,
			Base\Language::class,
			Base\AdminAjaxHandlers::class,

		];
		return $classes;
	}

	/**
	 * Get Helper classes.
	 *
	 * @return array
	 */
	private static function get_helper_classes() {
		$classes = [
			Helpers\PluginConstants::class,
		];
		return $classes;
	}

	/**
	 * Get Meta classes.
	 *
	 * @return array
	 */
	private static function get_meta_classes() {
		$classes = [
			Controllers\PluginMeta\MetaInfo::class,
		];
		return $classes;
	}


	/**
	 * Get WPBakery classes.
	 *
	 * @return array
	 */
    private static function get_wpbakery_classes() {

			$classes = [
				Controllers\Shortcodes\AddonShortcodes::class,
				Controllers\WPBakery\Shortcodes\ShortcodeParams::class,
				Controllers\WPBakery\Elements\Tags::class,
				Controllers\WPBakery\Elements\AskQuestion::class,
				Controllers\WPBakery\Elements\Category::class,
				Controllers\WPBakery\Elements\Counter::class,
				Controllers\WPBakery\Elements\ExternalForm::class,
				Controllers\WPBakery\Elements\Posts::class,
				Controllers\WPBakery\Elements\SearchBox::class,
				Controllers\WPBakery\Elements\Tabs::class,
				Controllers\WPBakery\Elements\Tags::class,
			];

			return $classes;
	}

	/**
	 * Get Notices classes.
	 *
	 * @return array
	 */
	private static function get_notices_classes() {
		$classes = [
			Controllers\Notices\PluginNoticesAjaxHandler::class,
		];
		return $classes;
	}
}
