<?php
if ( !function_exists('sth_post_page_sidebar') ) :
    function sth_post_page_sidebar() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}post_layout_mb",
            'title'     => __('Post Layout', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('post'),
            'context'   => 'side',
            'priority'  => 'high',

            'fields'    => array(
                array(
                    'id'            => "{$prefix}post_layout",
                    'type'          => 'radio',
                    'class'         => 'custom_sidebar_select',
                    'options'       => array(
                        'sidebar'        => __('With Sidebar', LANGUAGE_ZONE_ADMIN),
                        'full'           => __('Full Width', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'full',
                    'desc'          => '',
                ),
            ),
        );

    }
endif;