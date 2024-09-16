<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Subscribe_To_Pdf
 * @subpackage Subscribe_To_Pdf/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Subscribe_To_Pdf
 * @subpackage Subscribe_To_Pdf/includes
 * @author     MirT <office@mirt.pl>
 */
class Subscribe_To_Pdf_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'subscribe-to-pdf',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
