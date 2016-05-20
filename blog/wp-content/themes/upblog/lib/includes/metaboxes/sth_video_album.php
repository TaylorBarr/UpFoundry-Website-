<?php
if ( !function_exists('sth_video_album') ) :
    function sth_video_album() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}video_album_mb",
            'title'     => __('Video Post', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('post'),
            'context'   => 'normal',
            'priority'  => 'high',

            'show'      => array(
                'relation'  => 'OR',

                'post_format'   => array( 'video' ),
            ),

            'fields'    => array(
                array(
                    'name' => __( 'Video URL', LANGUAGE_ZONE ),
                    'id'   => "{$prefix}video_url",
                    'type' => 'text',
                    'desc' => __('You can use Youtube/Vimeo or other supported video oembeds for Wordpress.', LANGUAGE_ZONE),
                ),
            ),
        );

    }
endif;