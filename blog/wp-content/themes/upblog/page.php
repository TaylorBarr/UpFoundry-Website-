<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); ?>


    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-content-inner">

                            <?php the_content(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endwhile; ?>

    <?php endif; ?>

    <!-- Page Footer Top -->
    <?php get_template_part( 'pre-footer', '' ); ?>

<?php get_footer(); ?>