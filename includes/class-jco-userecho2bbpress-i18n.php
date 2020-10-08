<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       boldgrid.com
 * @since      1.0.0
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/includes
 * @author     Jesse C Owens <jesseo@boldgrid.com>
 */
class Jco_Userecho2bbpress_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'jco-userecho2bbpress',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
