<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Stylish_Image_Content {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'stylish_image_content', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts, $content = '' ){
        $output = $image_id = $background_attachment = '';

        extract( shortcode_atts( array(
            'image_id'                 => null,
            'background_attachment' => 'fixed'
        ), $atts ) );

        // No image ID set, return default content
        if( null === $image_id ) {
            return $content;
        }

        $image = wp_get_attachment_image_src( $image_id, 'full' );

        // Given attachment is not an image, return default content
        if( false === $image ) {
            return $content;
        }

        if($content != '') {

            $output .= '<div class="full-width-blockquote light change-menu">';
            $output .= sprintf( '<div class="blockquote" style="background-image: url(%1$s);">', $image[0]);
            $output .= '<div class="container">';
            $output .= '<blockquote>';

            $output .= apply_filters( 'stylish_image_content', $content );

            $output .= '</blockquote>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

        } else {

            $output .= '<div class="full-width-image change-menu">';
            $output .= sprintf('<img src="%1$s">', $image[0]);
            $output .= '</div>';

        }

        return $output;
    }

}

//add_filter( 'stylish_image_content', 'wpautop' );
//add_filter( 'stylish_image_content', 'wptexturize' );

Stylish_Image_Content::get_instance();