<?php global $post; ?>

<?php if(is_single()) : ?>

    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo( "name" )); ?>"/>
    <meta property="og:url" content="<?php echo esc_url(get_the_permalink()); ?>"/>
    <meta property="og:title" content="<?php the_title(); ?>"/>

    <meta property="og:type" content="article"/>
    <meta property="og:description" content="<?php echo trim( strip_tags( get_the_excerpt() ) ) ?>"/>

    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ); ?>
    <meta property="og:image" content="<?php echo esc_url($image[0]); ?>"/>

<?php endif; ?>