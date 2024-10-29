<?php
/**
 * affiliates-generator.php
 *
 * Copyright (c) 2011,2012 Antonio Blanco http://www.blancoleon.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Antonio Blanco	
 * @package affiliates-generator
 * @since affiliates-generator 1.0.0
 *
 * Plugin Name: Affiliates Generator
 * Plugin URI: http://www.eggemplo.com/plugins/affiliates-generator
 * Description: Affiliates Generator tool.
 * Version: 1.0
 * Author: eggemplo
 * Author URI: http://www.eggemplo.com
 * License: GPLv3
 */

define( 'AFFGENERATOR_DOMAIN', 'affgenerator' );

define( 'AFFGENERATOR_FILE', __FILE__ );

if ( !defined( 'AFFGENERATOR_CORE_DIR' ) ) {
	define( 'AFFGENERATOR_CORE_DIR', WP_PLUGIN_DIR . '/affiliates-generator' );
}

define( 'AFFGENERATOR_PLUGIN_URL', plugin_dir_url( AFFGENERATOR_FILE ) );

include_once 'class-affiliates-generator.php';

class AffiliatesGeneratorPlugin {
	
	private static $notices = array();
	
	public static function init() {
			
		load_plugin_textdomain( AFFGENERATOR_DOMAIN, null, 'affiliates-generator/languages' );
		
		register_activation_hook( AFFGENERATOR_FILE, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( AFFGENERATOR_FILE, array( __CLASS__, 'deactivate' ) );
		
		register_uninstall_hook( AFFGENERATOR_FILE, array( __CLASS__, 'uninstall' ) );
		
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}
	
	public static function wp_init() {
		if ( !defined ( 'AFFILIATES_PLUGIN_DOMAIN' ) )  {
			self::$notices[] = "<div class='error'>" . __( '<strong>Affiliates Generator</strong> plugin requires <a href="http://www.itthinx.com/plugins/affiliates/?affiliates=51" target="_blank">Affiliates</a>.', AFFGENERATOR_DOMAIN ) . "</div>";
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( array( __FILE__ ) );
		} else {
			wp_register_style( 'affgenerator', AFFGENERATOR_PLUGIN_URL . 'css/generator.css');
				
			AffiliatesGenerator::init(); // add the custom method

		}
		
	}
	
	public static function admin_notices() { 
		if ( !empty( self::$notices ) ) {
			foreach ( self::$notices as $notice ) {
				echo $notice;
			}
		}
	}
	
	/**
	 * Plugin activation work.
	 * 
	 */
	public static function activate() {
				
	}
	
	/**
	 * Plugin deactivation.
	 *
	 */
	public static function deactivate() {
		
	}

	/**
	 * Plugin uninstall.
	 *
	 */
	public static function uninstall() {
	
	}
	
	
}
AffiliatesGeneratorPlugin::init();

