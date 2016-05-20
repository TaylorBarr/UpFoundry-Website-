<?php
/**
 * @author Vlad Mustiata
 * @team StylishThemes
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$post_class = '';
$prefix = STH_Meta_Boxes::get_instance()->prefix;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
    <figure>

        <div class="article-details">

            <h2 style="text-align: center;">
                <?php _e('Sorry, we can\'t find what you\'re looking for.', LANGUAGE_ZONE) ?>
            </h2>

        </div>

    </figure>
</article>