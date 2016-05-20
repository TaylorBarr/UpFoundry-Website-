<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class STH_Map {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'sth_map', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $address = $marker = $title = $desc = '';

        extract( shortcode_atts( array(
            'address'           => '',
            'marker'            => '',
            'title'             => '',
            'desc'              => ''
        ), $atts ) );

        // Enqueue the needed scripts for players.
        wp_enqueue_script('google-maps-api');
        wp_enqueue_script('mapJS');

        $marker = wp_get_attachment_image_src( $marker, 'full' );
        $marker = $marker[0];

        $output .= Core_Helpers::get_instance()->get_google_maps($address, $marker, $title, $desc);

        return $output;
    }

}

STH_Map::get_instance();