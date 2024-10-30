<?php

namespace CROPEXPRESS;

class Scripts extends Singleton {

	protected function setup() {
	}

	public function enqueue_scripts( $scripts ) {
		foreach ( $scripts as $script ) {
			if ( empty( $script['name'] ) ) {
				$script['name'] = basename( $script['url'], '.js' );
			}

			$script['name'] = sprintf(
				'%s-%s',
				Cropexpress()->plugin_data()['prefix'],
				$script['name']
			);

			if ( empty( $script['version'] ) ) {
				$script['version'] = Cropexpress()->plugin_data()['Version'];
			}

			$script['dependencies'] = array_merge(
				['wp-i18n'],
				isset($script['dependencies']) ? $script['dependencies'] : []
			);

			wp_enqueue_script(
				$script['name'],
				$script['url'],
				$script['dependencies'],
				$script['version'],
				true
			);

//			wp_set_script_translations( $script['name'], 'crop-express' );
		}
	}

	public function enqueue_styles() {
		if ( ! Social_Link_Pages()->use_local() ) {
			$styles = Social_Link_Pages()->get_asset_urls( 'link-page',
				'css' );

			foreach ( $styles as $style ) {
				wp_enqueue_style(
					$style['name'],
					$style['url'],
					array(),
					$style['version']
				);
			}
		}
	}

	public function get_asset_urls( $app, $type = 'css' ) {

		$dir = new \DirectoryIterator( $this->get_asset_path( $app ) . $type );

		$scripts = array();
		foreach ( $dir as $file ) {
			if ( pathinfo( $file, PATHINFO_EXTENSION ) === $type ) {
				$fullName = basename( $file );

				$scripts[] = array(
					'name'    => $fullName,
					'url'     => sprintf(
						'%s%s/%s',
						$this->get_asset_url( $app ),
						$type,
						$fullName
					),
					'version' => Cropexpress()->plugin_data()['Version']
				);
			}
		}

		return $scripts;
	}

	public function get_asset_path( $app ) {
		return sprintf(
			'%s%s/build/static/',
			Cropexpress()->plugin_data()['plugin_dir_path'],
			$app
		);
	}

	public function get_asset_url( $app ) {
		return sprintf(
			'%s%s/build/static/',
			Cropexpress()->plugin_data()['plugin_dir_url'],
			$app
		);
	}

	public function localize_script( $scripts, $data ) {
		$script = reset( $scripts );

		if ( empty( $script['name'] ) ) {
			$script['name'] = basename( $script['url'], '.js' );
		}

		$script['name'] = sprintf(
			'%s-%s',
			Cropexpress()->plugin_data()['prefix'],
			$script['name']
		);

		$handle = $script['name'];

		wp_localize_script(
			$handle,
			Cropexpress()->plugin_data()['prefix'],
			$data
		);
	}
}
