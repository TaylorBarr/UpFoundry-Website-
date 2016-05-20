<?php

$comm_closed = true;

/***********************************************************************************************/
/* Prevent the direct loading of comments.php */
/***********************************************************************************************/
if (!empty($_SERVER['SCRIPT-FILENAME']) && basename($_SERVER['SCRIPT-FILENAME']) == 'comments.php') {
    die(__('You cannot access this page directly.', LANGUAGE_ZONE));
}

/***********************************************************************************************/
/* If the post is password protected then display text and return */
/***********************************************************************************************/
if (post_password_required()) : ?>

        <p class="comments-password">
            <?php
            _e( 'This post is password protected. Enter the password to view the comments.', LANGUAGE_ZONE);
            return;
            ?>
        </p>

<?php endif;

/***********************************************************************************************/
/* If we have comments to display, we display them */
/***********************************************************************************************/
if (comments_open()) {$comm_closed = false;}
if (have_comments()) : ?>

        <!-- ============== COMMENTS CONTAINER ============= -->
        <div class="comment-container" id="comment-container">


            <h1 class="title-comments">
                <?php comments_number(__('No Comments', LANGUAGE_ZONE), __('1 Comment', LANGUAGE_ZONE), __('% Comments', LANGUAGE_ZONE)); ?>
            </h1>

            <ul class="comments">
                <?php wp_list_comments(array('callback' => array( IMBT_Core::get_instance(), 'display_comments' ))); ?>
            </ul>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>

                <div class="comment-nav-section clearfix">

                    <p class="fl"><?php previous_comments_link(__( '&larr; Older Comments', LANGUAGE_ZONE)); ?></p>
                    <p class="fr"><?php next_comments_link(__( 'Newer Comments &rarr;', LANGUAGE_ZONE)); ?></p>

                </div> <!-- end comment-nav-section -->

            <?php endif; ?>


        </div>

<?php
/***********************************************************************************************/
/* If we don't have comments and the comments are closed, display a text */
/***********************************************************************************************/

elseif (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>

<?php endif;

/***********************************************************************************************/
/* Display the comment form */
/***********************************************************************************************/
?>

    <!-- ============== COMMENT RESPOND ============= -->
<?php if (!$comm_closed) : ?>
    <div class="contact-form-imbt" id="comment-form">
        <div class="comment-respond">

            <?php
            comment_form();
            ?>

        </div>
    </div>
<?php endif; ?>

<?php

?>