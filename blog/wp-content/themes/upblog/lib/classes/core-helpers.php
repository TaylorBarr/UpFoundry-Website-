<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


class Core_Helpers {

    protected static $instance = null;

    private function __construct() {

        // Add something here.
        add_action('wp_head', array( &$this, 'setup_google_analytics' ));

        add_filter( 'wp_title', array( &$this, 'theme_name_wp_title'), 10, 2 );

        add_action ( 'publish_post', array( &$this, 'updateNumbers'), 11 );

        add_action ( 'deleted_post', array( &$this, 'updateNumbers') );

        add_action ( 'edit_post', array( &$this, 'updateNumbers') );

        add_filter( 'post_gallery', array($this, 'stylish_gallery_shortcode'), 15, 2 );

        add_filter( 'excerpt_length', array($this, 'new_excerpt_length') );

        //$this->setup_seo();

    }

    /**
     * Function to setup SEO things.
     */
    function setup_seo() {
        require_once(get_stylesheet_directory() . '/lib/includes/seo/seo-option.php');
    }

    /**
     * Decides if a post has sidebar
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function post_has_sidebar() {
        $prefix = STH_Meta_Boxes::get_instance()->prefix;
        $layout = rwmb_meta("{$prefix}post_layout");

        if($layout == 'sidebar') {
            return true;
        }

        return false;

    }

    /**
     * Return the single post pagination
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    public function get_single_post_pagination() {
        $output = wp_link_pages(
            array(
                'before'            => '<ul class="pager">',
                'after'             => '</ul>',
                'next_or_number'    =>'next',
                'previouspagelink'  => __(' &laquo; Previous Page',LANGUAGE_ZONE),
                'nextpagelink'      => __(' Next Page &raquo; ', LANGUAGE_ZONE),
                'separator'         => ' ', 'link_before' => '<li>',
                'link_after'        => '</li>',
                'echo'              => 0
            ));

        $output = str_replace('li></a>', 'a></li>', $output);
        $output = str_replace('<li>', '', $output);
        $output = str_replace('<a href', '<li><a href', $output);

        return $output;
    }

    public function unused_pagination() {
        posts_nav_link();
    }

    /**
     * Function to return the date and author in a nice format
     *
     * @since 1.0.1
     *
     * @param bool $date
     * @param bool $author
     * @return string
     */
    public function get_article_date_and_author($date = true, $author = true) {

        $output = '';

        if($date) {
            $output .= strtoupper(get_the_time( get_option( 'date_format' ) ));
        }

        if($author) {
            $output .= __(' by ', LANGUAGE_ZONE) . '<a href="'.get_the_permalink().'">' .  get_the_author() . '</a>';
        }

        return $output;
    }

    /**
     * Displays an intelligent page title
     *
     * @since 1.0.0
     *
     * @param string $before
     * @param string $after
     * @param string $before_title
     * @param string $after_title
     */
    public function the_page_title($before = '', $after = '', $before_title = '', $after_title = '') {
        ?>
            <?php echo $before; ?>
                <?php

                if (is_home()) :

                    bloginfo('name');

                elseif (is_page()) :

                    the_title();

                elseif (is_tag()) : ?>

                    <?php _e('Posts in ', LANGUAGE_ZONE);?> <?php echo $before_title; ?><?php echo esc_html(single_cat_title()); ?><?php echo $after_title; ?>

                <?php elseif (is_category()) : ?>

                    <?php _e('Posts in ', LANGUAGE_ZONE);?> <?php echo $before_title; ?><?php echo esc_html(single_cat_title()); ?><?php echo $after_title; ?>

                <?php elseif (is_search()) : ?>

                    <?php _e('Search results for ', LANGUAGE_ZONE);?> <?php echo $before_title; ?><?php echo esc_html(get_search_query()); ?><?php echo $after_title; ?>

                <?php
                endif;
                ?>
            <?php echo $after; ?>
        <?php
    }

    /**
     * Setup Google Analytics code in the header of the theme
     *
     * @since 1.0.0
     */
    public function setup_google_analytics() {

        global $clx_data;

        if(!empty($clx_data['jscode'])) {
            print_r(stripslashes($clx_data['jscode']));
        }

    }

    /**
     * @param $width
     * @param bool $front
     * @return bool|string
     */
    public function vc_get_the_columns_classes( $width, $front = true ) {
        if ( preg_match( '/^(\d{1,2})\/12$/', $width, $match ) ) {
            $w = 'col-lg-'.$match[1];
        } else {
            $w = 'col-lg-';
            switch ( $width ) {
                case "1/6" :
                    $w .= '2';
                    break;
                case "1/4" :
                    $w .= '3';
                    break;
                case "1/3" :
                    $w .= '4';
                    break;
                case "1/2" :
                    $w .= '6';
                    break;
                case "2/3" :
                    $w .= '8';
                    break;
                case "3/4" :
                    $w .= '9';
                    break;
                case "5/6" :
                    $w .= '10';
                    break;
                case "1/1" :
                    $w .= '12';
                    break;
                default :
                    $w = $width;
            }
        }
        $custom = $front ? get_custom_column_class( $w ) : false;
        return $custom ? $custom : $w;
    }

