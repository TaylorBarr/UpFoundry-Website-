<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class STH_Client {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'sth_clients', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $clients = '';

        extract( shortcode_atts( array(
            'clients'              => '',
        ), $atts ) );

        $clients = explode(",", $clients);

        foreach($clients as $client) {

            $img_url = wp_get_attachment_image_src( $client, 'clients' );
            $img_url = $img_url[0];

            $output .= '<div class="col-sm-4">';
            $output .= '<div class="bordered-item without-padding">';
            $output .= '<img src="'.esc_url($img_url).'" alt="client">';
            $output .= '</div></div>';
        }

        return $output;
    }

}

STH_Client::get_instance();