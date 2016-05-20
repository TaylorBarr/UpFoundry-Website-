<?php
/**
 * @author Stylish Themes
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


class IMBT_Core {

    protected static $instance = null;

    /**
     * The Mailchimp API key.
     *
     * @since	1.0.0
     * @var		string
     */
    private $mailchimp_apikey;

    /**
     * The Mailchimp list ID.
     *
     * @since	1.0.0
     * @var		string
     */
    private $mailchimp_list_id;


    private function __construct() {

        $this->require_plugins();

        add_action('wp_enqueue_scripts', array( &$this, 'load_custom_scripts' ));
        add_action('wp_enqueue_scripts', array( &$this, 'load_custom_styles' ));

        add_filter('comment_form_defaults', array( &$this, 'custom_comment_form' ));
        add_filter('comment_form_default_fields', array( &$this, 'custom_comment_fields' ));

        add_action('init', array( &$this, 'setup_theme_menus' ));

        add_action('widgets_init', array( &$this, 'setup_theme_sidebars' ));

        $this->setup_shortcodes();

        add_action( 'vc_before_init', array( &$this, 'setup_visual_composer' ) );

        add_action( 'stylish_before_subscribe', array( &$this, 'wait_for_subscribe' ) );

        $this->include_other_classes();

        $this->setup_metaboxes();

        add_action('wp_head', array( Core_Helpers::get_instance(), 'setup_google_analytics' ));

        add_action('imbt_after_posts_loop', array( &$this, 'blog_pagination' ), 10, 3);

        // Init the mailchimp API and list ID.
        if( '' != get_theme_mod( 'mailchimp_list_id' ) ) {
            $this->mailchimp_list_id = esc_attr(get_theme_mod( 'mailchimp_list_id' ));
        }

        if( '' != get_theme_mod( 'mailchimp_api' ) ) {
            $this->mailchimp_apikey = esc_attr(get_theme_mod( 'mailchimp_api' ));
        }

    }

    /**
     * Function to return the subscribe and sidebar widget with conditions for each one.
     *
     * @param bool $subscribe
     * @param bool $sidebar
     */
    public function the_subscribe_and_sidebar($subscribe = true, $sidebar = true) {
        ?>

        <div class="page-footer-top">
            <div class="container">

                <?php if( $subscribe ) : ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="subscribe" id="subscribe">
                                <div class="gift-feature-image">
                                    <img src="<?php echo THEMEROOT; ?>/assets/img/content/boock-image.jpg" alt="">
                                </div>
                                <h3>
                                    free ebook "Just Enough Research"
                                </h3>
                                <p>
                                    Download Free "Just Enough Research" - An open Internet is essential to the American economy, increasingly to our very way of lifel aunching.
                                </p>
                                <form action="#" method="post" class="clearfix">
                                    <input type="text" placeholder="Your name..." required>
                                    <input type="email" placeholder="Your email adress..." required>
                                    <input type="submit" placeholder="send it">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( $subscribe && $sidebar ) : ?>
                    <hr>
                <?php endif; ?>

                <?php if( $sidebar ) : ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="widgets-area clearfix">
                                <div class="row">

                                    <?php get_sidebar('main-sidebar'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <?php
    }

    /**
     * Outputs an widget with the post author info.
     *
     * @since 1.0.0
     */
    public function the_author_widget() {
        ?>

        <figure class="author-info">
            <figcaption>
                <?php echo wp_kses_post(get_avatar(get_the_author_meta('ID'), 120)); ?>
            </figcaption>
            <h1>
                <?php the_author_meta('display_name'); ?>
            </h1>
            <p>
                <?php the_author_meta('description'); ?>
            </p>
            <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>" class="btn btn-default btn-sm"><?php _e('read more', LANGUAGE_ZONE); ?></a>
        </figure>

        <?php
    }

    /**
     * Adds pagination to the blog pages.
     *
     * @since 1.0.0
     *
     * @param $query
     * @param string $pages
     * @param int $range
     */
    public function blog_pagination( $query, $pages = '', $range = 2 ) {
        ?>
        <!-- ========== START PAGINATION ========== -->
        <?php
        $showitems = ($range * 2)+1;

        global $paged;
        if(empty($paged)) $paged = 1;

        if($pages == '')
        {
            $pages = $query->max_num_pages;
            if(!$pages)
            {
                $pages = 1;
            }
        }

        if(1 != $pages)
        {
            echo '<div class="row"><div class="col-sm-12"><ul class="more-posts-button">';
            if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a class='btn btn-default btn-sm' href='".get_pagenum_link(1)."'>&laquo;</a></li>";
            if($paged > 1 && $showitems < $pages) echo "<li><a class='btn btn-default btn-sm' href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";

            for ($i=1; $i <= $pages; $i++)
            {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                {
                    echo ($paged == $i)? "<li class='active'><a class='btn btn-default btn-sm' href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."' class='btn btn-default btn-sm inactive' >".$i."</a></li>";
                }
            }

            if ($paged < $pages && $showitems < $pages) echo "<li><a class='btn btn-default btn-sm' href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>";
            if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a class='btn btn-default btn-sm' href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
            echo "</ul></div></div>";
        }
        ?>
        <!-- ========== END PAGINATION ========== -->
    <?php
    }

    /**
     * Setup the themes Post Types
     *
     * @since 1.0.0
     */
    private function setup_theme_post_types() {

        //require_once(get_stylesheet_directory() . '/lib/includes/posttypes/sth_portfolio.php');

    }

    /**
     * Setup and register the theme menus
     *
     * @since 1.0.0
     */
    public function setup_theme_menus() {
        register_nav_menus(
            array(
                'main-menu' => __('Main Menu', LANGUAGE_ZONE_ADMIN),
                'social'    => __('Social', LANGUAGE_ZONE_ADMIN)
            )
        );
    }

    /**
     * Setup and register the theme sidebars
     *
     * @since 1.0.0
     */
    public function setup_theme_sidebars() {
        if (function_exists('register_sidebar')) {

            register_sidebar(
                array(
                    'name' => __( 'Main Sidebar', LANGUAGE_ZONE_ADMIN ),
                    'id' => 'main-sidebar',
                    'description' => __( 'Appears on posts and pages, which has its own widgets', LANGUAGE_ZONE_ADMIN ),
                    'before_widget' => '<div class="col-sm-4"><div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div></div>',
                    'before_title' => '<h3>',
                    'after_title' => '</h3>'
                )
            );

        }
    }

    /**
     * The callback function to display all comments
     *
     * @since 1.0.0
     *
     * @param $comment
     * @param $args
     * @param $depth
     */
    public function display_comments($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        if (get_comment_type() == 'pingback' || get_comment_type() == 'trackback') : ?>

            <li id="comment-<?php comment_ID(); ?>" >

            <div <?php comment_class(); ?>>

                <div class="left-section">
                </div>

                <div class="right-section">

                    <h1>

                        <a><?php _e('Pingback:', LANGUAGE_ZONE); ?></a>

                    </h1>

                    <p class="time-comment"><?php comment_date(); ?> at <?php comment_time(); ?> </p>

                    <div class="clear"></div>

                    <div class="comment-text">

                        <p><?php comment_author_link(); ?></p>

                    </div>

                </div>

                <div class="clear"></div>

            </div>

        <?php endif; ?>

        <?php if (get_comment_type() == 'comment') : ?>
        <li id="comment-<?php comment_ID(); ?>" >

            <div <?php comment_class(); ?>>

                <div class="left-section">
                    <?php
                    $avatar_size = 60;

                    echo wp_kses_post(get_avatar($comment, $avatar_size));
                    ?>
                </div>

                <div class="right-section">

                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text'=>'<i class="fa fa-reply"></i> '. __('Reply', LANGUAGE_ZONE)))); ?>


                    <h1>

                        <a><?php comment_author_link(); ?></a>

                    </h1>

                    <p class="time-comment"><?php comment_date(); ?> at <?php comment_time(); ?> </p>

                    <div class="clear"></div>

                    <div class="comment-text">

                        <?php if ($comment->comment_approved == '0') : ?>

                            <p class="awaiting-moderation"><?php _e('<i>Your comment is awaiting moderation.</i>', LANGUAGE_ZONE); ?></p>

                        <?php endif; ?>

                        <?php comment_text(); ?>

                    </div>

                </div>

                <div class="clear"></div>

            </div>

        <?php endif;
    }

    /**
     * Setup the custom form for comments
     *
     * @since 1.0.0
     *
     * @param $defaults
     * @return mixed
     */
    public function custom_comment_form($defaults) {
        $defaults['comment_notes_before'] = '';
        $defaults['comment_notes_after'] = '';
        $defaults['id_form'] = 'comment-form';
        $defaults['title_reply'] = __('LEAVE A REPLY', LANGUAGE_ZONE);
        $defaults['comment_field'] = '<textarea name="comment" id="comment" placeholder="'. __('Message *', LANGUAGE_ZONE) .'" rows="7"></textarea>';

        return $defaults;
    }

    /**
     * Setup the custom fields for comments
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function custom_comment_fields() {
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');

        $fields = array(
            'author' => '<ul class="comment-form-inputs"><li>' .
                '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" ' . $aria_req .
                ' placeholder="' . __('Name ', LANGUAGE_ZONE) . ($req ? __('*', LANGUAGE_ZONE) : '') . '" /></li>',
            'email' => '<li>' .
                '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" ' . $aria_req .
                ' placeholder="' . __('Email ', LANGUAGE_ZONE) . ($req ? __('*', LANGUAGE_ZONE) : '') . '"/>' .
                '</li>',
            'url' => '<li>' .
                '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) .
                '" placeholder="' . __('Website ', LANGUAGE_ZONE) . '" />' .
                '</li></ul>'
        );

        return $fields;
    }

    /**
     * Setup Visual Composer plugin for the theme
     *
     * @since 1.0.0
     */
    public function setup_visual_composer() {

        // Disable VC frontend editor
        if(function_exists('vc_disable_frontend')) {
            //vc_disable_frontend();
        }

        // Set the vc_path to the new folder
        if ( function_exists('vc_set_shortcodes_templates_dir') ) {
            $dir = THEMEDIR . '/lib/includes/vc_templates/';
            vc_set_shortcodes_templates_dir($dir);
        }
        if(function_exists('vc_set_as_theme')) vc_set_as_theme(true);

        require_once(THEMEDIR . '/lib/classes/visual-composer.php');

    }


    /**
     * Setup shortcodes
     */
    private function setup_shortcodes() {

        require_once(get_stylesheet_directory() . '/lib/includes/shortcodes/fullwidth-image-quote.php');
        require_once(get_stylesheet_directory() . '/lib/includes/shortcodes/book.php');
        require_once(get_stylesheet_directory() . '/lib/includes/shortcodes/button.php');
        require_once(get_stylesheet_directory() . '/lib/includes/shortcodes/randomtext.php');

    }

    /**
     * Loads the custom styles using 'wp_enqueue_script' function
     *
     * @since   1.0.0
     */
    public function load_custom_scripts() {

        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrapJS', THEMEROOT . '/assets/js/bootstrap.min.js', array(), '1.0', true);
        wp_enqueue_script('imbtPlugins', THEMEROOT . '/assets/js/plugins.js', array(), '1.0', true);
        wp_enqueue_script('imbtMainJS', THEMEROOT . '/assets/js/main.js', array(), '1.0', true);

        if(preg_match('/(?i)msie [9]/',$_SERVER['HTTP_USER_AGENT'])){
            wp_enqueue_script('placeholder', THEMEROOT . '/assets/js/placeholder.js', array(), '1.0', true);
            wp_enqueue_script('placeholderJS', THEMEROOT . '/lib/assets/js/placeholderJS.js', array(), '1.0', true);
        }

        wp_register_script('google-maps-api', 'http://maps.google.com/maps/api/js?sensor=true', array(), '1.0', true);
        wp_register_script('mapJS', THEMEROOT . '/lib/assets/js/map.js', array(), '1.0', true);

        if(is_single()) {
            wp_enqueue_script('share-count', THEMEROOT . '/lib/assets/js/single-share-count.js', array(), '1.0', true);
        }

    }

    /**
     * Loads the custom styles using 'wp_enqueue_style' function
     *
     * @since   1.0.0
     */
    public function load_custom_styles() {
		$deps = array();
		$web_fonts_style = stylish_web_fonts_style();
		if( false !== $web_fonts_style ) {
			$deps[] = 'google-fonts';
		}
		wp_enqueue_style( 'google-fonts', $web_fonts_style, false, null );
		
		wp_enqueue_style( 'master', THEMEROOT . '/assets/css/master.css', $deps, null );
		
		$custom_css = apply_filters( 'stylish_custom_css', '' );
		if( ! empty( $custom_css ) ) {
			/* Print Custom CSS after default Stylesheet */
			wp_add_inline_style( 'master', $custom_css );
		}
		
        wp_enqueue_style( 'style', get_stylesheet_uri());

    }

    /**
     * Include other classes files, required for the theme
     *
     * @since   1.0.0
     */
    private function include_other_classes() {

        // Crucial classes for the theme core
        require_once(THEMEDIR . '/lib/classes/core-helpers.php');

        // Custom Typography
        require_once(THEMEDIR . '/lib/includes/others/custom-fonts.php');
        
        // Custom Typography
        require_once(THEMEDIR . '/lib/includes/others/custom-css.php');

        require_once(THEMEDIR . '/admin/importer/import.php');

        if(!class_exists('MailChimp')) {
            require_once(THEMEDIR . '/admin/tcm-theme-lock/includes/class-mailchimp-api.php');
        }
        
        // Theme Customizer
        require_once(THEMEDIR . '/admin/customizer/customizer.php');

    }

    /**
     * Setup metaboxes
     *
     * @since 1.0.0
     */
    private function setup_metaboxes() {

        define( 'RWMB_URL', trailingslashit( get_stylesheet_directory_uri() . '/admin/metaboxes/meta-box' ) );
        define( 'RWMB_DIR', trailingslashit( get_stylesheet_directory() . '/admin/metaboxes/meta-box' ) );
        define( 'TPMBGF_URL', trailingslashit( get_stylesheet_directory_uri() . '/admin/metaboxes/meta-box-group-field' ) );
        define( 'TPMBGF_DIR', trailingslashit( get_stylesheet_directory() . '/admin/metaboxes/meta-box-group-field' ) );

        require_once(RWMB_DIR . 'meta-box.php');
        require_once(get_stylesheet_directory() . '/admin/metaboxes/meta-box-show-hide/meta-box-show-hide.php');
        require_once(get_stylesheet_directory() . '/admin/metaboxes/meta-box-group-field/meta-box-group-field.php');
        require_once(get_stylesheet_directory() . '/admin/metaboxes/meta-box-tabs/meta-box-tabs.php');

        require_once(get_stylesheet_directory() . '/lib/classes/sth-metaboxes.php');

    }

    /**
     * Function to require plugins with TGM Activator
     *
     * @since 1.0.0
     */
    private function require_plugins() {

        require_once(get_stylesheet_directory() . '/admin/require-plugins/tgm/tgm-init.php');

    }


    public function wait_for_subscribe($ID) {

        if(isset($_REQUEST['_sth-subscribe']) && wp_verify_nonce( $_REQUEST['_sth-subscribe'], 'subscribe-user_'.$ID )) {

            $email = sanitize_email($_REQUEST['stylish_email']);
            $name = sanitize_text_field($_REQUEST['stylish_name']);

            if($email && $name) {

                if($this->subscribe_to_mailchimp($email, $name) && get_theme_mod( 'subscribe_thankyou_page' ) != '') {

                    header( 'Location: ' . get_permalink( intval(get_theme_mod( 'subscribe_thankyou_page' )) ));

                }

            }

        }

    }

    /**
     * Subscribe the given email address to our Mailchimp List.
     *
     * @since 1.0.0
     * @param $email_address
     * @return array
     */
    private function subscribe_to_mailchimp($email_address, $name = '') {

        $MailChimp = new \Drewm\MailChimp($this->mailchimp_apikey);

        $result = $MailChimp->call('lists/subscribe', array(
            'id'                => $this->mailchimp_list_id,
            'email'             => array('email' => $email_address),
            'merge_vars'        => $name != '' ? array('FNAME'=> $name) : array(),
            'double_optin'      => false,
            'update_existing'   => true,
            'replace_interests' => false,
            'send_welcome'      => false,
        ));

        return $result;

    }

    public function download_as_PDF() {
        ?>
        <div class="download-button">
            <i class="fa fa-file-pdf-o"></i>
            <span>Download As PDF</span>
            <div class="download-box">
                <h3>
                    download as pdf
                </h3>
                <p>
                    Add your email adress to send you this article in PDF format
                </p>
                <form action="#" method="post">
                    <input type="email" placeholder="Your nickname" required>
                    <input type="submit" value="Send">
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * @return IMBT_Core|null
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
IMBT_Core::get_instance();