<?php

namespace WPGraphQLGutenberg\Schema\Types\Connection\Blocks;

use WPGraphQL\Model\Post;

class CoreImageBlockToMediaItemConnection {
	public function __construct() {
		add_action('graphql_register_types', function () {
			register_graphql_connection([
				'fromType'           => 'CoreImageBlock',
				'toType'             => 'MediaItem',
				'fromFieldName'      => 'mediaItem',
				'oneToOne'           => true,
				'connectionTypeName' => 'CoreImageBlockToMediaItemConnection',
				'resolve'            => function ( $source, $args, $context ) {
					$queried_attachment = get_post( $source->attributes['id'] );
					if ( is_wp_error( $queried_attachment ) ) {
						return false;
					}
					$graphql_post = new Post( $queried_attachment );
					$resolver     = $context->get_loader( 'post' )->load_deferred( $queried_attachment->ID );
					$connection   = $resolver->one_to_one()->get_connection();
					return $connection;
				},
			]);
		});
	}
}
