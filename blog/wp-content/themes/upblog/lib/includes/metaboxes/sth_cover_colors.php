<?php
if ( !function_exists('sth_cover_colors') ) :
    function sth_cover_colors() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}cover_colors_mb",
            'title'     => __('Page Cover Colors', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page', 'post'),
            'context'   => 'normal',
            'priority'  => 'high',

            'fields'    => array(

                array(
                    'name'          => __( 'Color Scheme', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}blog_color_scheme",
                    'type'          => 'radio',
                    'options'       => array(
                        'light'            => __('Light', LANGUAGE_ZONE),
                        'dark'             => __('Dark', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'light',
                ),

                array(
                    'type' => 'color',
                    'name' => __( 'Custom color background', LANGUAGE_ZONE_ADMIN ),
                    'id'   => "{$prefix}blog_custom_color",
                ),

            ),
        );

    }
endif;