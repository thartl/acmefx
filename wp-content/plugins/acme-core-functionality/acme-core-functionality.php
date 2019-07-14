<?php
/**
 * Sets up Core Functionality for Acme FX.
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @author      Tomas Hartl
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Acme FX Core Functionality
 * Plugin URI:  https://parkdalewire.com
 * Description: Sets up Core Functionality for acmefx.ca.
 * Version:     1.0.0
 * Author:      Tomas Hartl
 * Author URI:  https://parkdalewire.com
 * Text Domain: acme-fx-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace ParkdaleWire\AcmeFxCore;


if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}


define( 'ACME_FX_CORE_VERSION', '1.0.0' );

define( 'ACME_FX_CORE_PLUGIN_PATH', __FILE__ ); // full path, ending in '.php'

define( 'ACME_FX_CORE_DIR', trailingslashit( __DIR__ ) ); // ends with a slash

$plugin_url = plugin_dir_url( __FILE__ );
if ( is_ssl() ) {
	$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
}
define( 'ACME_FX_CORE_URL', $plugin_url ); // ends with a slash


require_once ACME_FX_CORE_DIR . 'src/setup.php';

