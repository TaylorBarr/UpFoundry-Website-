<?php

function stylish_default_theme_settings() {
	return array(
		'site_logo_light' => '',
		'site_logo_dark' => '',
		'subscribe_cta' => __( 'Free Gift', 'stylish' ),
		'show_subscribe_form_in_header' => true,
		'show_subscribe_form_in_page_footer' => true,
		'subscribe_image' => get_template_directory_uri() . '/assets/img/content/boock-image.jpg',
        'mailchimp_api' => '',
        'mailchimp_list_id' => '',
        'footer_copyright' => '&copy; Copyright <a href="#">StylishThemes</a>',
		'subscribe_title' => __( 'Free Ebook &ldquo;Just Enough Research&rdquo;', 'stylish' ),
		'subscribe_description' => __( 'Download Free &ldquo;Just Enough Research&rdquo; &ndash; An open Internet is essential to the American economy, increasingly to our very way of lifel aunching.', 'stylish' ),
		'headings_font'     => 'Open Sans Condensed',
		'text_font'         => 'Merriweather',
		'accent_font'       => 'Open Sans',
		'headings_color'    => '#373737',
		'text_color'        => '#737373',
		'link_color'        => '#373737',
		'accent_color'      => '#C8C8C8',
	);
}

function stylish_set_default_theme_settings( $mod ) {
	global $stylish_mods;
	
	// Cache theme mods array
	if( ! isset( $stylish_mods ) ) {
		$stylish_mods = get_theme_mods();
	}
	
	$defaults = stylish_default_theme_settings();
	$name = str_replace( 'theme_mod_', '', current_filter() );
	
	// No value in database, retrieve from default array
	if( ! isset( $stylish_mods[$name] ) ) {
		$mod = $defaults[$name];
	}
	
	return $mod;
}

function stylish_set_default_theme_settings_filter() {
	$mods = stylish_default_theme_settings();
	foreach( $mods as $mod => $value ) {
		add_filter( "theme_mod_{$mod}", 'stylish_set_default_theme_settings' );
	}
}
add_action( 'init', 'stylish_set_default_theme_settings_filter' );

function stylish_customize_controls( $wp_customize ) {

	class Stylish_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
	 
		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
	        <?php
	    }
	}
}
add_action( 'customize_register', 'stylish_customize_controls', 0 );

