<?php
if ( !function_exists('sth_page_background') ) :
    function sth_page_background() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}bg_image_mb",
            'title'     => __('Background Image', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page', STH_AlbumPostType::get_instance()->postType, STH_PhotoPostType::get_instance()->postType),
            'context'   => 'side',
            'priority'  => 'default',

            'fields'    => array(
                array(
                    'id'            => "{$prefix}bg_image",
                    'type'          => 'image_advanced',
                    'max_file_uploads' => 1
                ),
            ),
        );

    }
endif;