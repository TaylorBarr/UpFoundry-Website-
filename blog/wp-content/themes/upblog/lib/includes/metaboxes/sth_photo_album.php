<?php
if ( !function_exists('sth_photo_album') ) :
    function sth_photo_album() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}photo_album_mb",
            'title'     => __('Add/Edit Photos', LANGUAGE_ZONE_ADMIN),
            'pages'     => array(STH_PhotoPostType::get_instance()->postType),
            'context'   => 'normal',
            'priority'  => 'high',

            'fields'    => array(
                array(
                    'name' => __( 'Photos per page', LANGUAGE_ZONE ),
                    'id'   => "{$prefix}photo_album_offset",
                    'type' => 'number',
                    'desc' => __('How many photos to load on each page?', LANGUAGE_ZONE),
                    'std'  => 7,
                    'min'  => 0,
                    'step' => 1,
                ),
                array(
                    'id'            => "{$prefix}photo_album_items",
                    'type'          => 'image_advanced',
                    'max_file_uploads' => 999
                ),
            ),
        );

    }
endif;