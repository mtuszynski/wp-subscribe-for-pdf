<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Subscribe_To_Pdf
 * @subpackage Subscribe_To_Pdf/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Subscribe_To_Pdf
 * @subpackage Subscribe_To_Pdf/includes
 * @author     MirT <office@mirt.pl>
 */
class Subscribe_To_Pdf_Deactivator
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
		if (self::should_delete_tables()) {
			self::drop_table('subscribe_for_pdf_subscribers');
			self::drop_table('subscribe_for_pdf_settings');
		}
	}

	/**
	 * Checks whether database tables should be deleted upon plugin deactivation.
	 *
	 * This function queries the 'subscribe_for_pdf_settings' table to determine
	 * if the tables should be removed when the plugin is deactivated. It looks 
	 * for the value in the 'delete_tables_on_deactivation' column for the record 
	 * with an 'id' of 1.
	 *
	 * @since 1.0.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @return bool True if tables should be deleted, false otherwise.
	 */
	private static function should_delete_tables()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'subscribe_for_pdf_settings';
		$query = $wpdb->prepare("SELECT delete_tables_on_deactivation FROM $table_name WHERE id = %d", 1);
		$result = $wpdb->get_var($query);
		return (bool) $result;
	}

	/**
	 * Drop a table from the database.
	 *
	 * @since    1.0.0
	 */
	private static function drop_table($table_suffix)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . $table_suffix;
		$sql = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query($sql);
	}
}
