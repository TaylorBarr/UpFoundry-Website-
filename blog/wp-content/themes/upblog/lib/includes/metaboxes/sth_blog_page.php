<?php
if ( !function_exists('sth_blog_page_cover') ) :
    function sth_blog_page_cover() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}blog_page_cover_mb",
            'title'     => __('Blog Page Options', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page'),
            'context'   => 'normal',
            'priority'  => 'high',

            'show'      => array(
                'relation'  => 'OR',

                'template'   => array( 'template-blog.php' ),
            ),

            'fields'    => array(

                array(
                    'name'          => __( 'Choose Blog Style', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}blog_style",
                    'type'          => 'radio',
                    'options'       => array(
                        '1'             => __('Blog with no featured', LANGUAGE_ZONE),
                        '2'             => __('Blog with featured', LANGUAGE_ZONE_ADMIN),
                    ),
                    'std'           => '1',
                ),

                array(
                    'name' => __( 'Different articles background', LANGUAGE_ZONE_ADMIN ),
                    'id'   => "{$prefix}light_dark_bg",
                    'type' => 'checkbox',
                    // Value can be 0 or 1
                    'std'  => 0,
                    'desc' => __('Adds 2 different backgrounds for each article on the blog.', LANGUAGE_ZONE)
                ),

            ),
        );

    }
endif;