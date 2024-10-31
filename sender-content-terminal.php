<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sender.law/
 * @since             1.0.0
 * @package           Sender_Content_Terminal
 *
 * @wordpress-plugin
 * Plugin Name:       Sender Content Terminal
 * Plugin URI:        https://sender.law/wp-plugin
 * Description:       Sender Content Terminal allows you to include the latest news as well as choose the articles most relevant to your clientele from our extensive library.
 * Version:           1.0.1
 * Author:            Sender.law
 * Author URI:        https://sender.law/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sender-content-terminal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SENDER_CONTENT_TERMINAL_VERSION', '1.0.1' );

define('SENDER_CONTENT_TERMINAL_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sender-content-terminal-activator.php
 */
function activate_sender_content_terminal() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sender-content-terminal-activator.php';
	Sender_Content_Terminal_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sender-content-terminal-deactivator.php
 */
function deactivate_sender_content_terminal() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sender-content-terminal-deactivator.php';
	Sender_Content_Terminal_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sender_content_terminal' );
register_deactivation_hook( __FILE__, 'deactivate_sender_content_terminal' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sender-content-terminal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sender_content_terminal() {

	$plugin = new Sender_Content_Terminal();
	$plugin->run();

}
run_sender_content_terminal();
