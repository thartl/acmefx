<?php
/**
 * Include files and enqueue resources.
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\AcmeFxCore;


require_once ACME_FX_CORE_DIR . 'src/acf.php';

if ( is_admin() ) {
	require_once ACME_FX_CORE_DIR . 'admin/admin-setup.php';
}

// Include post types and taxonomies
require_once ACME_FX_CORE_DIR . 'inc/cpt-credits.php';

//add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
/**
 * Enqueue the plugin assets.
 *
 * @return void
 * @since 1.0.0
 *
 */
function enqueue_assets() {

//	wp_enqueue_style(
//		'micromodal-styles',
//		ACME_FX_CORE_URL . 'assets/css/micromodal.css',
//		array(),
//		ACME_FX_CORE_VERSION
//	);

	wp_enqueue_script(
		'pw-global',
		ACME_FX_CORE_URL . 'assets/js/pw-global.js',
		array(),
		ACME_FX_CORE_VERSION,
		true
	);

}



