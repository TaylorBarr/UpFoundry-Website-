<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $clx_data;

$prefix = STH_Meta_Boxes::get_instance()->prefix;
$blog_color_scheme = rwmb_meta("{$prefix}blog_color_scheme");
$footer_components = rwmb_meta("{$prefix}footer_components");
$body_class = '';

do_action('stylish_before_subscribe', get_the_ID());

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php if(isset($clx_data['favico']) && $clx_data['favico']['url'] != ''): ?>
        <link rel="shortcut icon" href="<?php echo esc_url($clx_data['favico']['url']); ?>" />
    <?php endif; ?>

    <!-- Script required for extra functionality on the comment form -->
    <?php if (is_singular()) wp_enqueue_script( 'comment-reply' ); ?>

    <?php wp_head(); ?>

</head>

<body <?php body_class($body_class); ?> id="body">

    <?php if(strlen(trim($clx_data['csscode']))) : ?><style type="text/css"><?php echo esc_attr($clx_data['csscode']); ?></style><?php endif; ?>

    <!-- Page Head -->
    <!-- Toggle Class 'light' to change layout color -->
    <div class="page-head <?php if(((is_page() || is_single()) && $blog_color_scheme == 'light')) { echo 'light'; } ?> <?php if(is_single()) { echo 'is-on-blog'; } ?>">

        <!-- Page Header -->
        <div class="page-header">

            <div class="header-left-js-button">
                <div class="menu-button">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <?php if(is_single()): ?>
                    <div class="share-button">
                        <i class="fa fa-share-alt"></i>
                        <span id="totalsharehead"><?php _e('loading...', LANGUAGE_ZONE) ?></span>
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
                <?php endif; ?>
            </div>

            <div class="header-content clearfix">

                <div class="middle-side">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr($clx_data['logo-title']); ?>">
                    	<?php if( '' != get_theme_mod( 'site_logo_light' ) || '' != get_theme_mod( 'site_logo_dark' ) ) : ?>
                    		<?php if( 'light' == $blog_color_scheme || '' == get_theme_mod( 'site_logo_light' ) ) : ?>
                    			<img src="<?php echo esc_url( get_theme_mod( 'site_logo_dark' ) ); ?>" alt="<?php esc_attr_e( strip_tags( get_bloginfo( 'name' ) ) ); ?>">
                    		<?php else : ?>
	                    		<img src="<?php echo esc_url( get_theme_mod( 'site_logo_light' ) ); ?>" alt="<?php esc_attr_e( strip_tags( get_bloginfo( 'name' ) ) ); ?>">
                    		<?php endif; ?>
                    	<?php else : ?>
                    		<?php bloginfo( 'name' ); ?>
                    	<?php endif; ?>
                    </a>
                </div>
				
				<?php if( get_theme_mod( 'show_subscribe_form_in_page_footer' ) &&
                    ($footer_components == 'both' || $footer_components == 'subscribe' || is_single() || is_home() || is_archive() || is_search()) ) : ?>
	                <div class="left-side">
	                	<?php if( '' != get_theme_mod( 'subscribe_cta' ) ) : ?>
		                    <i class="fa fa-gift"></i>
		                    <p>
		                        <?php esc_attr_e( get_theme_mod( 'subscribe_cta' ), 'stylish' ); ?>
		                    </p>
	                    <?php endif; ?>
	                    <a href="#subscribe" data-easing="easeInOutQuint" data-scroll="" data-speed="600" data-url="false">
	                        <?php _e( 'Subscribe', 'stylish' ); ?>
	                    </a>
	                </div>
                <?php endif; ?>

                <div class="right-side">
    				<?php wp_nav_menu( array(
    					'theme_location'  => 'social',
    					'container'       => 'nav',
    					'container_class' => 'social-icons-list',
    					'menu_class'      => '',
    					'fallback_cb'     => '',
    				) ); ?>
                </div>
            </div>

            <div class="header-right-js-button">
                <?php if(is_single()): ?>
                    <?php //IMBT_Core::get_instance()->download_as_PDF(); ?>
                <?php endif; ?>
                <i class="fa fa-search"></i>
                <div class="menu-button">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>

        <?php if(is_single()): ?>

            <?php
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
            $bg_color = rwmb_meta("{$prefix}blog_custom_color");
            $video_url = rwmb_meta("{$prefix}video_url");
            $title_position = rwmb_meta("{$prefix}article_title_position");
            $disable_bg = rwmb_meta("{$prefix}disable_bg");
            ?>

            <!-- Blog Single Head -->
            <?php if(get_post_format() == 'video' && $video_url != ''): ?>

                <div class="blog-single-head video-feature">
                    <?php echo wp_oembed_get( $video_url, array('height' => '396') ); ?>
                </div>

            <?php else: ?>

                <div class="blog-single-head<?php if ( has_post_thumbnail() ) { echo ' with-image'; } if($title_position == 'center') { echo ' align-center-text'; } if($disable_bg) { echo ' without-bg'; } ?>" style="<?php if($image[0] != ''): ?>background-image: url('<?php echo esc_url($image[0]); ?>');<?php endif; ?> <?php if($bg_color != ''): ?>background-color: <?php echo esc_attr($bg_color); ?>;<?php endif; ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h1>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="article-details">
                                    <?php $read_info = rwmb_meta("{$prefix}read_info"); ?>
                                    <?php if($read_info != ''): ?>
                                    <span>
                                        <i class="fa fa-bookmark-o"></i>
                                        <?php echo esc_html($read_info); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php $post_author_id = get_post_field( 'post_author', get_the_ID() ); ?>
                                    <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID', $post_author_id ) )); ?>">
                                        <i class="fa fa-user"></i>
                                        <?php
                                        the_author_meta( 'display_name', $post_author_id );
                                        ?>
                                    </a>
                                    <?php if ( comments_open() ) : ?>
                                        <?php comments_popup_link(
                                            '<i class="fa fa-comment-o"></i> ' . __( '0 Comments', LANGUAGE_ZONE ),
                                            '<i class="fa fa-comment-o"></i> ' . __( '1 Comment', LANGUAGE_ZONE ),
                                            '<i class="fa fa-comment-o"></i> ' . __( '% Comments', LANGUAGE_ZONE  )
                                        ); ?>
                                    <?php endif; ?>
                                   
                                    <span>
                                        <i class="fa fa-folder-open-o"></i>
                                        <?php the_category(' '); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        <?php elseif(is_author()): ?>

            <div class="page-title-or-author more-padding-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="title">
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 200 ); ?>
                                <h3>
                                    <?php _e('Posts by ', LANGUAGE_ZONE); echo esc_html(get_the_author_meta( 'display_name' )); ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif(is_home() || is_archive() || is_search()): ?>

            <div class="page-title-or-author centred-content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="title">
                                <h1>
                                    <?php Core_Helpers::get_instance()->the_page_title(); ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif(is_page()): ?>

            <?php
            $cover_avatar = rwmb_meta("{$prefix}blog_cover_avatar");
            $bg_color = rwmb_meta("{$prefix}blog_custom_color");

            if($cover_avatar == 'on'): ?>

                <?php
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                $heading = rwmb_meta("{$prefix}blog_heading");
                $button_text = rwmb_meta("{$prefix}button_text");
                $button_url = rwmb_meta("{$prefix}blog_avatar_url");
                $more_padding_bottom = rwmb_meta("{$prefix}more_padding_bottom");
                $avatar = rwmb_meta("{$prefix}blog_avatar", array('type'=>'image_advanced', 'size'=>'imbt_avatar'));
                $avatar = array_shift($avatar);
                ?>

                <div class="page-title-or-author <?php if($more_padding_bottom) { echo 'more-padding-bottom'; } ?> <?php if($image[0] != '') { echo 'extended-author with-image'; } ?>"
                    style="<?php if($image[0] != ''): ?>background-image: url('<?php echo esc_url($image[0]); ?>');<?php endif; ?> <?php if($bg_color != ''): ?>background-color: <?php echo esc_attr($bg_color); ?>;<?php endif; ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title">
                                    
                                    <h3>
                                        <?php echo do_shortcode(str_replace("\n", "<br />", $heading)); ?>
                                    </h3>
                                    <?php if($button_text != ''): ?>
                                        <hr>
                                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary"><?php echo esc_html($button_text); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif($cover_avatar == 'title'): ?>

                <div class="page-title-or-author centred-content" <?php if($bg_color != ''): ?> style="background-color: <?php echo esc_attr($bg_color); ?>;" <?php endif; ?>>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="title">
                                    <h1>
                                        <?php the_title(); ?>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>

                <?php
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
                $bg_img = $image[0];
                ?>

                <?php if($bg_img != ''): ?>

                    
                <?php else: ?>

                    <div class="page-title-or-author extended-author narrow-on-bottom without-content" <?php if($bg_color != ''): ?> style="background-color: <?php echo esc_attr($bg_color); ?>;" <?php endif; ?>>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

        <?php else: ?>

            <div class="page-title-or-author extended-author narrow-on-bottom without-content">
            </div>

        <?php endif; ?>

    </div>

    <!-- Menu And Search Container -->
    <div class="menu-search-container">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <nav class="main-menu">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location'    => 'main-menu',
                                    'menu_class'        => '',
                                    'container'         => '',
                                    'fallback_cb'       => false
                                ));
                            ?>
                        </nav>

                        <?php get_search_form(); ?>
                    </div>
                    
                    <?php if( get_theme_mod( 'show_subscribe_form_in_header' ) ) : ?>
	                    <div class="col-sm-6">
	                        <div class="subscribe-menu">
	                        	<?php if( '' != get_theme_mod( 'subscribe_image' ) ) : ?>
	                                <div class="gift-feature-image">
	                                    <img src="<?php echo esc_url( get_theme_mod( 'subscribe_image' ) ); ?>" alt="">
	                                </div>
	                            <?php endif; ?>
	                            
	                        	<?php if( '' != get_theme_mod( 'subscribe_title' ) ) : ?>
	                                <h3><?php echo esc_attr( get_theme_mod( 'subscribe_title' ) ); ?></h3>
	                            <?php endif; ?>
	                            
	                            <?php if( '' != get_theme_mod( 'subscribe_description' ) ) : ?>
	                                <p><?php echo esc_html( get_theme_mod( 'subscribe_description' ) ); ?></p>
	                            <?php endif; ?>
	                            
	                            <div class="row">
	                                <div class="col-sm-12">
	                                    <form action="<?php get_permalink(); ?>" method="post" class="clearfix">
	                                        <input type="text" name="stylish_name" placeholder="<?php _e('Your first name&#8230;', LANGUAGE_ZONE); ?>" required>
	                                        <input type="email" name="stylish_email" placeholder="<?php _e('Your email address&#8230;', LANGUAGE_ZONE); ?>" required>
	
	                                        <?php wp_nonce_field( 'subscribe-user_'.get_the_ID(), '_sth-subscribe' ); ?>
	                                        <input type="submit" value="<?php _e('Subscribe', LANGUAGE_ZONE); ?>">
	                                    </form>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
