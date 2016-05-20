<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class VC_Setup {

    protected static $instance = null;

    public $tabs = 0;
    public $accordion_instance = array(
        'id'    => 0,
        'type'  => '',
        'tab_id'=> 0,
    );

    private function __construct() {

        if (class_exists('Vc_Manager')) {

            /**
             * Remove other elements.
             */
            vc_remove_element('vc_tour');

            /**
             * Setup the new shortcodes.
             */
            $this->setup_image_quote_fullwidth();
            $this->setup_book();
            $this->setup_buttons();

        }

    }

    public function setup_image_quote_fullwidth() {

        vc_map( array(
            'name' => __( 'IMBT Fullwidth Image & Quote', LANGUAGE_ZONE_ADMIN ),
            'base' => 'stylish_image_content',
            'icon' => 'icon-wpb-application-icon-large',
            'category' => __( 'IMBT Shortcodes', LANGUAGE_ZONE_ADMIN ),
            'description' => __( 'A stylish fullwidth image with a quote above it.', LANGUAGE_ZONE_ADMIN ),
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __( 'Image', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'image_id',
                    'description' => __( 'Select the fullwidth background image.', LANGUAGE_ZONE_ADMIN ),
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Content', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'content',
                    'description' => __( 'If you don\'t add any content here, the widget will display only a static fullwidth image.', LANGUAGE_ZONE_ADMIN ),
                ),
            )
        ) );

    }

    public function setup_book() {

        vc_map( array(
            'name' => __( 'IMBT Book', LANGUAGE_ZONE_ADMIN ),
            'base' => 'stylish_book',
            'icon' => 'icon-wpb-application-icon-large',
            'category' => __( 'IMBT Shortcodes', LANGUAGE_ZONE_ADMIN ),
            'description' => __( 'A stylish book with cover image & purchase info.', LANGUAGE_ZONE_ADMIN ),
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __( 'Cover Image', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'image_id',
                    'description' => __( 'Select the book cover image.', LANGUAGE_ZONE_ADMIN ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Book Title', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'title',
                    'description' => __( 'The title of the book.', LANGUAGE_ZONE_ADMIN ),
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Short Description', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'description',
                    'description' => __( 'The book description.', LANGUAGE_ZONE_ADMIN ),
                ),
                array(
                    'type' => 'exploded_textarea',
                    'heading' => __( 'Button 1', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'button_1',
                    'std'       => 'Buy from Apple,http://google.ro,fa-apple',
                    'description' => __( 'On the first line the button text, on the second the url of the button and on the last the Font Awesome icon class.', LANGUAGE_ZONE_ADMIN ),
                ),
                array(
                    'type' => 'exploded_textarea',
                    'heading' => __( 'Button 2', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'button_2',
                    'std'       => 'Buy from Apple,http://google.ro,fa-apple',
                    'description' => __( 'On the first line the button text, on the second the url of the button and on the last the Font Awesome icon class.', LANGUAGE_ZONE_ADMIN ),
                ),
            )
        ) );

    }

    public function setup_buttons(){

        vc_map( array(
            "name" => __("IMBT Button", LANGUAGE_ZONE_ADMIN),
            'base' => 'stylish_button',
            'icon' => 'icon-wpb-application-icon-large',
            'category' => __( 'IMBT Shortcodes', LANGUAGE_ZONE_ADMIN ),
            'description' => __( 'A stylish button.', LANGUAGE_ZONE_ADMIN ),
            "params" => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Type', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'type',
                    'value' => array(
                        __( 'Empty Button with Grey Border', LANGUAGE_ZONE_ADMIN )         => 'btn-default',
                        __( 'Empty Button with Dark Border', LANGUAGE_ZONE_ADMIN )         => 'btn-primary',
                        __( 'Full Button', LANGUAGE_ZONE_ADMIN )                           => 'btn-success',
                    ),
                    'description' => __( 'Select the type you want.', LANGUAGE_ZONE_ADMIN )
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Size', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'size',
                    'value' => array(
                        __( 'Large', LANGUAGE_ZONE_ADMIN )         => 'btn-lg',
                        __( 'Normal', LANGUAGE_ZONE_ADMIN )         => '',
                        __( 'Small', LANGUAGE_ZONE_ADMIN )         => 'btn-sm',
                        __( 'Extra Small', LANGUAGE_ZONE_ADMIN )         => 'btn-xs',
                    ),
                    'description' => __( 'Select the size of the button you want.', LANGUAGE_ZONE_ADMIN )
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("URL", LANGUAGE_ZONE_ADMIN),
                    "param_name" => "link",
                    //"description" => __("The text you want to display on the box.", LANGUAGE_ZONE_ADMIN),
                    //"value" => ""
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("Text on the button", LANGUAGE_ZONE_ADMIN),
                    "param_name" => "text",
                    //"description" => __("The text you want to display on the box.", LANGUAGE_ZONE_ADMIN),
                    //"value" => ""
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Open in', LANGUAGE_ZONE_ADMIN ),
                    'param_name' => 'target',
                    'value' => array(
                        __( 'Same page', LANGUAGE_ZONE_ADMIN )         => '_self',
                        __( 'New tab', LANGUAGE_ZONE_ADMIN )        => '_blank',
                    ),
                    //'description' => __( 'Select the size of the button you want.', LANGUAGE_ZONE_ADMIN )
                ),
            )
        ) );

    }

    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}

VC_Setup::get_instance();

