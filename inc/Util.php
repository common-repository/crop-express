<?php

namespace CROPEXPRESS;

class Util extends Singleton {

	protected function setup() {
	}

	public static function is_local() {
		if ( isset( $_COOKIE['is-local'] ) ) {
			return (bool) $_COOKIE['is-local'];
		}

		return in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) );
	}

	/**
	 * @param $input
	 * @link https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
	 *
	 * @return string
	 */
	public static function camel_to_snake( $input ) {
		return strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $input ) );
	}

	/**
	 * @param $input
	 * @link https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
	 *
	 * @return string
	 */
	public static function snakeToCamel( $input ) {
		return lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', $input ) ) ) );
	}
}