function stylish_customize_register( $wp_customize ) {
	$defaults = stylish_default_theme_settings();
	$type     = 'theme_mod';
	$cap      = 'edit_theme_options';
	$fonts    = stylish_custom_fonts();
	$choices  = array();
	
	foreach( $fonts as $font => $args ) {
		$args['family'] = str_replace( '"', '', $args['family'] );
		$choices[ $font ] = sprintf( '%1$s, %2$s', $args['family'], $args['category'] );
	}
	
	$wp_customize->add_setting( 'site_logo_light', array(
		'default'           => $defaults['site_logo_light'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_setting( 'site_logo_dark', array(
		'default'           => $defaults['site_logo_dark'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_setting( 'subscribe_cta', array(
		'default'           => $defaults['subscribe_cta'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_setting( 'show_subscribe_form_in_header', array(
		'default'           => $defaults['show_subscribe_form_in_header'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'stylish_sanitize_checkbox',
	) );
	$wp_customize->add_setting( 'show_subscribe_form_in_page_footer', array(
		'default'           => $defaults['show_subscribe_form_in_page_footer'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'stylish_sanitize_checkbox',
	) );
	$wp_customize->add_setting( 'subscribe_image', array(
		'default'           => $defaults['subscribe_image'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_setting( 'subscribe_title', array(
		'default'           => $defaults['subscribe_title'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_setting( 'subscribe_description', array(
		'default'           => $defaults['subscribe_description'],
		'type'              => $type,
		'capability'        => $cap,
		'sanitize_callback' => 'sanitize_text_field',
	) );
    $wp_customize->add_setting( 'mailchimp_api', array(
        'default'           => $defaults['mailchimp_api'],
        'type'              => $type,
        'capability'        => $cap,
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_setting( 'mailchimp_list_id', array(
        'default'           => $defaults['mailchimp_list_id'],
        'type'              => $type,
        'capability'        => $cap,
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_setting( 'subscribe_thankyou_page', array(
        'type'              => $type,
        'capability'        => $cap,
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_setting( 'footer_copyright', array(
        'default'           => $defaults['footer_copyright'],
        'type'              => $type,
        'capability'        => $cap,
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_setting( 'headings_font', array(
    	'default'           => $defaults['headings_font'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'stylish_sanitize_font',
    ) );
    $wp_customize->add_setting( 'text_font', array(
    	'default'           => $defaults['text_font'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'stylish_sanitize_font',
    ) );
    $wp_customize->add_setting( 'accent_font', array(
    	'default'           => $defaults['accent_font'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'stylish_sanitize_font',
    ) );
    $wp_customize->add_setting( 'headings_color', array(
    	'default'           => $defaults['headings_color'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_setting( 'text_color', array(
    	'default'           => $defaults['text_color'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_setting( 'link_color', array(
    	'default'           => $defaults['link_color'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_setting( 'accent_color', array(
    	'default'           => $defaults['accent_color'],
    	'type'              => $type,
    	'capability'        => $cap,
    	'sanitize_callback' => 'sanitize_hex_color',
    ) );
	
	$wp_customize->add_section( 'header_options', array(
		'title'          => __( 'Header Options', 'stylish' ),
		'priority'       => 35,
	) );
    $wp_customize->add_section( 'footer_options', array(
        'title'          => __( 'Footer Options', 'stylish' ),
        'priority'       => 35,
    ) );
	$wp_customize->add_section( 'subscribe_form', array(
		'title'          => __( 'Subscribe Form', 'stylish' ),
		'priority'       => 35,
	) );
	$wp_customize->add_section( 'font_options', array(
		'title'          => __( 'Fonts', 'stylish' ),
		'priority'       => 35,
	) );
	
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'site_logo_light', array(
		'label'      => __( 'Light Logo', 'stylish' ),
		'section'    => 'header_options',
		'settings'   => 'site_logo_light',
	) ) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'site_logo_dark', array(
		'label'      => __( 'Dark Logo', 'stylish' ),
		'section'    => 'header_options',
		'settings'   => 'site_logo_dark',
	) ) );
	$wp_customize->add_control( 'subscribe_cta', array(
		'label'      => __( 'Subscribe Form Call to Action', 'stylish' ),
		'section'    => 'header_options',
		'settings'   => 'subscribe_cta',
	) );
	$wp_customize->add_control( 'show_subscribe_form_in_header', array(
		'label'      => __( 'Show Subscribe Form in Header', 'stylish' ),
		'section'    => 'subscribe_form',
		'type'       => 'checkbox',
		'settings'   => 'show_subscribe_form_in_header',
	) );
	$wp_customize->add_control( 'show_subscribe_form_in_page_footer', array(
		'label'      => __( 'Show Subscribe Form in Page Footer', 'stylish' ),
		'section'    => 'subscribe_form',
		'type'       => 'checkbox',
		'settings'   => 'show_subscribe_form_in_page_footer',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'subscribe_image', array(
		'label'      => __( 'Subscribe Form Image', 'stylish' ),
		'section'    => 'subscribe_form',
		'settings'   => 'subscribe_image',
	) ) );
	$wp_customize->add_control( 'subscribe_title', array(
		'label'      => __( 'Subscribe Form Title', 'stylish' ),
		'section'    => 'subscribe_form',
		'settings'   => 'subscribe_title',
	) );
	$wp_customize->add_control( new Stylish_Customize_Textarea_Control( $wp_customize, 'subscribe_description', array(
		'label'      => __( 'Subscribe Form Description', 'stylish' ),
		'section'    => 'subscribe_form',
		'settings'   => 'subscribe_description',
	) ) );
    $wp_customize->add_control( 'mailchimp_api', array(
        'label'      => __( 'Mailchimp API Key', 'stylish' ),
        'section'    => 'subscribe_form',
        'settings'   => 'mailchimp_api',
    ) );
    $wp_customize->add_control( 'mailchimp_list_id', array(
        'label'      => __( 'Mailchimp List ID', 'stylish' ),
        'section'    => 'subscribe_form',
        'settings'   => 'mailchimp_list_id',
    ) );
    $wp_customize->add_control( 'subscribe_thankyou_page', array(
        'type'       => 'dropdown-pages',
        'label'      => __( 'Thank You Page', 'stylish' ),
        'section'    => 'subscribe_form',
        'settings'   => 'subscribe_thankyou_page',
    ) );
    $wp_customize->add_control( 'footer_copyright', array(
        'label'      => __( 'Copyrights', 'stylish' ),
        'section'    => 'footer_options',
        'settings'   => 'footer_copyright',
    ) );
    $wp_customize->add_control( 'headings_font', array(
    	'label'      => __( 'Headings Font', 'stylish' ),
    	'section'    => 'font_options',
    	'type'       => 'select',
    	'choices'    => $choices,
    	'settings'   => 'headings_font',
    ) );
    $wp_customize->add_control( 'text_font', array(
    	'label'      => __( 'Text Font', 'stylish' ),
    	'section'    => 'font_options',
    	'type'       => 'select',
    	'choices'    => $choices,
    	'settings'   => 'text_font',
    ) );
    $wp_customize->add_control( 'accent_font', array(
    	'label'      => __( 'Accent Font', 'stylish' ),
    	'section'    => 'font_options',
    	'type'       => 'select',
    	'choices'    => $choices,
    	'settings'   => 'accent_font',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'headings_color', array(
    	'label'      => __( 'Headings Color', 'stylish' ),
    	'section'    => 'colors',
    	'settings'   => 'headings_color',
    ) ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
    	'label'      => __( 'Text Color', 'stylish' ),
    	'section'    => 'colors',
    	'settings'   => 'text_color',
    ) ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
    	'label'      => __( 'Link Color', 'stylish' ),
    	'section'    => 'colors',
    	'settings'   => 'link_color',
    ) ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
    	'label'      => __( 'Accent Color', 'stylish' ),
    	'section'    => 'colors',
    	'settings'   => 'accent_color',
    ) ) );
}
add_action( 'customize_register', 'stylish_customize_register' );

function stylish_sanitize_checkbox( $input ) {
	return (bool) $input;
}

/**
 * Sanitize font inputs.
 */
function stylish_sanitize_font( $input ) {
	if( ! array_key_exists( $input, stylish_custom_fonts() ) ) {
		return 'Merriweather';
	}
	
	return $input;
}