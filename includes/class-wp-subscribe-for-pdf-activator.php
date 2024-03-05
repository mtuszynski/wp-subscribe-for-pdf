<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/includes
 * @author     MirT <tuszynski.mir@gmail.com>
 */
class Wp_Subscribe_For_Pdf_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::wp_subscribe_for_pdf_create_table();
	}
	private static function wp_subscribe_for_pdf_create_table()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'wp_subscribe_for_pdf';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			first_name varchar(55) NOT NULL,
			last_name varchar(55) NOT NULL,
			email varchar(255) NOT NULL,
			submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			INDEX email_index (email)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}
