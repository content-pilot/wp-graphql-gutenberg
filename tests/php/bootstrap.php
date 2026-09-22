<?php
/**
 * PHPUnit bootstrap file.
 *
 * Loads the Composer autoloader so the plugin classes and their dependencies
 * (voku/simple_html_dom, opis/json-schema) are available. The unit suite
 * covers code paths that are pure functions of their inputs and never touch
 * WordPress, so no WP test framework or stubs are loaded here.
 */

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';
