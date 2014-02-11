<?php
/**
 * The WordPress SlideShare Posts Import plugin.
 *
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 *
 * @wordpress-plugin
 * Plugin Name:       SlideShare Posts Import
 * Plugin URI:        https://github.com/Spoon4/slideshare-posts-import
 * Description:       SlideShare API import in WordPress posts
 * Version:           1.0.0
 * Author:            Spoon
 * Author URI:        https://github.com/Spoon4
 * Text Domain:       en_US
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/Spoon4/slideshare-posts-import
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'SLIDESHARE_API_URL' ) ) {
	define('SLIDESHARE_API_URL', 'https://www.slideshare.net/api/2/');
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-slideshare-posts-import.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-json-encoder.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/api/exceptions/class-slideshare-exception.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/exceptions/class-slideshare-http-exception.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/exceptions/class-slideshare-parser-exception.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/exceptions/class-slideshare-service-exception.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/api/interface-xml-parser.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/class-slideshare-xml-parser.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/api/models/class-slideshare-model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/models/class-user-model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/models/class-slideshow-model.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/models/class-tag-model.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/api/http/class-slideshare-http-request.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/http/class-slideshare-http-response.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/api/services/class-slideshare-service.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/services/class-slideshare-credentials.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/services/class-slideshare-slideshow-service.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/api/services/class-slideshare-user-service.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'SlideShare_Posts_Import', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SlideShare_Posts_Import', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'SlideShare_Posts_Import', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-slideshare-posts-import-admin.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/class-slideshare-importer.php' );
	
	if( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		add_action( 'plugins_loaded', array( 'SlideShare_Posts_Import_Admin', 'get_instance' ) );
	} else {
		add_action( 'plugins_loaded', array( 'SlideShare_Posts_Import_Admin', 'add_ajax_actions' ) );
	}
}
