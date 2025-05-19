<?php
/**
 * Plugin Name: Maintenance Mode with Timer
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/maintenance-mode-pro/
 * Description: Easy to add maintenance mode in your website.
 * Author: Essential Plugin
 * Text Domain: maintenance-mode-with-timer
 * Domain Path: /languages/
 * Version: 1.3
 * Author URI: https://www.essentialplugin.com
 *
 * @package Maintenance Mode with Timer
 * @author Essential Plugin
 */

/**
 * Basic plugin definitions
 * 
 * @package Maintenance Mode with Timer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! defined( 'MTM_VERSION' ) ) {
	define( 'MTM_VERSION', '1.3' ); // Version of plugin
}
if( ! defined( 'MTM_DIR' ) ) {
    define( 'MTM_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'MTM_URL' ) ) {
    define( 'MTM_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'MTM_PLUGIN_BASENAME' ) ) {
	define( 'MTM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // plugin base name
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function mtm_load_textdomain() {

    global $wp_version;

    // Set filter for plugin's languages directory
    $mtm_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $mtm_lang_dir = apply_filters( 'mtm_languages_directory', $mtm_lang_dir );

    // Traditional WordPress plugin locale filter.
    $get_locale = get_locale();

    if ( $wp_version >= 4.7 ) {
        $get_locale = get_user_locale();
    }

    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  $get_locale, 'maintenance-mode-with-timer' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'maintenance-mode-with-timer', $locale );

    // Setup paths to current locale file
    $mofile_global  = WP_LANG_DIR . '/plugins/' . basename( MTM_DIR ) . '/' . $mofile;

    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
        load_textdomain( 'maintenance-mode-with-timer', $mofile_global );
    } else { // Load the default language files
        load_plugin_textdomain( 'maintenance-mode-with-timer', false, $mtm_lang_dir );
    }
}
add_action('plugins_loaded', 'mtm_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'mtm_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'mtm_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @since 1.0.0
 */
function mtm_install() {

    // Get settings for the plugin
    $mtm_options = get_option( 'mtm_options' );
    
    if( empty( $mtm_options ) ) { // Check plugin version option
        
        // Set default settings
        mtm_set_default_settings();
        
        // Update plugin version to option
        update_option( 'mtm_plugin_version', '1.0' );
    }
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @since 1.0.0
 */
function mtm_uninstall() {
    // Uninstall functionality
}

// Taking some globals
global $mtm_options;

// Functions file
require_once( MTM_DIR . '/includes/mtm-functions.php' );
$mtm_options = mtm_get_settings();

// Script Class File
require_once( MTM_DIR . '/includes/class-mtm-script.php' );

// Admin Class File
require_once( MTM_DIR . '/includes/admin/class-mtm-admin.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( MTM_DIR . '/includes/admin/mtm-how-it-work.php' );
}