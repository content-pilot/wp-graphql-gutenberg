<?php

namespace WPGraphQLGutenberg\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use voku\helper\HtmlDomParser;
use WPGraphQLGutenberg\Blocks\Block;

/**
 * Tests for Block::source_attributes, the parser that reads block attributes
 * out of a block's saved HTML according to the block type's `source` config.
 *
 * Gutenberg sources `attribute` values from the DOM (hpq `attr()`), which
 * returns them decoded — `href="...?a=1&amp;b=2"` yields `&`. The HTML parser
 * we use preserves entities instead, so the plugin decodes them itself to
 * match the editor. `html` / `rich-text` sources are HTML by design and stay
 * encoded.
 */
class SourceAttributesTest extends TestCase {

	/**
	 * Expose the protected sourcing method under test.
	 *
	 * @param string $html Block inner HTML.
	 * @param array  $type Attribute definitions (the block type's `attributes`).
	 *
	 * @return array
	 */
	private static function source( string $html, array $type ): array {
		$block = new class() extends Block {
			public function __construct() {} // phpcs:ignore Squiz.Commenting.FunctionComment.Missing

			public static function run( $node, $type ) {
				return self::source_attributes( $node, $type );
			}
		};

		return $block::run( HtmlDomParser::str_get_html( $html ), $type );
	}

	// ---------------------------------------------------------------------
	// `attribute` source — decoded, like the block editor
	// ---------------------------------------------------------------------

	/**
	 * @return array<string, array{string, string, string}>
	 */
	public static function attribute_source_provider(): array {
		$href = [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'a', 'attribute' => 'href' ];
		$src  = [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'img', 'attribute' => 'src' ];
		$alt  = [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'img', 'attribute' => 'alt' ];

		return [
			'query string with &amp; (core/button)' => [
				'<div class="wp-block-button"><a class="wp-block-button__link" href="https://example.com/jobs.aspx?FilterREID=2&amp;FilterJobCategoryID=3">All Open Positions</a></div>',
				'href',
				'https://example.com/jobs.aspx?FilterREID=2&FilterJobCategoryID=3',
			],
			'numeric entity &#038; (core/image src)' => [
				'<figure class="wp-block-image"><img src="https://example.com/a.jpg?w=1&#038;h=2" alt=""/></figure>',
				'src',
				'https://example.com/a.jpg?w=1&h=2',
			],
			'quotes and ampersand in alt' => [
				'<figure class="wp-block-image"><img src="https://example.com/a.jpg" alt="Say &quot;hi&quot; to Smith &amp; Jones"/></figure>',
				'alt',
				'Say "hi" to Smith & Jones',
			],
			'markup entities decode too (consumers escape on output)' => [
				'<figure class="wp-block-image"><img src="https://example.com/a.jpg" alt="5 &lt; 6"/></figure>',
				'alt',
				'5 < 6',
			],
			'no entities: unchanged' => [
				'<div class="wp-block-button"><a class="wp-block-button__link" href="https://example.com/plain/?a=1">Plain</a></div>',
				'href',
				'https://example.com/plain/?a=1',
			],
		];
	}

	#[DataProvider( 'attribute_source_provider' )]
	public function test_attribute_source_is_decoded( string $html, string $attribute, string $expected ): void {
		$selector = 'href' === $attribute ? 'a' : 'img';
		$type     = [
			'value' => [ 'type' => 'string', 'source' => 'attribute', 'selector' => $selector, 'attribute' => $attribute ],
		];

		$this->assertSame( $expected, self::source( $html, $type )['value'] );
	}

	public function test_missing_attribute_falls_back_to_default(): void {
		$type = [
			'alt' => [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'img', 'attribute' => 'alt', 'default' => '' ],
			'title' => [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'img', 'attribute' => 'title' ],
		];

		$result = self::source( '<figure class="wp-block-image"><img src="https://example.com/a.jpg"/></figure>', $type );

		$this->assertSame( '', $result['alt'] );
		$this->assertSame( '', $result['title'] );
	}

	// ---------------------------------------------------------------------
	// `rich-text` / `html` sources — HTML by design, stay encoded
	// ---------------------------------------------------------------------

	public function test_rich_text_source_keeps_entities(): void {
		$type = [
			'text'    => [ 'type' => 'rich-text', 'source' => 'rich-text', 'selector' => 'a' ],
			'url'     => [ 'type' => 'string', 'source' => 'attribute', 'selector' => 'a', 'attribute' => 'href' ],
		];

		$result = self::source(
			'<div class="wp-block-button"><a class="wp-block-button__link" href="https://example.com/?a=1&amp;b=2">Smith &amp; Jones</a></div>',
			$type
		);

		$this->assertSame( 'Smith &amp; Jones', $result['text'], 'rich-text stays HTML' );
		$this->assertSame( 'https://example.com/?a=1&b=2', $result['url'], 'attribute is decoded' );
	}

	public function test_html_source_keeps_entities(): void {
		$type = [
			'caption' => [ 'type' => 'string', 'source' => 'html', 'selector' => 'figcaption' ],
		];

		$result = self::source( '<figure class="wp-block-image"><img src="https://example.com/a.jpg"/><figcaption>Smith &amp; Jones</figcaption></figure>', $type );

		$this->assertSame( 'Smith &amp; Jones', $result['caption'] );
	}
}
