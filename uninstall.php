<?php
/**
 * Pop-Up PRO CC - Time
 *
 * @package   ChChPopUpScrollProTime
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

if ( is_multisite() ) {

	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	delete_option( 'chch_pusf_license_key');
	delete_option( 'chch_pusf_license_status');	 
	if ( $blogs ) {

	 	foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			delete_option( 'chch_pusf_license_key');
			delete_option( 'chch_pusf_license_status');
			restore_current_blog();
		}
	}

} else {
	delete_option( 'chch_pusf_license_key');
	delete_option( 'chch_pusf_license_status');
}