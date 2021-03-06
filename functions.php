<?php

/*
 * Definition of needed Constants
 */
if(!defined('WP_THEME_URL')) {
	define( 'WP_THEME_URL', get_bloginfo('stylesheet_directory'));
}
if(!defined('WP_JS_URL')) {
	define( 'WP_JS_URL' , get_bloginfo('template_url').'/js');
}
if(!defined('LANG_NAMESPACE')){
	define( 'LANG_NAMESPACE', "bp");
}

/*
 * Include needed files
 */
require_once( dirname( __FILE__ ) . "/sources/theme_functions.php" ); 	// L//H Theme Functions
require_once( dirname( __FILE__ ) . "/less/lib/less.inc.php");			// The Less compiler
require_once( dirname( __FILE__ ) . "/less/lib/less.php" ); 			// The Less controller
require_once( dirname( __FILE__ ) . "/sources/meta_boxes.php" );		// Theme Meta Boxes
require_once( dirname( __FILE__ ) . "/sources/theme_update.php" );		// Update the Theme via Github
require_once( dirname( __FILE__ ) . "/sources/lh.opengraph.php" );		// Open Graph Function
require_once( dirname( __FILE__ ) . "/sources/lh.facebook.php" );		// Facebook Functions
require_once( dirname( __FILE__ ) . "/sources/Mobile_Detect.php" );		// Mobile Detect Class


/*
 * Definition and compilation of needed less files
 */
$css_file = dirname(__FILE__).'/style_less.css';
$less_file = dirname(__FILE__).'/less/style.less';
autoCompileLess($less_file,$css_file);

$css_file = dirname(__FILE__).'/admin/admin.css';
$less_file = dirname(__FILE__).'/admin/less/admin.less';
autoCompileLess($less_file,$css_file);


function init_bookpress(){
	global $post;

	if(is_singular()){
		$fb_settings = get_post_meta($post->ID, "_fb_settings", true);
		if(is_array($fb_settings) && $fb_settings['appid'] != ""){
			define( 'FB_APP_ID', $fb_settings['appid']);
			define( 'FB_APP_SECRET', $fb_settings['appsecret']);
		}
	}

	if(!defined('FB_APP_ID')){
		define( 'FB_APP_ID', '414125188601439');
	}

	if(!defined('FB_APP_SECRET')){
		define( 'FB_APP_SECRET', 'false');
	}

}
add_action("wp", "init_bookpress", 9999);

global $_bp_mobile_detect;
$_bp_mobile_detect = new Mobile_Detect;

/**
 * Enqueue the needed scripts and styles in the frontend
 * Called by action "wp_enqueue_scripts"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @return void
 */
function lh_enqueue_scripts(){
	// CSS
	wp_enqueue_style('style', WP_THEME_URL.'/style_less.css', NULL, '2.0', 'all');

	// JS Libaries
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"), false, '1.9.1', true);
	wp_enqueue_script('jquery');

	// Own Script Files
	wp_enqueue_script("main", WP_JS_URL.'/main.js', array("jquery"), 1, true);
}
add_action("wp_enqueue_scripts", "lh_enqueue_scripts");


/**
 * Enqueue the needed scripts and styles in the backend
 * Called by action "admin_enqueue_scripts"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @return void
 */
function lh_enqueue_scripts_admin(){
	wp_enqueue_script("wp2fb", WP_JS_URL.'/admin.js', array("jquery"), 1, true);

	wp_enqueue_script("codemirror", WP_JS_URL.'/codemirror/codemirror.js', NULL, 1, true);

	wp_enqueue_script("cm-less", WP_JS_URL.'/codemirror/mode/less.js', NULL, 1, true);

	wp_enqueue_script("matchingbrackets", WP_JS_URL.'/codemirror/addon/edit/matchbrackets.js', NULL, 1, true);


	wp_enqueue_style('wp2fb', WP_THEME_URL.'/admin/admin.css', NULL, '2.0', 'all');
}
add_action("admin_enqueue_scripts", "lh_enqueue_scripts_admin");


/**
 * Setup the images and image sizes needed in this theme
 * Called by action "after_setup_theme"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @return void
 */
function setup_images(){
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 810, 330, true );
}
add_action("after_setup_theme", "setup_images");

/*
 * Clean up the header
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);


/**
 * Add language support
 * Called by action "after_setup_theme"
 *
 * @author Hendrik Luehrsen
 * @since 1.0
 *
 * @return void
 */
function lh_load_theme_textdomain(){
    load_theme_textdomain(LANG_NAMESPACE, get_template_directory() . '/lang');
}
add_action('after_setup_theme', 'lh_load_theme_textdomain');
