<?php
if ( !function_exists('sth_portfolio_cover') ) :
    function sth_portfolio_cover() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}portfolio_cover_mb",
            'title'     => __('Portfolio Cover Image/Slider', LANGUAGE_ZONE_ADMIN),
            'pages'     => array(STH_PortfolioPostType::get_instance()->postType),
            'context'   => 'normal',
            'priority'  => 'high',

            'fields'    => array(
                array(
                    'name'          => __( 'Cover Type', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}portfolio_cover_type",
                    'type'          => 'radio',
                    'options'       => array(
                        'image'             => __('Fixed image', LANGUAGE_ZONE_ADMIN),
                        'slider'            => __('Slider', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'image',
                ),

                array(
                    'type' => 'heading',
                    'name' => __( 'Single Image Cover', LANGUAGE_ZONE_ADMIN ),
                    'id'   => 'fake_id', // Not used but needed for plugin
                ),

                array(
                    'name'             => __( 'Cover Single Image', LANGUAGE_ZONE_ADMIN ),
                    'id'               => "{$prefix}cover_single_image",
                    'type'             => 'image_advanced',
                    'max_file_uploads' => 1,
                ),

                array(
                    'type' => 'heading',
                    'name' => __( 'Slider Cover', LANGUAGE_ZONE_ADMIN ),
                    'id'   => 'fake_id', // Not used but needed for plugin
                ),

                array(
                    'name'             => __( 'Cover Slider Images', LANGUAGE_ZONE_ADMIN ),
                    'id'               => "{$prefix}cover_slider_images",
                    'type'             => 'image_advanced',
                    'max_file_uploads' => 99,
                ),

                array(
                    'name'          => __( 'Slider Logo', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}slider_logo",
                    'type'          => 'radio',
                    'options'       => array(
                        'on'        => __('ON', LANGUAGE_ZONE_ADMIN),
                        'off'           => __('OFF', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'on',
                ),

            ),
        );

    }
endif;