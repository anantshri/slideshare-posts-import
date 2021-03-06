<?php
/**
 * The WordPress Slideshare Posts Import plugin.
 *
 * @package   Slideshare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 *
 * @wordpress-plugin
 * Plugin Name:       Slideshare Posts Import
 * Plugin URI:        https://github.com/Spoon4/slideshare-posts-import
 * Description:       Slideshare API import in WordPress posts
 * Version:           1.0.0
 * Author:            Spoon
 * Author URI:        https://github.com/Spoon4
 * Text Domain:       slideshare-posts-import
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

if ( ! defined( 'IMPORT_DEFAULT_MEMORY_MAX' ) ) {
	define('IMPORT_DEFAULT_MEMORY_MAX', 32);
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

require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/class-slideshare-cron.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/class-slideshare-importer.php' );
	
// // Initialize schedule tasks
$cron = new SlideshareCron();
$cron->init();

register_activation_hook( __FILE__, array( 'Slideshare_Posts_Import', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Slideshare_Posts_Import', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Slideshare_Posts_Import', 'get_instance' ) );
	
/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-slideshare-posts-import-admin.php' );
	
	if( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		add_action( 'plugins_loaded', array( 'Slideshare_Posts_Import_Admin', 'get_instance' ) );
	} else {
		require_once( plugin_dir_path( __FILE__ ) . 'admin/includes/class-slideshare-ajax-response.php' );
		add_action( 'plugins_loaded', array( 'Slideshare_Posts_Import_Admin', 'add_ajax_actions' ) );
	}
}
