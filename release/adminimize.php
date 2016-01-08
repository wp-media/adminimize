<?php
/**
 * Plugin Name: Adminimize
 * Plugin URI:  https://wordpress.org/plugins/adminimize/
 * Text Domain: adminimize
 * Domain Path: /resources/languages
 * Description: Visually compresses the administrative meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for all roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de/
 * Version:     2.0.0-alpha
 * License:     GPLv3+
 */

namespace Adminimize;

use Inpsyde\Autoload;

! defined( 'ABSPATH' ) and exit;

require_once __DIR__ . '/src/Autoload/bootstrap.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );
/**
 * Initialize in the WP circus.
 */
function init() {

	$autoload = new Autoload\Autoload();
	$autoload->add_rule( new Autoload\NamespaceRule( __DIR__ . '/src/', __NAMESPACE__ ) );

	$plugin = new Plugin( __FILE__ );
	$plugin->init();
}