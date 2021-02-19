<?php
/*
Plugin Name: Custom API Endpoint
Description: Blueshift plugin to manage different aspects of the connection between the website and Blueshift.
Plugin URI:  https://yupscode.com/custom-api-endpoint
Author:      YupsCode Team
Version:     0.3.25
License:     GPLv3 or later

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/
*/

/*
* Disable direct file access
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Define global variables
*/
define('YC_PREFIX', '_yc_cae_');
define('VERSION', '0.1');

/*
* Load text domain
*/
function yc_cae_load_textdomain() {
	load_plugin_textdomain( 'CustomApiEndpoint', false, plugin_dir_path( __FILE__ ) . 'languages/' );
}
add_action( 'plugins_loaded', YC_PREFIX . 'load_textdomain' );

class customApiEndpoint {
	/**
	 * @var object
	 */
	protected $core; 

	/**
	 * Object Constructor
	 **/
	public function __construct() {
		$js = plugin_dir_url( __FILE__ ) .'admin/js/admin-script.js';
		$css = plugin_dir_url( __FILE__ ) .'admin/css/style.css';

		register_activation_hook( __FILE__, array($this, 'activation' ) );
		register_deactivation_hook( __FILE__, array($this, 'de_activation' ) );
		register_uninstall_hook( __FILE__, array($this, 'delete_plugin' ) );

		add_action( 'admin_menu', array( $this, 'initialize_menu') );
	
		wp_enqueue_script( YC_PREFIX . 'jquery', '//code.jquery.com/jquery-3.5.1.min.js', array(), VERSION, false);

		wp_register_script( YC_PREFIX . 'popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array(), VERSION, true);
		wp_enqueue_script( YC_PREFIX . 'popper' );
		wp_register_script( YC_PREFIX . 'bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array(), VERSION, true);
		wp_enqueue_script( YC_PREFIX . 'bootstrap' );

		wp_enqueue_script( YC_PREFIX . 'admin_js', $js, array(), null, false );
		wp_enqueue_style( YC_PREFIX . 'admin_css', $css, array(), null, false );

		// Views
		require_once plugin_dir_path( __FILE__ ) . 'views/view-dashboard.php';
		include_once plugin_dir_path( __FILE__ ) . 'views/view-framework.php';

		// Classes
		require_once plugin_dir_path( __FILE__ ) . 'classes/class-api-handler.php';
		require_once plugin_dir_path( __FILE__ ) . 'classes/class-page-handler.php';
		
	}

	/**
	 * Activation Function
	 *
	 * @param void
	 * @return void
	 * 
	 **/
	public function activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		$option = get_option(YC_PREFIX . 'api_endpoint_config');

		if( $option == NULL ) {
			$api = new restApiEndpoint();
			$api->set_config('');
		}
	}

	/**
	 * Deactivation Function
	 *
	 * @param void
	 * @return void
	 * 
	 **/
	public function de_activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;

		flush_rewrite_rules();
	}
	
	/**
	 * Deleting Function
	 *
	 * @param void
	 * @return void
	 * 
	 **/
	public function delete_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;
    
    	delete_option(YC_PREFIX . 'api_endpoint_config');
	}

	/**
	 * Function to add menu item(s) to the Admin menu
	 *
	 * @param void
	 * @return void
	 */
	public function initialize_menu(){
		/*
		* Main page
		*/
		add_menu_page(
			esc_html__('Dashboard', 'custom_api_endpoint'),
			esc_html__('Custom API Endpoint', 'custom_api_endpoint'),
			'manage_options',
			YC_PREFIX . 'dashboard',
			'yc_cae_display_settings_page',
			'dashicons-rest-api',
			null
		);

		/*
		* Endpoints sub page
		*/
		add_submenu_page(
			esc_html__('Custom API Endpoint', 'custom_api_endpoint'),
			esc_html__('Endpoints', 'Endpoints'),
			esc_html__('Endpoints', 'Endpoints'),
			'manage_options',
			YC_PREFIX . 'endpoints',
			'yc_cae_display_settings_page'
		);
	}

	/*
	 *  Handler for Admin pages
	 *
	 * @param void
	 * @return void
	 */
	public function admin_page(){
		if (!current_user_can('manage_options')) {  
			wp_die('You do not have sufficient permissions to access this page.');  
		}

		$content = array(
				'menuItems' => apply_filters( YC_PREFIX . 'admin_menu', array() ),
				'plugin_admin_page' => $this->core->plugin_admin_page,
				'config' => $this->core->config,
				'config_name' => $this->core->config_name
			);
	}
}

$cae = new customApiEndpoint();