<?php
/**
Author URI:        https://crop.express/
Author:            Crop.Express
Contributors:      gelform
Description:       Make cropping Featured Images in WordPress easier
Domain Path:       /languages
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
License:           GPL v2 or later
Plugin Name:       Crop.Express
Plugin URI:        https://crop.express/
Release Date:      March 25, 2023
Requires PHP:      5.3
Requires at least: 4.0
Stable tag:        0.0.4
Tags:              image crop, image editing, featured image
Tested up to:      6.1.1
Text Domain:       crop-express
Version:           0.0.4
 */

require __DIR__ . '/vendor/autoload.php';

use CROPEXPRESS\Admin;
use CROPEXPRESS\Block;
use CROPEXPRESS\MediaLibrary;
use CROPEXPRESS\Singleton;

class Cropexpress extends Singleton {

	private $plugin_data;

	public function setup() {
		Block::instance();
		Admin::instance();
		MediaLibrary::instance();
	}

	public function plugin_data() {
		if ( empty( $this->plugin_data ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$this->plugin_data                    = get_plugin_data( __FILE__ );
			$this->plugin_data['plugin_dir_url']  = plugin_dir_url( __FILE__ );
			$this->plugin_data['plugin_dir_path'] = plugin_dir_path( __FILE__ );

			$this->plugin_data['slug']   = Cropexpress()->plugin_data()['TextDomain'];
			$this->plugin_data['prefix'] = str_replace( '-', '_', Cropexpress()->plugin_data()['TextDomain'] );
		}

		return $this->plugin_data;
	}
}

function Cropexpress() {
	return Cropexpress::instance();
}

Cropexpress();
