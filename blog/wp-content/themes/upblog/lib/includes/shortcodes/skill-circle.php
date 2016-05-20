<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class STH_Skill_Circle {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'sth_skill_circle', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $title = $icon = '';

        extract( shortcode_atts( array(
            'title'              => '',
            'icon'              => '',
        ), $atts ) );


        $output .= '<div class="bordered-item"><div class="skill-circle"><div class="centred-v-t"><div class="centred-v">';
        $output .= '<i class="fa '.esc_attr($icon).'"></i>';
        $output .= '<p>'.sanitize_text_field($title).'</p>';
        $output .= '</div></div></div></div>';


        return $output;
    }

}

STH_Skill_Circle::get_instance();