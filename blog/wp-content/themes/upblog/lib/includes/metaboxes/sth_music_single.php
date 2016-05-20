<?php
if ( !function_exists('sth_music_single') ) :
    function sth_music_single() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}music_mb",
            'title'     => __('Music Song/Album Info', LANGUAGE_ZONE_ADMIN),
            'pages'     => array(STH_AlbumPostType::get_instance()->postType),
            'context'   => 'normal',
            'priority'  => 'high',

            'tabs'      => array(
                'info' => array(
                    'label' => __( 'Basic Info', LANGUAGE_ZONE ),
                ),
                'acc' => array(
                    'label' => __( 'Accordion Details', LANGUAGE_ZONE ),
                ),
                'download'  => array(
                    'label' => __( 'Purchase/Download Info', LANGUAGE_ZONE ),
                ),
                'meta'  => array(
                    'label' => __( 'Meta Info', LANGUAGE_ZONE ),
                ),
            ),

            // Tab style: 'default', 'box' or 'left'. Optional
            'tab_style' => 'left',

            'fields'    => array(
                array(
                    'name'     => __( 'Player Color Scheme', LANGUAGE_ZONE_ADMIN ),
                    'id'       => "{$prefix}music_player_style",
                    'type'     => 'select',
                    'options'  => array(
                        'dark'  => __( 'Dark', LANGUAGE_ZONE_ADMIN ),
                        'light' => __( 'Light', LANGUAGE_ZONE_ADMIN )
                    ),
                    'multiple'    => false,
                    'std'         => 'dark',
                    'tab'  => 'info'
                ),
                array(
                    'name'  => __( 'SoundCloud URL', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}music_soundcloud_url",
                    'type'  => 'text',
                    'std'   => 'https://soundcloud.com/zedsdead/hadouken',
                    'desc'  => __('This URL will be used to get the audio from SoundCloud. It could be a <strong>single song</strong> or a <strong>playlist</strong> (album).', LANGUAGE_ZONE),
                    'tab'  => 'info'
                ),
                array(
                    'name' => __( 'Description', LANGUAGE_ZONE_ADMIN ),
                    'id'   => "{$prefix}music_description",
                    'type' => 'wysiwyg',
                    'raw'  => false,
                    'std'  => __( '', LANGUAGE_ZONE_ADMIN ),
                    'options' => array(
                        'textarea_rows' => 6,
                        'teeny'         => true,
                        'media_buttons' => false,
                    ),
                    'tab'  => 'info'
                ),
                array(
                    'id'        => "{$prefix}music_meta_group",
                    'name'        => __('Meta Details', LANGUAGE_ZONE),
                    'type'        => 'group',
                    'clone'        => true,
                    'fields'    => array(
                        array(
                            'name'  => __( 'Title', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_meta_title",
                            'type'  => 'text',
                            'desc'  => __('For example, it could be <strong>"Release Date"</strong>.', LANGUAGE_ZONE)
                        ),
                        array(
                            'name'  => __( 'Value', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_meta_value",
                            'type'  => 'text',
                            'desc'  => __('For "Release Date" it could be <strong>"13/12/2015"</strong>.', LANGUAGE_ZONE)
                        ),
                    ),
                    'tab'  => 'meta'
                ),
                array(
                    'id'        => "{$prefix}music_buy_group",
                    'name'        => __('Purchase/Download Info', LANGUAGE_ZONE),
                    'type'        => 'group',
                    'clone'        => true,
                    'fields'    => array(
                        array(
                            'name'  => __( 'Icon ID', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_buy_icon",
                            'type'  => 'text',
                            'desc'  => __('Take the icon ID from <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/3.2.1/icons/">here</a>.', LANGUAGE_ZONE)
                        ),
                        array(
                            'name'  => __( 'Purchase/Download URL', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_buy_url",
                            'type'  => 'text',
                            'desc'  => __('The URL for download or purchase.', LANGUAGE_ZONE)
                        ),
                    ),
                    'tab'  => 'download'
                ),
                array(
                    'name'  => __( 'Accordion Section Title', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}music_accordion_title",
                    'type'  => 'text',
                    'std'   => 'Lyrics',
                    'desc'  => __('This is used for the accordion section and you can use it for lyrics, tracklists or other things.', LANGUAGE_ZONE),
                    'tab'  => 'acc'
                ),
                array(
                    'id'        => "{$prefix}music_accordion_group",
                    'name'        => __('Accordion Section Details', LANGUAGE_ZONE),
                    'type'        => 'group',
                    'clone'        => true,
                    'fields'    => array(
                        array(
                            'name'  => __( 'Title', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_acc_title",
                            'type'  => 'text',
                        ),
                        array(
                            'name'  => __( 'Value', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}music_acc_value",
                            'type'  => 'textarea',
                            'options' => array(
                                'textarea_rows' => 6,
                                'teeny'         => true,
                                'media_buttons' => false,
                            ),
                        ),
                    ),
                    'tab'  => 'acc'
                ),
            ),
        );

    }
endif;