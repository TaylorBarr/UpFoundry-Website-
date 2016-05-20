<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if footer option is not enabled
if( ! get_theme_mod( 'show_subscribe_form_in_page_footer' ) ) {
	return;
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="subscribe" id="subscribe">
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
            
            <form action="<?php get_permalink(); ?>" method="post" class="clearfix">
                <input type="text" name="stylish_name" placeholder="<?php _e('Your first name&#8230;', LANGUAGE_ZONE); ?>" required>
                <input type="email" name="stylish_email" placeholder="<?php _e('Your email address&#8230;', LANGUAGE_ZONE); ?>" required>

                <?php wp_nonce_field( 'subscribe-user_'.get_the_ID(), '_sth-subscribe' ); ?>
                <input type="submit" value="<?php _e('Subscribe', LANGUAGE_ZONE); ?>">
            </form>
        </div>
    </div>
</div>