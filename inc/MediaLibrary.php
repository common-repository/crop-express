<?php

namespace CROPEXPRESS;

use CROPEXPRESS\Scripts;
use CROPEXPRESS\Util;

class MediaLibrary extends Singleton {

	protected function setup() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'upload.php' !== $hook ) {
			return false;
		}

		if ( ! Util::is_local() ) {
			$scripts = Scripts::instance()->get_asset_urls( 'media-library', 'js' );
		} else {
			$scripts = [
				[
					'url' => 'https://localhost:3000/static/js/bundle.js',

				]
			];
		}

		Scripts::instance()->enqueue_scripts( $scripts );

		$settings = get_option( Admin::OPTION_NAME_SETTINGS, [] );
		$presets  = array_values( $settings['media-library']['presets'] );

		$save_image_settings = $settings['media-library']['sizes'];

		$save_image_settings_keys   = array_map( '\CROPEXPRESS\Util::snakeToCamel', array_keys($save_image_settings) );
		$save_image_settings = array_combine($save_image_settings_keys, array_values($save_image_settings));

		$to_localize = [
			'presets'             => $presets,
			'save_image_settings' => $save_image_settings,
			'nonce_feedback'      => wp_create_nonce( Cropexpress()->plugin_data()['prefix'] . '-feedback' ),
			'_wpnonce'            => wp_create_nonce( 'media-form' ),
			'admin_ajax'          => admin_url( 'admin-ajax.php' ),
			'admin_upload'        => admin_url( 'async-upload.php' ),
		];

		Scripts::instance()->localize_script( $scripts, $to_localize );

//		$scripts [] = [
//			'name'    => 'mustache',
//			'url'     => Cropexpress()->plugin_data()['plugin_dir_url'] . 'media-library/node_modules/mustache/mustache.js',
//			'version' => "4.2.0"
//		];


//		wp_enqueue_script(
//			'ce-media-library',
//			Cropexpress()->plugin_data()['plugin_dir_url'] . 'media-library/js/media-library.js',
//			[ 'thickbox', 'mustache' ],
//			Cropexpress()->plugin_data()['Version'],
//			true
//		);

//		$to_localize = (object) [
////			'admin_ajax'     => admin_url( 'admin-ajax.php' ),
//			'templates' => [
//				'thickbox' => file_get_contents( Cropexpress()->plugin_data()['plugin_dir_path'] . 'media-library/html/thickbox.mustache' ),
//			],
////			'text_domain'    => SESocialImages()->plugin_data()['TextDomain'],
////			'namespace'      => SESocialImages()->plugin_data()['name_underscores'],
////			'wp_nonce_field' => wp_create_nonce( 'screenshot' ),
////			'post_id'        => $post->ID,
////			'permalink'      => get_permalink( $post->ID ),
////			'post_title'     => get_the_title( $post->ID ),
//		];

//		Scripts::instance()->localize_script($scripts, $to_localize);

//		wp_localize_script(
//			'ce-media-library',
//			Cropexpress()->plugin_data()['prefix'],
//			$to_localize
//		);
	}
}
