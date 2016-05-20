<?php
if ( !function_exists('sth_photo_album') ) :
    function sth_portfolio_mb() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}portfolio_mb",
            'title'     => __('Portfolio Info', LANGUAGE_ZONE_ADMIN),
            'pages'     => array(STH_PortfolioPostType::get_instance()->postType),
            'context'   => 'normal',
            'priority'  => 'high',

            'tabs'      => array(
                'info' => array(
                    'label' => __( 'Portfolio Images', LANGUAGE_ZONE ),
                ),
                'meta'  => array(
                    'label' => __( 'Meta Info', LANGUAGE_ZONE ),
                ),
                'style'  => array(
                    'label' => __( 'Portfolio Style', LANGUAGE_ZONE ),
                ),
            ),

            'fields'    => array(
                array(
                    'name'             => __( 'Choose images', LANGUAGE_ZONE_ADMIN ),
                    'id'               => "{$prefix}portfolio_images",
                    'type'             => 'image_advanced',
                    //'desc'             => __('<strong>The images should be </strong>', LANGUAGE_ZONE),
                    'max_file_uploads' => 99,
                    'tab'  => 'info'
                ),
                array(
                    'id'        => "{$prefix}portfolio_meta_group",
                    'name'        => __('Meta Details', LANGUAGE_ZONE),
                    'type'        => 'group',
                    'clone'        => true,
                    'fields'    => array(
                        array(
                            'name'  => __( 'Title', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}portfolio_meta_title",
                            'type'  => 'text',
                            'desc'  => __('For example, it could be <strong>"Release Date"</strong>.', LANGUAGE_ZONE)
                        ),
                        array(
                            'name'  => __( 'Value', LANGUAGE_ZONE_ADMIN ),
                            'id'    => "{$prefix}portfolio_meta_value",
                            'type'  => 'text',
                            'desc'  => __('For "Release Date" it could be <strong>"13/12/2015"</strong>.', LANGUAGE_ZONE)
                        ),
                    ),
                    'tab'  => 'meta'
                ),
                array(
                    'name'          => __( 'Layout Style', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}portfolio_layout",
                    'type'          => 'radio',
                    'options'       => array(
                        '2columns'        => __('2 Columns', LANGUAGE_ZONE_ADMIN),
                        'fixed'           => __('Fixed', LANGUAGE_ZONE_ADMIN),
                        'fixedleft'           => __('Fixed Left', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => 'fixed',
                    'tab'           => 'style'
                ),
            ),
        );

    }
endif;