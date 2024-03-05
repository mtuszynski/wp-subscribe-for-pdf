<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/includes
 * @author     MirT <tuszynski.mir@gmail.com>
 */
class Wp_Subscribe_For_Pdf_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		self::wp_subscribe_for_pdf_delete_table();
	}
	private static function wp_subscribe_for_pdf_delete_table()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wp_subscribe_for_pdf";

		$sql = "DROP TABLE IF EXISTS $table_name";

		$wpdb->query($sql);
	}
}
