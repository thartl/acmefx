<?php
/**
 * Set up admin.
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\AcmeFxCore;


add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\load_admin_styles' );
/**
 * Enqueue admin styles.
 *
 * @return void
 * @since   1.0.0
 *
 */
function load_admin_styles() {

	wp_enqueue_style(
		'acme-fx-admin-style',
		ACME_FX_CORE_URL . 'assets/css/admin-style.css',
		array(),
		ACME_FX_CORE_VERSION
	);

}



