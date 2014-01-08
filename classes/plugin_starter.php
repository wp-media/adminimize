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
	public $basename = '';

	/**
	 * Flag if the textdomain was loaded or not
	 * @var boolean
	 */
	public $textdomain_loaded = false;

	/**
	 * Flag to show if all styles are loaded
	 * @var bool
	 */
	public $all_styles_loaded = false;

	/**
	 * Array with flags which style was loaded
	 * @var array
	 */
	public $style_loaded = array();

	/**
	 * Flag to show if all scripts are loaded
	 * @var bool
	 */
	public $all_scripts_loaded = false;

	/**
	 * Array with flags which script was loaded
	 * @var array
	 */
	public $script_loaded = array();

	/**
	 * Loads the plugin textdomain
	 * @param PluginHeaderReader $plugindata PluginHeaderReader object containing basic informations (the plugin header) about the plugin
	 * @return bool true on success, false on error
	 */
	public function load_textdomain( PluginHeaderReader $plugindata ) {

		$this->textdomain_loaded = load_plugin_textdomain(
			$plugindata->TextDomain,
			false,
			dirname( plugin_basename( $this->basename ) ) . $plugindata->DomainPath
		);

		return true;

	}

	/**
	 * Load styles
	 * @param array	$styles Array with stylesheets in format 'slug' => array( 'file' => 'path/to/file', 'enqueue' => true|false )
	 */
	public function load_styles( array $styles ) {

		foreach ( $styles as $slug => $data ) {

			wp_register_style(
				$slug,
				plugins_url( $data['file'], $this->basename )
			);

			$is_registered = isset( $GLOBALS['wp_styles']->registered[ $slug ] );
			$this->all_styles_loaded     = $is_registered;
			$this->style_loaded[ $slug ] = $is_registered;

			if ( true == $data['enqueue'] )
				wp_enqueue_style( $slug );

		}

		return $this->all_styles_loaded;

	}

	/**
	 * Load scripts
	 * @param array	$scripts Array with scripts in format 'slug' => array( 'file' => 'path/to/file', 'enqueue' => true|false, 'args' => array $args )
	 * Array args contain the rest of the arguments like in_footer, version and dependencies
	 */
	public function load_scripts( array $scripts ) {

		foreach ( $scripts as $slug => $data ) {

			$data['args'] = array_merge( array( 'deps' => array(), 'version' => false, 'in_footer' => false ), $data['args'] );

			wp_register_script(
				$slug,
				plugins_url( $data['file'], $this->basename ),
				$data['args']['deps'],
				$data['args']['version'],
				$data['args']['in_footer']
			);

			$is_registered = isset( $GLOBALS['wp_scripts']->registered[ $slug ] );
			$this->all_scripts_loaded     = $is_registered;
			$this->script_loaded[ $slug ] = $is_registered;

			if ( true == $data['enqueue'] )
				wp_enqueue_script( $slug );

		}

		return $this->all_scripts_loaded;

	}

}

}
