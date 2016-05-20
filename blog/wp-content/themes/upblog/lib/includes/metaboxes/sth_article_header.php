<?php
if ( !function_exists('sth_article_header') ) :
    function sth_article_header() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}article_header_mb",
            'title'     => __('Page Cover Options', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('post'),
            'context'   => 'normal',
            'priority'  => 'high',

            'fields'    => array(

                array(
                    'name'          => __( 'Article title & meta position', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}article_title_position",
                    'type'          => 'radio',
                    'options'       => array(
                        'left'                  => __('Left', LANGUAGE_ZONE),
                        'center'                => __('Middle', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'left',
                ),

                array(
                    'name' => __( 'Disable Background?', LANGUAGE_ZONE_ADMIN ),
                    'id'   => "{$prefix}disable_bg",
                    'type' => 'checkbox',
                    // Value can be 0 or 1
                    'std'  => 0,
                ),

            ),
        );

    }
endif;