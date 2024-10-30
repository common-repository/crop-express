<?php

namespace CROPEXPRESS;

use CROPEXPRESS\Util;

class Admin extends Singleton {

	const ADMIN_SLUG = 'crop-express-settings';
	const OPTION_NAME_SETTINGS = 'crop-express-settings';
	const ADMIN_SCREENS = [ 'block', 'featured-image', 'media-library', 'site-wide' ];

	protected function setup() {
		add_action( 'wp_ajax_crop_express_feedback', [ $this, 'send_feedback_email' ] );

		add_action( 'admin_menu', [ $this, 'update_media_add_new_url' ], 100 );

		add_action( 'admin_menu', [ $this, 'my_plugin_settings_menu' ] );

		add_action( 'admin_init', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'admin_init', [ $this, 'save_presets' ] );
		add_action( 'admin_init', [ $this, 'save_sizes' ] );
		add_action( 'admin_init', [ $this, 'setup_settings' ], 0 );
	}

	public function setup_settings() {
		$settings = get_option( self::OPTION_NAME_SETTINGS, [] );

		$default_presets = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/presets.php';
		$default_sizes   = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/save-image-defaults.php';

		foreach ( self::ADMIN_SCREENS as $screen ) {
			if ( ! isset( $settings[ $screen ]['presets'] ) ) {
				$settings[ $screen ]['presets'] = $default_presets;
			}

			if ( ! isset( $settings[ $screen ]['sizes'] ) ) {
				$settings[ $screen ]['sizes'] = $default_sizes;
			}
		}

		update_option( self::OPTION_NAME_SETTINGS, $settings, false );
	}

	public function admin_enqueue_scripts() {

		if ( ! empty( $_GET['page'] ) && self::ADMIN_SLUG === $_GET['page'] ) {
			wp_enqueue_style(
				'flexboxgrid',
				Cropexpress()->plugin_data()['plugin_dir_url'] . 'settings/css/index.css',
				[],
				Cropexpress()->plugin_data()['Version']
			);
		}
	}

	public function my_plugin_settings_menu() {
		add_options_page(
			'crop.express',
			'crop.express',
			'manage_options',
			self::ADMIN_SLUG,
			[ $this, 'render_settings_page' ]
		);
	}

	public function render_settings_page() {
		$screen = $this->get_screen();

		$default_presets = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/presets.php';
		$default_sizes   = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/save-image-defaults.php';

		$settings = get_option( self::OPTION_NAME_SETTINGS, [] );

		$presets = $settings[ $screen ]['presets'];
		$sizes   = $settings[ $screen ]['sizes'];

		include sprintf( '%ssettings/html/settings.php', Cropexpress()->plugin_data()['plugin_dir_path'] );
	}

	public function save_sizes() {
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || ! wp_verify_nonce( $_POST['_wpnonce'], Cropexpress()->plugin_data()['prefix'] . '-sizes-update' ) ) {
			return false;
		}

		$screen = $this->get_screen();

		$default_sizes = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/save-image-defaults.php';

		foreach ( array_keys( $default_sizes ) as $default_size_label ) {
			if ( ! empty( $_POST[ $default_size_label ] ) ) {
				$default_sizes[ $default_size_label ] = sanitize_text_field( $_POST[ $default_size_label ] );
			}
		}

		$settings                     = get_option( self::OPTION_NAME_SETTINGS, [] );
		$settings[ $screen ]['sizes'] = $default_sizes;

		update_option( self::OPTION_NAME_SETTINGS, $settings, false );

		wp_redirect(
			$_POST['_wp_http_referer']
		);
		exit;
	}

	public function save_presets() {
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || ! wp_verify_nonce( $_POST['_wpnonce'], Cropexpress()->plugin_data()['prefix'] . '-presets-update' ) ) {
			return false;
		}

		$screen = $this->get_screen();

		$default_presets = include Cropexpress()->plugin_data()['plugin_dir_path'] . 'data/presets.php';

		$presets = [];

		foreach ( $default_presets as $default_preset_id => $default_preset ) {
			if ( in_array( $default_preset_id, $_POST['default_presets'] ) ) {
				$presets[ $default_preset_id ] = $default_preset;
			}
		}

		if ( ! empty( $_POST['custom_presets'] ) ) {
			foreach ( $_POST['custom_presets'] as $custom_preset_id => $custom_preset ) {
				$custom_preset['aspect_ratio'] = floatval( $custom_preset['numerator'] ) / floatval( $custom_preset['denominator'] );
				$presets[ $custom_preset_id ]  = $custom_preset;
			}
		}

		if ( ! empty( $_POST['preset']['label'] )
		     && ! empty( $_POST['preset']['numerator'] )
		     && ! empty( $_POST['preset']['denominator'] ) ) {

			$id = sanitize_title( $_POST['preset']['label'] ) . uniqid();

			$preset = [
				'group'        => 'custom',
				'id'           => $id,
				'label'        => sanitize_text_field( $_POST['preset']['label'] ),
				'numerator'    => floatval( $_POST['preset']['numerator'] ),
				'denominator'  => floatval( $_POST['preset']['denominator'] ),
				'aspect_ratio' => floatval( $_POST['preset']['numerator'] ) / floatval( $_POST['preset']['denominator'] ),
			];

			$presets[ $preset['id'] ] = $preset;
		}

		$settings                       = get_option( self::OPTION_NAME_SETTINGS, [] );
		$settings[ $screen ]['presets'] = $presets;

		update_option( self::OPTION_NAME_SETTINGS, $settings, false );

		wp_redirect(
			$_POST['_wp_http_referer']
		);
		exit;
	}

	public function update_media_add_new_url() {
		global $submenu;

		foreach ( $submenu['upload.php'] as &$submenu ) {
			if ( 'media-new.php' !== $submenu[2] ) {
				continue;
			}

			$submenu[2] = 'upload.php?crop-express';
		}
	}

	public function send_feedback_email() {
		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || ! wp_verify_nonce( $_POST['crop_express'], Cropexpress()->plugin_data()['prefix'] . '-feedback' ) ) {
			return false;
		}

		if ( ! empty( $_POST['email'] ) ) {
			$email = sanitize_email( $_POST['email'] );
		} else {
			$current_user = wp_get_current_user();
			$email        = $current_user->user_email;
		}

		wp_mail(
			'support@crop.express',
			sprintf(
				'Crop.express feedback: %s',
				! empty( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : ''
			),
			sprintf(
				"%s\n\n%s",
				sanitize_textarea_field( $_POST['message'] ),
				site_url()
			),
			[
				sprintf( 'From: %1$s<%1$s>', $email )
			]
		);

		wp_send_json_success();
		exit;
	}

	public function get_screen() {
		if ( ! empty( $_GET['screen'] ) || in_array( $_GET['screen'], self::ADMIN_SCREENS ) ) {
			return $_GET['screen'];
		} else {
			return 'welcome';
		}
	}
}