    /**
     * Function to echo the global social icons.
     *
     * @since 1.0.0
     */
    public function the_redux_social_icons($data) {
        ?>
        <nav class="social-icons">
            <ul>
                <?php if(isset($data)){
                    foreach($data['social'] as $icon) {
                        echo do_shortcode($icon);
                    }
                } ?>
            </ul>
        </nav>
    <?php
    }

    /**
     * Returns a HTML with a Google Map.
     *
     * @since 1.0.0
     *
     * @param $address
     * @param string $pointer
     * @param string $title
     * @param string $description
     * @param bool $visible
     * @return string
     */
    public function get_google_maps($address, $pointer = '', $title = '', $description = '', $visible = true) {

        $coordinates = $this->get_coordinates($address);

        if($visible) {
            $output = '<div id="map-canvas" class="google-map" data-lat="'.floatval($coordinates['lat']).'" data-long="'.floatval($coordinates['lng']).'" data-img="'.esc_url($pointer).'" data-title="'.sanitize_text_field($title).'" data-description="'.wp_kses_post($description).'"></div>';

            return $output;
        } else { return ''; }

    }

    /**
     * Function to get the coordinates from Google for a given address.
     *
     * @since 1.0.0
     *
     * @param $address
     * @param bool $force_refresh
     * @return array|mixed|string|void
     */
    public function get_coordinates($address, $force_refresh = false) {
        $address_hash = md5( $address );

        $coordinates = get_transient( $address_hash );

        if ($force_refresh || $coordinates === false) {

            $args       = array( 'address' => urlencode( $address ), 'sensor' => 'false' );
            $url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
            $response   = wp_remote_get( $url );

            if( is_wp_error( $response ) )
                return;

            $data = wp_remote_retrieve_body( $response );

            if( is_wp_error( $data ) )
                return;

            if ( $response['response']['code'] == 200 ) {

                $data = json_decode( $data );

                if ( $data->status === 'OK' ) {

                    $coordinates = $data->results[0]->geometry->location;

                    $cache_value['lat']   = $coordinates->lat;
                    $cache_value['lng']   = $coordinates->lng;
                    $cache_value['address'] = (string) $data->results[0]->formatted_address;

                    // cache coordinates for 3 months
                    set_transient($address_hash, $cache_value, 3600*24*30*3);
                    $data = $cache_value;

                } elseif ( $data->status === 'ZERO_RESULTS' ) {
                    return __( 'No location found for the entered address.', 'pw-maps' );
                } elseif( $data->status === 'INVALID_REQUEST' ) {
                    return __( 'Invalid request. Did you enter an address?', 'pw-maps' );
                } else {
                    return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'pw-maps' );
                }

            } else {
                return __( 'Unable to contact Google API service.', 'pw-maps' );
            }

        } else {
            // return cached results
            $data = $coordinates;
        }

