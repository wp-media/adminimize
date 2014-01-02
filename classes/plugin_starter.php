<?php
/**
 * Class to initialize the basic setup for a plugin
 * - load styles
 * - load scripts
 * - load textdomain
 * - ...
 */

if ( ! class_exists( 'Plugin_Starter' ) ) {

class Plugin_Starter
{

	/**
	 * Basename of the plugin file
	 * @var string
	 */
	public static $basename = '';

	/**
	 * Flag if the textdomain was loaded or not
	 * @var boolean
	 */
	public static $textdomain_loaded = false;

	/**
	 * Loads the plugin textdomain
	 * @param PluginHeader_Reader $plugindata PluginHeader_Reader object containing basic informations (the plugin header) about the plugin
	 */
	public static function load_textdomain( PluginHeaderReader $plugindata ) {

		self::$textdomain_loaded = load_plugin_textdomain(
			$plugindata->TextDomain,
			FALSE,
			self::$basename . $plugindata->DomainPath
		);

	}

	/**
	 * Load stylesheets
	 * @param PluginHeader_Reader $plugindata
	 */
	public static function load_styles(  $basefile, array $styles ) {

		foreach ( $styles as $slug => $file ) {

			wp_register_style(
				$slug,
				plugins_url( $file, $basefile )
			);

			wp_enqueue_style( $slug );

		}

	}

}

}