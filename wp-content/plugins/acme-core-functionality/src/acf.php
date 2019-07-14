<?php
/**
 * Advanced Custom Fields setup
 *
 * @package     ParkdaleWire\AcmeFxCore
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\AcmeFxCore;


class BE_ACF_Customizations {

	public function __construct() {

		// Save and sync fields in functionality plugin
		add_filter( 'acf/settings/save_json', array( $this, 'get_local_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_local_json_path' ) );

	}

	/**
	 * Define where the local JSON is saved
	 *
	 * @return string
	 */
	public function get_local_json_path() {
		return ACME_FX_CORE_DIR . '/acf-json';
	}

	/**
	 * Add our path for the local JSON
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function add_local_json_path( $paths ) {
		$paths[] = ACME_FX_CORE_DIR . '/acf-json';

		return $paths;
	}

}

new BE_ACF_Customizations();


