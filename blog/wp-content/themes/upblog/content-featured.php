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

        <figcaption>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('blog_image'); ?>
            </a>

            <?php switch(get_post_format()) {
                case 'video':
                    echo '<i class="fa fa-play"></i>';
                    break;
                case 'audio':
                    echo '<i class="fa fa-play"></i>';
                    break;
                case 'image':
                    echo '<i class="fa fa-photo"></i>';
                    break;
                case 'gallery':
                    echo '<i class="fa fa-photo"></i>';
                    break;
                default:
                    echo '<i class="fa fa-pencil"></i>';
            } ?>
        </figcaption>

        <div class="article-details">

            <h2>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <?php the_excerpt(); ?>

            <div class="article-additional-details">
                <?php $read_info = rwmb_meta("{$prefix}read_info"); ?>
                <?php if($read_info != ''): ?>
                    <span>
                        <i class="fa fa-bookmark-o"></i>
                        <?php echo esc_html($read_info); ?>
                    </span>
                <?php endif; ?>
                <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">
                    <i class="fa fa-user"></i>
                    <?php
                    $post_author_id = get_post_field( 'post_author', get_the_ID() );
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
                    <i class="fa fa-clock-o"></i>
                    <?php echo esc_html(get_the_date()); ?>
                </span>
                <span>
                    <i class="fa fa-folder-open-o"></i>
                    <?php the_category(' '); ?>
                </span>
            </div>

        </div>

    </figure>
</article>