        return $data;
    }

    public function get_entry_tags() {
        $tag_list = get_the_tags();
        if ( $tag_list ) {
            echo '<h6>' . __('tags: ', LANGUAGE_ZONE) . '</h6><ul class="clearfix">';

            $i = 0;
            $n = count( $tag_list );

            foreach($tag_list as $tag) {
                echo '<li><a href="' . home_url( '/' ) . '?tag=' . $tag->slug . '">' . $tag->name . '</a></li>';
                //if ($i != $n-1) echo ', ';
                $i++;
            }

            echo '</ul>';
        }
    }

    public function theme_name_wp_title( $title, $sep ) {
        if ( is_feed() ) {
            return $title;
        }

        global $page, $paged;

        // Add the blog name
        $title .= get_bloginfo( 'name', 'display' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title .= " $sep $site_description";
        }

        // Add a page number if necessary:
        if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
            $title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
        }

        return $title;
    }

    /**
     * Function to number the posts in a meta field.
     *
     * @since 1.0.0
     */
    public function updateNumbers() {
        global $wpdb;
        $querystr = "SELECT $wpdb->posts.* FROM $wpdb->posts
                 WHERE $wpdb->posts.post_status = 'publish'
                 AND $wpdb->posts.post_type = 'post'
                 ORDER BY $wpdb->posts.post_date DESC";
        $pageposts = $wpdb->get_results($querystr, OBJECT);
        $counts = 0 ;
        if ($pageposts):
            foreach ($pageposts as $post):
                $counts++;
                add_post_meta($post->ID, 'incr_number', $counts, true);
                update_post_meta($post->ID, 'incr_number', $counts);
            endforeach;
        endif;
    }

    /**
     * @param string $content
     * @param array $attr
     * @return mixed|string|void
     */
    public function stylish_gallery_shortcode( $content = '', $attr = array() ) {
        static $instance = 0; $style = $order = $orderby = $id = $itemtag = $icontag = $captiontag = $columns = $size = $include = $exclude = $link = '';

        // return if this is standard mode or gallery already modified
        if ( !empty($content) ) {
            return $content;
        }

        $instance++;

        $post = get_post();

        if ( ! empty( $attr['ids'] ) ) {
            // 'ids' is explicitly ordered, unless you specify otherwise.
            if ( empty( $attr['orderby'] ) )
                $attr['orderby'] = 'post__in';
            $attr['include'] = $attr['ids'];
        }

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if ( isset( $attr['orderby'] ) ) {
            $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
            if ( !$attr['orderby'] )
                unset( $attr['orderby'] );
        }

        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post ? $post->ID : 0,
            'itemtag'    => 'dl',
            'icontag'    => 'dt',
            'captiontag' => 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => '',
            'link'       => '',
            'style'      => ''
        ), $attr, 'gallery'));

        $id = intval($id);
        if ( 'RAND' == $order )
            $orderby = 'none';

        if ( !empty($include) ) {
            $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ( !empty($exclude) ) {
            $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        } else {
            $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        }

        if ( empty($attachments) )
            return '';

        if ( is_feed() ) {
            $output = "\n";
            foreach ( $attachments as $att_id => $attachment )
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $itemtag = tag_escape($itemtag);
        $captiontag = tag_escape($captiontag);
        $icontag = tag_escape($icontag);
        $valid_tags = wp_kses_allowed_html( 'post' );
        if ( ! isset( $valid_tags[ $itemtag ] ) )
            $itemtag = 'dl';
        if ( ! isset( $valid_tags[ $captiontag ] ) )
            $captiontag = 'dd';
        if ( ! isset( $valid_tags[ $icontag ] ) )
            $icontag = 'dt';

        $columns = intval($columns);
        $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
        $float = is_rtl() ? 'right' : 'left';

        $selector = "gallery-{$instance}";

        $gallery_style = $gallery_div = '';

        switch ( $style ) {

            default :

                if ( apply_filters( 'use_default_gallery_style', true ) )
                    $gallery_style = "
                    <style type='text/css'>
                        #{$selector} {
                            margin: auto;
                        }
                        #{$selector} .gallery-item {
                            float: {$float};
                            margin-top: 10px;
                            text-align: center;
                            width: {$itemwidth}%;
                        }
                        #{$selector} .gallery-caption {
                            margin-left: 0;
                        }

                        #{$selector} dt {
                            margin: 0px 10px !important;
                        }
                        /* see gallery_shortcode() in wp-includes/media.php */
                    </style>";
                $size_class = sanitize_html_class( $size );
                $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
                $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

                $i = 0;
                foreach ( $attachments as $id => $attachment ) {

                    $img_html = wp_get_attachment_image( $id, 'thumbnail', false );

                    $img_link = wp_get_attachment_url( $id );
                    $img_video_url = get_post_meta( $id, 'zen_video_url', true );

                    if ( $img_video_url != '' ) {
                        $image_output = '<a href="'.$img_video_url.'" rel="prettyPhoto[pp_gal]" alt="">'.$img_html.'</a>';
                    } else {
                        $image_output = '<a href="'.$img_link.'" rel="prettyPhoto[pp_gal]" alt="">'.$img_html.'</a>';
                    }

                    $image_meta  = wp_get_attachment_metadata( $id );

                    $orientation = '';
                    if ( isset( $image_meta['height'], $image_meta['width'] ) )
                        $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

                    $output .= "<{$itemtag} class='gallery-item'>";
                    $output .= "
                        <{$icontag} class='gallery-icon {$orientation}'>
                            $image_output
                        </{$icontag}>";
                    $output .= "</{$itemtag}>";
                    if ( $columns > 0 && ++$i % $columns == 0 )
                        $output .= '<br style="clear: both" />';
                }

                $output .= "
                        <br style='clear: both;' />
                    </div>\n";
        }

        return $output;
    }

    /**
     * A function to limit the length of the default excerpt of wordpress.
     *
     * @param $length
     * @return int
     */
    public function new_excerpt_length($length) {
        return 23;
    }

    /**
     * A custom function which returns the related posts ids for a given post.
     *
     * @param $post_id
     * @param int $nr
     * @return array
     */
    public function get_related_posts_ids($post_id, $nr = 3) {
        $ids = array(); $tags_q = array();

        $tags = wp_get_post_tags($post_id);
        if ($tags) {

            foreach ($tags as $tag) {
                $tags_q[] = $tag->term_id;
            }

            $args = array(
                'tag__in' => $tags_q,
                'post__not_in' => array($post_id),
                'posts_per_page'=> $nr,
                'ignore_sticky_posts'=>true,
                'orderby'=> 'rand'
            );

            $query = new WP_Query($args);

            if( $query->have_posts() ) {
                while ($query->have_posts()) : $query->the_post();

                    $ids[] = get_the_ID();

                endwhile;
            }
            wp_reset_query();
        }

        return $ids;
    }


    /**
     * @return Core_Helpers|null
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
Core_Helpers::get_instance();