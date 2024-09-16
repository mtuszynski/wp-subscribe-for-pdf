<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/mtuszynski
 * @since             1.0.0
 * @package           Subscribe_For_Pdf
 *
 * @wordpress-plugin
 * Plugin Name:       Subscribe for PDF
 * Plugin URI:        https://https://github.com/mtuszynski/wp-subscribe-for-pdf
 * Description:       The WP-Subscribe-for-PDF plugin is a versatile WordPress plugin designed for easy integration into any page, enabling users to access a specified PDF file after submitting their details through a form. This project has been developed as part of a recruitment task, which demonstrates the functionality and coding standards expected in a professional setting.
 * Version:           1.0.0
 * Author:            MirT
 * Author URI:        https://https://github.com/mtuszynski/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       subscribe-for-pdf
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('Subscribe_For_Pdf_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-subscribe-for-pdf-activator.php
 */
function activate_Subscribe_For_Pdf()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-subscribe-for-pdf-activator.php';
	Subscribe_For_Pdf_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-subscribe-for-pdf-deactivator.php
 */
function deactivate_Subscribe_For_Pdf()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-subscribe-for-pdf-deactivator.php';
	Subscribe_For_Pdf_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_Subscribe_For_Pdf');
register_deactivation_hook(__FILE__, 'deactivate_Subscribe_For_Pdf');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-subscribe-for-pdf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Subscribe_For_Pdf()
{

	$plugin = new Subscribe_For_Pdf();
	$plugin->run();
}
run_Subscribe_For_Pdf();
