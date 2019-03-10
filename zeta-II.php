<?php
/**
 * Created by PhpStorm.
 * User: Mr OK
 * Date: 3/7/2019
 * Time: 23:14
 */

# Remove BackEnd WP Tags.
// remove junk from head
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'start_post_rel_link', 10 );
remove_action( 'wp_head', 'parent_post_rel_link', 10 );
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_generator' );
//Resource Hints is a smart feature added to WordPress version 4.6. I think it might improve your site speed.
// But if you want to disable it, try this:
remove_action( 'wp_head', 'wp_resource_hints', 2 );

// Reference:
//      wp_oembed_add_discovery_links
//      rest_output_link_wp_head
//      rest_output_link_header
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

add_filter( 'the_generator', '__return_empty_string' );

// remove version from scripts and styles
function shapeSpace_remove_version_scripts_styles( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}

##################################################################################
function red_c_page()
{
	wp_redirect(site_url() . '/login');
	exit;
}

add_action('wp_logout', "red_c_page");
add_action('wp_login_failed', "red_c_page");

function red_wp_admin()
{
	global $pagenow;
	if ($pagenow === "wp-login.php") {
		wp_redirect(site_url() . '/login');
	}

}

add_action('init', 'red_wp_admin');
##################################################################################
add_filter( 'style_loader_src', 'shapeSpace_remove_version_scripts_styles', 9999 );
add_filter( 'script_loader_src', 'shapeSpace_remove_version_scripts_styles', 9999 );
new AuthorConfigsNiceName();