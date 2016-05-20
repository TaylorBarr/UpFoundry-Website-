<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


/************************************************************/
/* Define Theme's Constants */
/************************************************************/

if ( !defined( 'THEMEROOT' ) ) {
    define('THEMEROOT', get_stylesheet_directory_uri());
}

if ( !defined( 'THEMEDIR' ) ) {
    define('THEMEDIR', get_stylesheet_directory());
}

if ( !defined( 'IMAGES' ) ) {
    define('IMAGES', THEMEROOT . '/assets/img');
}

if ( !defined( 'LANGUAGE_ZONE' ) ) {
    define( 'LANGUAGE_ZONE', 'imbt' );
}

if ( !defined( 'LANGUAGE_ZONE_ADMIN' ) ) {
    define( 'LANGUAGE_ZONE_ADMIN', 'imbt' );
}


/************************************************************/
/* Theme Setup Function */
/************************************************************/
add_action( 'after_setup_theme', 'imbt_theme_setup' );
function imbt_theme_setup() {

    // Load textdomain for translation
    load_theme_textdomain( 'imbt', get_template_directory() . '/lang' );

    // Set a max width for the uploaded images.
    if (!isset($content_width)) $content_width = 2000;

    if (function_exists('add_theme_support')) {
        add_theme_support('post-formats', array('video','audio','image','gallery'));

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(500, 9999);

        // Adds RSS feed links to <head> for posts and comments
        add_theme_support('automatic-feed-links');

        // This theme styles the visual editor with editor-style.css to match the theme style
        add_editor_style();
    }

    if ( function_exists( 'add_image_size' ) ) {

        add_image_size('imbt_blog', 750, 750, true);

        add_image_size('imbt_avatar', 150, 150, true);

        add_image_size('blog_image', 800, 9999);

        add_image_size('book_cover', 200, 9999);

    }

    // Set widgets to accept shortcodes
    add_filter('widget_text', 'do_shortcode');

    // This removes the admin bar from the front-end
    //add_filter('show_admin_bar', '__return_false');

    // Require the CORE class of the theme
    require_once('lib/classes/imbt-core.php');
}