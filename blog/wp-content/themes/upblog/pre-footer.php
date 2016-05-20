<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<div class="page-footer-top">
    <div class="container">

        <?php
            $prefix = STH_Meta_Boxes::get_instance()->prefix;
            $footer_components = rwmb_meta("{$prefix}footer_components");

            if(is_single()) {

                get_template_part('pre-footer', 'subscribe');

            } elseif($footer_components == 'both') {

                get_template_part( 'pre-footer', 'subscribe' );

                echo '<hr>';

                get_template_part( 'pre-footer', 'sidebar' );

            } elseif ($footer_components == 'subscribe') {

                get_template_part( 'pre-footer', 'subscribe' );

            } elseif ($footer_components == 'sidebar') {

                get_template_part( 'pre-footer', 'sidebar' );

            } else {

                get_template_part( 'pre-footer', 'subscribe' );

                if( get_theme_mod( 'show_subscribe_form_in_page_footer' ) ) {
                    echo '<hr>';
                }

                get_template_part( 'pre-footer', 'sidebar' );

            }
        ?>

    </div>
</div>