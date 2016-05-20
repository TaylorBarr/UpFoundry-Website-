<?php
if ( !function_exists('sth_contact_info') ) :
    function sth_contact_info() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}contact_info_mb",
            'title'     => __('Contact Info', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('page'),
            'context'   => 'normal',
            'priority'  => 'high',

            'show'      => array(
                'relation'  => 'OR',

                'template'   => array( 'template-contact.php' ),
            ),

            'fields'    => array(
                array(
                    'name'  => __( 'Contact Address', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}contact_address",
                    'type'  => 'text',
                    'std'   => 'New York, USA',
                    'desc'  => __('You should take this from Google Maps directly.', LANGUAGE_ZONE),
                ),
                array(
                    'name'  => __( 'Contact Email', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}contact_email",
                    'type'  => 'text',
                    'std'   => 'youremail@domain.com',
                    'desc'  => __('The email address where the messages from the contact page will be sent.', LANGUAGE_ZONE),
                ),
                array(
                    'name'  => __( 'Map Pointer Image', LANGUAGE_ZONE_ADMIN ),
                    'id'            => "{$prefix}contact_pointer",
                    'type'          => 'image_advanced',
                    'max_file_uploads' => 1,
                    'desc'  => __('The pointer image it will be used for the map.', LANGUAGE_ZONE)
                ),
                array(
                    'name'  => __( 'Pointer Box Title', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}contact_title",
                    'type'  => 'text',
                    'std'   => 'Title Here',
                    'desc'  => __('This will appear when you click on the pointer image.', LANGUAGE_ZONE),
                ),
                array(
                    'name'  => __( 'Pointer Box Description', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}contact_description",
                    'type' => 'wysiwyg',
                    'raw'  => false,
                    'options' => array(
                        'textarea_rows' => 4,
                        'teeny'         => true,
                        'media_buttons' => false,
                    ),
                    'std'   => 'Description Here',
                    'desc'  => __('This will appear when you click on the pointer image.', LANGUAGE_ZONE),
                ),
            ),
        );

    }
endif;