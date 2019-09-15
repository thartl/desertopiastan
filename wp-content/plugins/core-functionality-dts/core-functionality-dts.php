<?php
/**
 * Core functionality plugin for Desertopiastan
 *
 * @package     ParkdaleWire\DTS_Core
 * @author      Tomas Hartl
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Desertopiastan Core Functionality
 * Plugin URI:  https://parkdalewire.com
 * Description: Core functionality plugin for Desertopiastan
 * Version:     1.0.3
 * Author:      Tomas Hartl
 * Author URI:  https://parkdalewire.com
 * Text Domain: dts-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace ParkdaleWire\DTS_Core;


if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}


define( 'DTS_CORE_FUNCTIONALITY_PLUGIN', __FILE__ );
define( 'DTS_CORE_FUNCTIONALITY_DIR', trailingslashit( __DIR__ ) );

$DTS_Core_plugin_url = plugin_dir_url( __FILE__ );
if ( is_ssl() ) {
	$DTS_Core_plugin_url = str_replace( 'http://', 'https://', $DTS_Core_plugin_url );
}

define( 'DTS_CORE_FUNCTIONALITY_URL', $DTS_Core_plugin_url );
define( 'DTS_CORE_FUNCTIONALITY_TEXT_DOMAIN', 'dts-core' );

// Bump this version number to force ACF sync
define( 'DTS_CORE_FUNCTIONALITY_VERSION', '1.0.3' );


require_once __DIR__ . '/src/setup.php';

require_once __DIR__ . '/src/acf.php';

