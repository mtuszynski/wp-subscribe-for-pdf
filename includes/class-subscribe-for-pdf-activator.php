<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Subscribe_For_Pdf
 * @subpackage Subscribe_For_Pdf/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Subscribe_For_Pdf
 * @subpackage Subscribe_For_Pdf/includes
 * @author     MirT <office@mirt.pl>
 */
class Subscribe_For_Pdf_Activator
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
		self::create_table('subscribe_for_pdf_subscribers', "
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(55) NOT NULL,
            last_name varchar(55) NOT NULL,
            email varchar(255) NOT NULL,
            submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            INDEX email_index (email)
        ");

		self::create_table('subscribe_for_pdf_settings', "
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            document_url varchar(255) NOT NULL,
			delete_tables_on_deactivation tinyint(1) DEFAULT 0,
            PRIMARY KEY  (id)
        ");
	}
	private static function create_table($table_suffix, $sql)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . $table_suffix;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name ($sql) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}
