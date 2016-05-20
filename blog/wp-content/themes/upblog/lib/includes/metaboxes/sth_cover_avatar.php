<?php
if ( !function_exists('sth_cover_avatar') ) :
    function sth_cover_avatar() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}cover_avatar_mb",
            'title'     => __('Page Cover Options', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page'),
            'context'   => 'normal',
            'priority'  => 'high',

            'fields'    => array(

                array(
                    'name'          => __( 'Cover Type', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}blog_cover_avatar",
                    'type'          => 'radio',
                    'options'       => array(
                        'on'              => __('Avatar', LANGUAGE_ZONE),
                        'off'             => __('Thick', LANGUAGE_ZONE_ADMIN),
                        'title'           => __('Page Title', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'off',
                ),

                array(
                    'name'             => __( 'Avatar Image', LANGUAGE_ZONE_ADMIN ),
                    'id'               => "{$prefix}blog_avatar",
                    'type'             => 'image_advanced',
                    'max_file_uploads' => 1,
                ),

                array(
                    'name'  => __( 'Heading', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}blog_heading",
                    'type'  => 'textarea',
                    'std'   => 'Hello. I\'m a writer. I live in a small town from Spain.',
                ),

                array(
                    'name' => __( 'More padding bottom', LANGUAGE_ZONE_ADMIN ),
                    'id'   => "{$prefix}more_padding_bottom",
                    'type' => 'checkbox',
                    // Value can be 0 or 1
                    'std'  => 0,
                    'desc' => __('This is available only on Avatar cover type when you don\'t have a featured image.', LANGUAGE_ZONE)
                ),

                array(
                    'type' => 'heading',
                    'name' => __( 'Call to action button', LANGUAGE_ZONE_ADMIN ),
                    'id'   => 'fake_id', // Not used but needed for plugin
                ),

                array(
                    'name'  => __( 'Button Text', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}button_text",
                    'type'  => 'text',
                ),

                array(
                    'name' => __( 'Button URL', LANGUAGE_ZONE ),
                    'id'   => "{$prefix}blog_avatar_url",
                    'desc' => __( 'Place here the url to the author about page or any other url.', LANGUAGE_ZONE ),
                    'type' => 'url',
                ),

            ),
        );

    }
endif;