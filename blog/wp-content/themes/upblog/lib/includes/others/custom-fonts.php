<?php

function stylish_custom_fonts() {
	$fonts = array(
		'Helvetica Neue' => array(
			'category' => 'sans-serif',
			'family' => '"Helvetica Neue", Helvetica, "Nimbus Sans L"',
		),
		'Geneva' => array(
			'category' => 'sans-serif',
			'family' => 'Geneva, Verdana, "DejaVu Sans"',
		),
		'Tahoma' => array(
			'category' => 'sans-serif',
			'family' => 'Tahoma, "DejaVu Sans"',
		),
		'Trebuchet MS' => array(
			'category' => 'sans-serif',
			'family' => '"Trebuchet MS", "Bitstream Vera Sans"',
		),
		'Lucida Grande' => array(
			'category' => 'sans-serif',
			'family' => '"Lucida Grande", "Lucida Sans Unicode", "Bitstream Vera Sans"',
		),
		'Georgia' => array(
			'category' => 'serif',
			'family' => 'Georgia, "URW Bookman L"',
		),
		'Times' => array(
			'category' => 'serif',
			'family' => 'Times, "Times New Roman", "Century Schoolbook L"',
		),
		'Palatino' => array(
			'category' => 'serif',
			'family' => 'Palatino, "Palatino Linotype", "URW Palladio L"',
		),
		'Courier' => array(
			'category' => 'monospace',
			'family' => 'Courier, "Courier New", "Nimbus Mono L"',
		),
		'Monaco' => array(
			'category' => 'monospace',
			'family' => 'Monaco, Consolas, "Lucida Console", "Bitstream Vera Sans Mono"',
		),
	);
	return apply_filters( 'stylish_custom_fonts', $fonts );
}


