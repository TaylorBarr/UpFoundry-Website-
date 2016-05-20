<?php
if ( !function_exists('sth_footer_options') ) :
    function sth_footer_options() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}footer_options_mb",
            'title'     => __('Footer Options', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page'),
            'context'   => 'normal',
            'priority'  => 'low',

            'fields'    => array(

                array(
                    'name'          => __( 'Footer Components', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}footer_components",
                    'type'          => 'radio',
                    'options'       => array(
                        'both'                  => __('Subscribe Form & Sidebar', LANGUAGE_ZONE),
                        'subscribe'             => __('Only Subscribe Form', LANGUAGE_ZONE_ADMIN),
                        'sidebar'               => __('Only Sidebar', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'both',
                ),

            ),
        );

    }
endif;