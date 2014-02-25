<?php
/**
 * Slideshare Posts Import.
 *
 * @package   Slideshare_Posts_Import_Admin
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-slideshare-posts-import.php`
 *
 * @package Slideshare_Posts_Import_Admin
 * @author  Spoon <spoon4@gmail.com>
 */
class Slideshare_Posts_Import_Admin 
{
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = array();

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() 
	{
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Slideshare_Posts_Import::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() 
	{
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() 
	{
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( in_array($screen->id, $this->plugin_screen_hook_suffix) ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Slideshare_Posts_Import::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() 
	{
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( in_array($screen->id, $this->plugin_screen_hook_suffix) ) {
            wp_register_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), Slideshare_Posts_Import::VERSION);
            wp_localize_script($this->plugin_slug . '-admin-script', 'AjaxParams', array( 
                'ajaxurl'                => admin_url( 'admin-ajax.php' ),
                'import_nonce'           => wp_create_nonce('_wp_slideshare_import_nonce'),
                'default_error_label'    => __( 'Error !' ),
                'import_success_label'   => __( 'Import succeed !' ),
                'import_success_message' => __( '<strong>{0}</strong> slideshows was found for user <strong>{1}</strong> and <strong>{2}</strong> posts was created.' ),
            ));
            wp_enqueue_script( $this->plugin_slug . '-admin-script' );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() 
	{
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		if(function_exists('add_menu_page')) {
			$settings_slug = $this->plugin_slug.'-settings';
			
			$this->plugin_screen_hook_suffix[] = add_menu_page(
				__( 'Slideshare Posts Import Settings', $this->plugin_slug ), 
				__( 'Slideshare Posts', $this->plugin_slug ), 
				10, $settings_slug, 
				array( $this, 'display_plugin_admin_page' ),
				plugins_url( 'assets/images/icon-slideshare.png', __FILE__ )
			);
			$this->plugin_screen_hook_suffix[] = add_submenu_page(
				$settings_slug, 
				__( 'Global settings', $this->plugin_slug ), 
				__( 'Global settings', $this->plugin_slug ), 
				10, $settings_slug, 
				array( $this, 'display_plugin_admin_page' )
			);
			$this->plugin_screen_hook_suffix[] = add_submenu_page(
				$settings_slug, 
				__( 'Import slideshows', $this->plugin_slug ), 
				__( 'Import slideshows', $this->plugin_slug ), 
				10, $this->plugin_slug.'-import', 
				array( $this, 'display_plugin_import_page' )
			);
			$this->plugin_screen_hook_suffix[] = add_submenu_page(
				$settings_slug, 
				__( 'Imported posts', $this->plugin_slug ), 
				__( 'Imported posts', $this->plugin_slug ), 
				10, $this->plugin_slug.'-posts', 
				array( $this, 'display_plugin_posts_page' )
			);
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() 
	{
		include_once( 'views/admin-settings.php' );
	}

	/**
	 * Render the import page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_import_page() 
	{
		include_once( 'views/admin-import.php' );
	}

	/**
	 * Render the imported posts page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_posts_page() 
	{
		include_once( 'views/admin-posts-list.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) 
	{
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Add ajax action hooks.
	 *
	 * @since    1.0.0
	 */
	public static function add_ajax_actions()
	{
		require_once( 'includes/admin-ajax-hooks.php' );
		
		$plugin_ajax_actions_map = array(
			'import_slideshows' => 'action_ajax_import',
		);
	
		// Add ajax actions.
		foreach($plugin_ajax_actions_map as $action => $function){
			add_action("wp_ajax_" . $action, $function);
			add_action("wp_ajax_nopriv_" . $action, $function);
		}
	}
}
