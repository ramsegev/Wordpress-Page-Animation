<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www
 * @since             1.0.0
 * @package           page_animation
 *
 * @wordpress-plugin
 * Plugin Name:       Page Animation
 * Plugin URI:        www
 * Description:       This plugin will save you so much time animating you site
 * Version:           1.0.3
 * Author:            Ram Segev
 * Author URI:        www
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-animation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-page-animation-activator.php
 */
function activate_page_animation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-animation-activator.php';
	Page_animation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-page-animation-deactivator.php
 */
function deactivate_page_animation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-animation-deactivator.php';
	Page_animation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_page_animation' );
register_deactivation_hook( __FILE__, 'deactivate_page_animation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-page-animation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_page_animation() {

	$plugin = new Page_animation();
	$plugin->run();

}
run_page_animation();
