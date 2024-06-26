<?php

namespace WPGraphQLGutenberg\Schema\Types\Scalar;

class Scalar {
	public function __construct() {
		add_action( get_graphql_register_action(), function () {
			register_graphql_scalar('BlockAttributesObject', [
				'serialize' => function ( $value ) {
					return wp_json_encode( $value );
				},
			]);

			register_graphql_scalar('BlockAttributesArray', [
				'serialize' => function ( $value ) {
					return wp_json_encode( $value );
				},
			]);
		});
	}

	public static function BlockAttributesObject() {
		return 'BlockAttributesObject';
	}

	public static function BlockAttributesArray() {
		return 'BlockAttributesArray';
	}
}
