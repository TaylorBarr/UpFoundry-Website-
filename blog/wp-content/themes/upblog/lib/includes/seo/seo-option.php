<?php

function nm_callback_general_seo() {
    include 'seo-graphs.php';
}

/**
 * Facebook share correct image fix (thanks to yoast).
 */
function nm_callback_facebook_opengraph() {
    include 'facebook-graphs.php';
}

function load_social_share() {
    add_action( 'wp_head', 'nm_callback_general_seo' );
    add_action( 'wp_head', 'nm_callback_facebook_opengraph' );
}

add_action( 'init', 'load_social_share', 5 );