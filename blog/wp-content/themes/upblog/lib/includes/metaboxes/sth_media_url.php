<?php
if ( !function_exists('sth_media_url') ) :
    function sth_media_url() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}video_url_mb",
            'title'     => __('Video/Audio URL', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('post'),
            'context'   => 'normal',
            'priority'  => 'high',

            'show'      => array(
                'relation'  => 'OR',

                'post_format'   => array( 'Video', 'Audio' ),
            ),

            'fields'    => array(
                array(
                    'name'  => __( '', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}video_url",
                    'type'  => 'text',
                    'desc'  => __('This URL could be from YouTube/Vimeo/Soundcloud/Mixcloud or any suported embeded players of Wordpress.', LANGUAGE_ZONE)
                ),
            ),
        );

    }
endif;