<?php
if ( !function_exists('sth_read_info') ) :
    function sth_read_info() {

        $prefix = STH_Meta_Boxes::get_instance()->prefix;

        return array(
            'id'        => "{$prefix}read_info_mb",
            'title'     => __('Read Time Info', LANGUAGE_ZONE_ADMIN),
            'pages'     => array('post'),
            'context'   => 'side',
            'priority'  => 'high',

            'fields'    => array(
                array(
                    'name'  => __( '', LANGUAGE_ZONE_ADMIN ),
                    'id'    => "{$prefix}read_info",
                    'type'  => 'text',
                    'desc'  => __('ex: "X minutes read" or "Short Read"', LANGUAGE_ZONE)
                ),
            ),
        );

    }
endif;