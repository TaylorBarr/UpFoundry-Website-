<?php
/**
 * @author Stylish Themes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Stylish_Book {

    protected static $instance = null;

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        add_shortcode( 'stylish_book', array( &$this, 'shortcode' ) );
    }

    public function shortcode( $atts ){
        $output = $image_id = $title = $description = $button_1 = $button_2 = $icon = '';

        extract( shortcode_atts( array(
            'image_id'              => null,
            'title'                 => '',
            'description'           => '',
            'button_1'              => '',
            'button_2'              => '',
        ), $atts ) );

        $image = wp_get_attachment_image_src( $image_id, 'book_cover' );
        $button_1 = explode(',', $button_1);
        $button_2 = explode(',', $button_2);

        $output .= '<div class="products-list">';
        $output .= '<article>';
        $output .= '<div class="gift-feature-image">';
        $output .= sprintf('<img src="%1$s" alt="">', esc_url($image[0]));
        $output .= '</div>';
        $output .= sprintf('<h3>%1$s</h3>', esc_html($title));
        $output .= sprintf('<p>%1$s</p>', esc_html($description));

        if(!empty($button_1) && count($button_1) >= 2) {

            if(count($button_1) == 3) { $icon = '<i class="fa '.esc_attr($button_1[2]).'"></i>'; } else { $icon = ''; }

            $output .= sprintf('<a href="%1$s" class="btn btn-default">%2$s %3$s</a>', esc_url($button_1[1]), $icon, esc_html($button_1[0]));

        }

        if(!empty($button_2) && count($button_2) >= 2) {

            if(count($button_2) == 3) { $icon = '<i class="fa '.esc_attr($button_2[2]).'"></i>'; } else { $icon = ''; }

            $output .= sprintf('<a href="%1$s" class="btn btn-default">%2$s %3$s</a>', esc_url($button_2[1]), $icon, esc_html($button_2[0]));

        }

        $output .= '</article>';
        $output .= '</div>';

        return $output;
    }

}

Stylish_Book::get_instance();