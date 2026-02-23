<?php
/**
 * FAIR Connect Beta Tester Plugin
 *
 * @package FAIR\Beta_Tester
 */

namespace FAIR\Beta_Tester;

use const FAIR\PLUGIN_FILE;
use Fragen\Git_Updater;

/**
 * Bootstrap the plugin.
 */
function bootstrap(): void {
	add_action( 'plugins_loaded', __NAMESPACE__ . '\run' );
	add_filter( 'git_updater_lite_api_url', __NAMESPACE__ . '\add_development_channel_support', 10, 2 );
	add_filter( 'git_updater_lite_transient_timeout', __NAMESPACE__ . '\shorten_transient_timeout', 10, 2 );
}

/**
 * Set-up Git Updater Lite with FAIR Connect.
 *
 * @return void
 */
function run(): void {
	( new Git_Updater\Lite( PLUGIN_FILE ) )->run();
}

/**
 * Add development channel support.
 *
 * @param  string $url Git Updater API URL.
 * @param  string $slug Plugin slug.
 *
 * @return string
 */
function add_development_channel_support( $url, $slug ): string {
	if ( 'fair-plugin' === $slug ) {
		$url = add_query_arg( [ 'channel' => 'development' ], $url );
	}
	return $url;
}

/**
 * Shorten transient timeout for fair-plugin.
 *
 * This allows for more frequent checks during development
 * to bust the FAIR cache and ensure updates are detected in a timely manner.
 *
 * @param  int    $timeout Timeout in seconds.
 * @param  string $slug Plugin slug.
 *
 * @return int
 */
function shorten_transient_timeout( $timeout, $slug ): int {
	if ( 'fair-plugin' === $slug ) {
		$timeout = 5 * MINUTE_IN_SECONDS;
	}
	return $timeout;
}
