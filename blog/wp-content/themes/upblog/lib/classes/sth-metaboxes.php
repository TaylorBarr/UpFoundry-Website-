<?php
/**
 * @author Stylish Themes
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


class STH_Meta_Boxes {

    protected static $instance = null;

    public $prefix = 'sth_';

    private function __construct() {

        add_filter('rwmb_meta_boxes', array( &$this, 'sth_register_meta_boxes'));

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', array( &$this, 'sth_meta_admin_init' ));
        }

    }

    /**
     * @param $meta_boxes
     * @return array
     */
    public function sth_register_meta_boxes( $meta_boxes ) {
        $prefix = $this->prefix;


        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_read_info.php');
        $meta_boxes[] = sth_read_info();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_blog_page.php');
        $meta_boxes[] = sth_blog_page_cover();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_cover_avatar.php');
        $meta_boxes[] = sth_cover_avatar();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_cover_colors.php');
        $meta_boxes[] = sth_cover_colors();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_footer_options.php');
        $meta_boxes[] = sth_footer_options();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_contact_info.php');
        $meta_boxes[] = sth_contact_info();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_video_album.php');
        $meta_boxes[] = sth_video_album();

        require_once(get_stylesheet_directory() . '/lib/includes/metaboxes/sth_article_header.php');
        $meta_boxes[] = sth_article_header();

        return $meta_boxes;
    }

    /**
     * This function adds custom CSS and JS for the metaboxes
     */
    public function sth_meta_admin_init() {
        global $pagenow;

        if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {

            wp_enqueue_script('sth_meta_js', THEMEROOT .'/lib/assets/js/sth-meta-js.js', array('jquery'));

            //wp_enqueue_style( 'sth_meta_css', THEMEROOT .'/assets/css/clx-meta-css.css');

        }
    }

    /**
     * @return null|STH_Meta_Boxes
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
STH_Meta_Boxes::get_instance();