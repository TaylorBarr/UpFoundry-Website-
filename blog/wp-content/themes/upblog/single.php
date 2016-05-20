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

        <!-- Page Content -->
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-content-inner">

                            <?php the_content(); ?>

                            <?php echo Core_Helpers::get_instance()->get_single_post_pagination(); ?>

                            <div class="tags-area clearfix">
                                <div class="row">
                                    <div class="col-sm-6">

                                        <div id="share-button-head" class="share-button" data-url="<?php echo THEMEROOT . '/lib/includes/others/socialworth.php'; ?>">
                                            <i class="fa fa-share-alt"></i>
                                            <span id="totalshare"><?php _e('loading...', LANGUAGE_ZONE) ?></span>
                                            <ul>
                                                <li>
                                                    <a target="popup" onclick="window.open('http://api.addthis.com/oexchange/0.8/forward/googleplus/offer?url=<?php the_permalink(); ?>','name','width=600,height=400')" rel="nofollow">
                                                        <i class="fa fa-google-plus"></i>
                                                        <?php _e('google+', LANGUAGE_ZONE) ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="popup" onclick="window.open('http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=<?php the_permalink(); ?>','name','width=600,height=400')" rel="nofollow">
                                                        <i class="fa fa-twitter"></i>
                                                        <?php _e('twitter', LANGUAGE_ZONE) ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="popup" onclick="window.open('http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=<?php the_permalink(); ?>','name','width=600,height=400')" rel="nofollow">
                                                        <i class="fa fa-facebook"></i>
                                                        <?php _e('facebook', LANGUAGE_ZONE) ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="popup" onclick="window.open('http://api.addthis.com/oexchange/0.8/forward/vk/offer?url=<?php the_permalink(); ?>','name','width=600,height=400')" rel="nofollow">
                                                        <i class="fa fa-vk"></i>
                                                        <?php _e('VK', LANGUAGE_ZONE) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <?php //IMBT_Core::get_instance()->download_as_PDF(); ?>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="tags">
                                            <?php the_tags( '<span>'.__('Tags', LANGUAGE_ZONE) . ': </span>', ' ', '' ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php IMBT_Core::get_instance()->the_author_widget(); ?>

                            <?php
                            $related_articles = Core_Helpers::get_instance()->get_related_posts_ids(get_the_ID());

                            if($related_articles) :
                            ?>
                                <div class="posts-list with-border-top related-posts">
                                    <h4 class="related-posts-heading"><?php _e('You may also like', LANGUAGE_ZONE) ?></h4>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php

                                            // Construct the query
                                            $args = array(
                                                'post__in'      => $related_articles,
                                                'post_status'   => 'publish',
                                                'orderby'       => 'post__in',
                                                'ignore_sticky_posts'=>true,
                                            );

                                            $query = new WP_Query($args);

                                            ?>

                                            <?php if ( $query->have_posts() ) : ?>

                                                <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                                                    <?php
                                                    /* Get the content template */
                                                    get_template_part( 'content', get_post_format() );
                                                    ?>

                                                <?php endwhile; ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php
                                    wp_reset_postdata();
                                    // End The Loop
                                    ?>

                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Footer Top -->
            <?php get_template_part( 'pre-footer', '' ); ?>


            <div class="container" id="comments">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-container-inner">

                            <?php comments_template('', true); ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>


        <?php endwhile; ?>
    <?php endif; ?>

<?php get_footer(); ?>