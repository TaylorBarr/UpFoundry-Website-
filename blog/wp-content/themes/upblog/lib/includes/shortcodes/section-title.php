<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class STH_Section_Title {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'sth_section_title', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $title = $number = '';

        extract( shortcode_atts( array(
            'title'              => '',
            'number'              => '',
        ), $atts ) );


        $output .= '<div class="menu-item-title">';
        $output .= '<span class="number-of-item">'.sanitize_text_field($number).'</span>';
        $output .= '<h2>'.sanitize_text_field($title).'</h2>';
        $output .= '</div>';


        return $output;
    }

}

STH_Section_Title::get_instance();