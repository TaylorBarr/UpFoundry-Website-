<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Stylish_RandomText {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'about_carousel', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $text = $color = '';

        extract( shortcode_atts( array(
            'text'              => '',
            'color'             => '#f6be52',
        ), $atts ) );

        $text = explode(',', $text);

        $output .= '<div class="skills-text-list">';
        foreach($text as $t) {
            $output .= '<span style="color: '.$color.';">'.$t.'</span>';
        }
        $output .= '</div>';

        return $output;
    }

}
Stylish_RandomText::get_instance();