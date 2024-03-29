<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/mtuszynski
 * @since      1.0.0
 *
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Subscribe_For_Pdf
 * @subpackage Wp_Subscribe_For_Pdf/admin
 * @author     MirT <tuszynski.mir@gmail.com>
 */
require_once plugin_dir_path(__FILE__) . 'partials/class-wp-subscribe-for-pdf-menu.php';

class Wp_Subscribe_For_Pdf_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$menu = new Wp_Subscribe_For_Pdf_Menu();
		add_action('admin_menu', array($menu, 'wp_subscribe_for_pdf_menu'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Subscribe_For_Pdf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Subscribe_For_Pdf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-subscribe-for-pdf-admin.css', array(), $this->version, 'all');
		wp_enqueue_style('datatables-style', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Subscribe_For_Pdf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Subscribe_For_Pdf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-subscribe-for-pdf-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_script('datatables-script', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', array('jquery'), $this->version, true);
	}
}
