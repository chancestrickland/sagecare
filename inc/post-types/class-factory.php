<?php

namespace Chancelight\Sagecare\Post_Types;

class Factory {
	/**
	 * Factory method to build post type from its respective class.
	 *
	 * @param $type string Classname of the post type.
	 * @param $slug string Post type slug.
	 */
	public static function build( $type, $slug ) {
		$type  = ucwords( str_replace( [ '-', '_' ], ' ', $type ) );
		$class = __NAMESPACE__ . '\\Types\\' . str_replace( ' ', '_', $type );
		if ( class_exists( $class ) && method_exists( $class, 'register' ) ) {
			$instance = new $class( $slug );
			$instance->register();
		}
	}
}