function stylish_web_fonts( $fonts = null ) {
	if( ! is_array( $fonts ) ) {
		$fonts = array();
	}
	
	$web_fonts = apply_filters( 'stylish_web_fonts', array(
		'Open Sans'  => array(
			'family' => 'Open Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Open Sans Condensed'  => array(
			'family' => 'Open Sans Condensed',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '700' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Roboto' => array(
			'family' => 'Roboto',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Source Sans Pro' => array(
			'family' => 'Source Sans Pro',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '600', '600italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'vietnamese' ),
		),
		'Lato'       => array(
			'family' => 'Lato',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'Droid Sans' => array(
			'family' => 'Droid Sans',
			'category' => 'sans-serif',
			'variants' => array( '400', '700' ),
			'subsets' => array( 'latin' ),
		),
		'PT Sans'    => array(
			'family' => 'PT Sans',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'cyrillic, cyrillic-ext' ),
		),
		'Fira Sans'    => array(
			'family' => 'Fira Sans',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'cyrillic', 'cyrillic-ext' ),
		),
		'Hind'    => array(
			'family' => 'Hind',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets' => array( 'latin', 'latin-ext', 'devanagari' ),
		),
		'Ek Mukta'    => array(
			'family' => 'Ek Mukta',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700', '800' ),
			'subsets' => array( 'latin', 'latin-ext', 'devanagari' ),
		),
		'Noto Sans'    => array(
			'family' => 'Noto Sans',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese', 'devanagari' ),
		),
		'Ubuntu' => array(
			'family' => 'Ubuntu',
			'category' => 'sans-serif',
			'variants' => array( '300', '300italic', '400', 'italic', '500', '500italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext' ),
		),
		'Cantarell'  => array(
			'family' => 'Cantarell',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'Raleway' => array(
			'family' => 'Raleway',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '500', '600', '700' ),
			'subsets' => array( 'latin' ),
		),
		'Oswald'     => array(
			'family' => 'Oswald',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Yanone Kaffeesatz' => array(
			'family' => 'Yanone Kaffeesatz',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Montserrat'     => array(
			'family' => 'Montserrat',
			'category' => 'sans-serif',
			'variants' => array( '400', '700' ),
			'subsets' => array( 'latin' ),
		),
		'Quattrocento Sans' => array(
			'family' => 'Quattrocento Sans',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Arimo' => array(
			'family' => 'Arimo',
			'category' => 'sans-serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Oxygen' => array(
			'family' => 'Oxygen',
			'category' => 'sans-serif',
			'variants' => array( '300', '400', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Droid Serif' => array(
			'family' => 'Droid Serif',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'Lora'       => array(
			'family' => 'Lora',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'PT Serif'   => array(
			'family' => 'PT Serif',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'cyrillic, cyrillic-ext' ),
		),
		'Arvo' => array(
			'family' => 'Arvo',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'Roboto Slab' => array(
			'family' => 'Roboto Slab',
			'category' => 'serif',
			'variants' => array( '300', '400', '700' ),
			'subsets' => array( 'latin', 'latin-ext', 'greek', 'greek-ext', 'cyrillic', 'cyrillic-ext', 'vietnamese' ),
		),
		'Bitter'       => array(
			'family' => 'Bitter',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Merriweather' => array(
			'family' => 'Merriweather',
			'category' => 'serif',
			'variants' => array( '300', '300italic', '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Crimson Text' => array(
			'family' => 'Crimson Text',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '600', '600italic', '700', '700italic' ),
			'subsets' => array( 'latin' ),
		),
		'Neuton' => array(
			'family' => 'Neuton',
			'category' => 'serif',
			'variants' => array( '200', '300', '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Playfair Display' => array(
			'family' => 'Playfair Display',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic', '900', '900italic' ),
			'subsets' => array( 'latin', 'latin-ext', 'cyrillic' ),
		),
		'Libre Baskerville' => array(
			'family' => 'Libre Baskerville',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Gentium Basic' => array(
			'family' => 'Gentium Basic',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Gentium Book Basic' => array(
			'family' => 'Gentium Book Basic',
			'category' => 'serif',
			'variants' => array( '400', 'italic', '700', '700italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Sorts Mill Goudy' => array(
			'family' => 'Sorts Mill Goudy',
			'category' => 'serif',
			'variants' => array( '400', 'italic' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Halant' => array(
			'family' => 'Halant',
			'category' => 'serif',
			'variants' => array( '400', '500', '600', '700' ),
			'subsets' => array( 'latin', 'latin-ext', 'devanagari' ),
		),
		'Domine' => array(
			'family' => 'Domine',
			'category' => 'serif',
			'variants' => array( '400', '700' ),
			'subsets' => array( 'latin', 'latin-ext' ),
		),
		'Poly' => array(
			'family' => 'Poly',
			'category' => 'serif',
			'variants' => array( '400', 'italic' ),
			'subsets' => array( 'latin' ),
		),
		'Ovo' => array(
			'family' => 'Ovo',
			'category' => 'serif',
			'variants' => array( '400' ),
			'subsets' => array( 'latin' ),
		),
	) );
	return array_merge( $fonts, $web_fonts );
}
add_filter( 'stylish_custom_fonts', 'stylish_web_fonts' );

function stylish_get_font_atts( $font ) {
	$fonts = stylish_web_fonts();
	
	if( isset( $fonts[$font] ) ) {
		return $fonts[$font];
	}
	
	return false;
}

function stylish_enqueue_font( $font, $styles = null ) {
	if( null === $styles ) {
		$styles = array( '400', 'italic', '700' );
	}
	
	global $stylish_web_fonts;
	if( ! isset( $stylish_web_fonts ) ) {
		$stylish_web_fonts = array();
	}
	
	$fonts = stylish_web_fonts();
	
	if( array_key_exists( $font, $fonts ) ) {
		if( isset( $enlightenment_web_fonts[$font] ) ) {
			$stylish_web_fonts[$font] = array_unique( array_merge( $stylish_web_fonts[$font], $styles ) );
		} else {
			$stylish_web_fonts[$font] = $styles;
		}
	}
}

function stylish_enqueue_theme_options_font( $option ) {
	$font = get_theme_mod( $option );
	$atts = stylish_get_font_atts( $font );
	
	if( array_key_exists( $font, stylish_web_fonts() ) ) {
		stylish_enqueue_font( $font, $atts['variants'] );
	}
}

function stylish_enqueue_web_fonts() {
	stylish_enqueue_theme_options_font( 'headings_font' );
	stylish_enqueue_theme_options_font( 'text_font' );
	stylish_enqueue_theme_options_font( 'accent_font' );
}
add_action( 'stylish_enqueue_fonts', 'stylish_enqueue_web_fonts' );

function stylish_fonts_to_load() {
	global $stylish_web_fonts;
	
	if( ! isset( $stylish_web_fonts ) ) {
		$stylish_web_fonts = array();
	}

	do_action( 'stylish_enqueue_fonts' );
	
	return apply_filters( 'stylish_fonts_to_load', $stylish_web_fonts );
}

function stylish_web_fonts_style( $args = null ) {
	$defaults = array(
		'variants' => array( '400', 'italic', '700' ),
		'subsets' => array( 'latin' ),
	);
	
	$defaults = apply_filters( 'stylish_web_fonts_style_args', $defaults );
	$args = wp_parse_args( $args, $defaults );
	
	$fonts = stylish_fonts_to_load();
	
	if( ! empty( $fonts ) ) {
		global $stylish_web_fonts_errors;
		
		if( ! isset( $stylish_web_fonts_errors ) ) {
			$stylish_web_fonts_errors = array();
		}
		
		$stylesheet = 'http' . ( is_ssl() ? 's' : '' ) . '://fonts.googleapis.com/css?family=';
		
		foreach( $fonts as $font => $styles ) {
			if( empty( $styles ) ) {
				$styles = $args['variants'];
			}
			
			$atts = stylish_get_font_atts( $font );
			
			foreach( $styles as $key => $style ) {
				if( ! in_array( $style, $atts['variants'], true ) ) {
					unset( $styles[$key] );
				}
			}
			
			$styles = array_values( $styles );
			
			foreach( $args['subsets'] as $key => $subset ) {
				if( ! in_array( $subset, $atts['subsets'] ) ) {
					$stylish_web_fonts_errors[] = sprintf( __( 'The font "%1$s" does not support your selected subset <code>%2$s</code>.', 'stylish' ), $font, $subset );
				}
			}
			
			$fonts[$font] = array_values( $styles );
		}
		
		$i = 0;
		$c = count( $fonts );
		
		foreach( $fonts as $font => $styles ) {
			$i++;
			$font = str_replace( ' ', '+', $font );
			$stylesheet .= $font;
			
			if( ! empty( $styles ) && array( '400' ) != $styles ) {
				$stylesheet .= ':';
				$tmpstyles = $styles;
				$laststyle = array_pop( $tmpstyles );
				unset( $tmpstyles );
				
				foreach( $styles as $style ) {
					$stylesheet .= $style;
					if( $style != $laststyle ) {
						$stylesheet .= ',';
					}
				}
			}
			
			if( $i != $c ) {
				$stylesheet .= '|';
			}
		}
		
		$stylesheet .= '&subset=';
		
		foreach( $args['subsets'] as $subset ) {
			$stylesheet .= $subset;
			if( $subset != end( $args['subsets'] ) ) {
				$stylesheet .= ',';
			}
		}
		
		return esc_url( $stylesheet );
	}
	
	return false;
}