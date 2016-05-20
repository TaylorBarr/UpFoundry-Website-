<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); ?>

    <!-- Page Content -->
    <div class="page-content">
        <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-content-inner">
                    <div class="posts-list post-list-padding">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php if ( have_posts() ) : ?>

                                    <?php while ( have_posts() ) : the_post(); ?>

                                        <?php
                                        /* Get the content template */
                                        get_template_part( 'content', get_post_format() );
                                        ?>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <?php
                                    /* Get the content template */
                                    get_template_part( 'content', 'none' );
                                    ?>

                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                        /**
                         * imbt_after_posts_loop hook
                         *
                         * @hooked Ajax Pagination
                         */
                        global $wp_query;
                        do_action( 'imbt_after_posts_loop', $wp_query, '', 2 );
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