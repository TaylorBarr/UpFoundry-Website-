<?php
/**
 * Template Name: Blog Page
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$prefix = STH_Meta_Boxes::get_instance()->prefix;
$blog_style = rwmb_meta("{$prefix}blog_style");
$ldbg = rwmb_meta("{$prefix}light_dark_bg");
?>

    <div class="page-content<?php if($ldbg) { echo ' blog-with-distinct-articles'; } ?>">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-content-inner">
                        <div class="posts-list <?php if($blog_style == '2') { echo 'posts-with-feature'; } elseif(has_post_thumbnail()) { echo 'post-list-padding'; } else { echo 'with-border-top'; } ?>">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php

                                    // Construct the query
                                    if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
                                    elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
                                    else { $paged = 1; }
                                    $args = array(
                                        'post_type'         => 'post',
                                        'post_status'       => 'publish',
                                        'paged'             => $paged,
                                    );

                                    $query = new WP_Query($args);

                                    ?>

                                    <?php if ( $query->have_posts() ) : ?>

                                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                                            <?php
                                            /* Get the content template */
                                            if($blog_style == '2') {
                                                get_template_part( 'content-featured', get_post_format() );
                                            } else {
                                                get_template_part( 'content', get_post_format() );
                                            }
                                            ?>

                                        <?php endwhile; ?>

                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php
                            /**
                             * imbt_after_posts_loop hook
                             *
                             * @hooked Ajax Pagination
                             */
                            do_action( 'imbt_after_posts_loop', $query, '', 2 );
                            ?>

                            <?php
                            wp_reset_postdata();
                            // End The Loop
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Footer Top -->
    <?php get_template_part( 'pre-footer', '' ); ?>

<?php get_footer(); ?>