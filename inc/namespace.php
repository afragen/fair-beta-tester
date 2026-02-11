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
	add_filter( 'git_updater_lite_api_data', __NAMESPACE__ . '\update_download_link', 10, 3 );
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
 * Update download link with current development channel link.
 *
 * @param  object $api_data API data object.
 * @param  string $slug Plugin slug.
 * @param  string $url Git Updater API URL.
 *
 * @return object Updated API data object.
 */
function update_download_link( $api_data, $slug, $url ) : object {
	$parsed_url = parse_url( $url, PHP_URL_QUERY );
	if ( 'fair-plugin' === $slug && str_contains( $parsed_url, 'channel=development' ) ) {
		$api_data->download_link = reset( $api_data->versions );
	}
	return $api_data;
}
