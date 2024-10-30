<?php

namespace CROPEXPRESS;

use CROPEXPRESS\Presets;
use CROPEXPRESS\Admin;
use CROPEXPRESS\Util;

class Block extends Singleton {

	public function register_block_type() {
		register_block_type( Cropexpress()->plugin_data()['plugin_dir_path'] . '/block/build' );
	}

	public function enqueue_assets() {
//		$asset_file = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'featured-image/build/index.asset.php';

		$scripts = [
			[
				'name' => 'ce-featured-image',
				'url'  => Cropexpress()->plugin_data()['plugin_dir_url'] . 'featured-image/build/index.js',
			]
		];

		Scripts::instance()->enqueue_scripts( $scripts );

		$settings               = get_option( Admin::OPTION_NAME_SETTINGS, [] );
		$featured_image_presets = array_values( $settings['featured-image']['presets'] );
		$block_presets          = array_values( $settings['block']['presets'] );

		$featured_image_save_image_settings      = $settings['featured-image']['sizes'];
		$featured_image_save_image_settings_keys = array_map( '\CROPEXPRESS\Util::snakeToCamel', array_keys( $featured_image_save_image_settings ) );
		$featured_image_save_image_settings      = array_combine( $featured_image_save_image_settings_keys, array_values( $featured_image_save_image_settings ) );

		$block_save_image_settings      = $settings['block']['sizes'];
		$block_save_image_settings_keys = array_map( '\CROPEXPRESS\Util::snakeToCamel', array_keys( $block_save_image_settings ) );
		$block_save_image_settings      = array_combine( $block_save_image_settings_keys, array_values( $block_save_image_settings ) );

		$to_localize = [
			'featured_image' => [
				'presets'             => $featured_image_presets,
				'save_image_settings' => $featured_image_save_image_settings,
			],
			'block'          => [
				'presets'             => $block_presets,
				'save_image_settings' => $block_save_image_settings,
			],
			'nonce_feedback' => wp_create_nonce( Cropexpress()->plugin_data()['prefix'] . '-feedback' ),
			'admin_ajax'     => admin_url( 'admin-ajax.php' ),
		];

		Scripts::instance()->localize_script( $scripts, $to_localize );
	}

	protected function setup() {
		add_action( 'init', [ $this, 'register_block_type' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_assets' ] );
	}

}