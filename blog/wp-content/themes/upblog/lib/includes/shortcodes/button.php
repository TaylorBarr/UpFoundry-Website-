<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Stylish_Button {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'stylish_button', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ) {
        $output = $type = $size = $text = $link = $target = '';

        extract( shortcode_atts( array(
            'type'             => '1',
            'size'             => 'normal',
            'text'             => '',
            'link'             => '#',
            'target'           => '_self',
        ), $atts ) );

        $output .= sprintf('<a href="%1$s" class="btn %2$s %3$s" target="%4$s">%5$s</a>', esc_url($link), esc_attr($type),
            esc_attr($size), esc_attr($target), esc_html($text));

        return $output;
    }

}

Stylish_Button::get_instance();