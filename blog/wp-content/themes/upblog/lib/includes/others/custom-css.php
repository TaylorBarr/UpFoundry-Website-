<?php


function stylish_custom_css( $input ) {
	$defaults       = stylish_default_theme_settings();
	$headings_color = get_theme_mod( 'headings_color' );
	$text_color     = get_theme_mod( 'text_color' );
	$link_color     = get_theme_mod( 'link_color' );
	$accent_color   = get_theme_mod( 'accent_color' );
	$headings_font  = get_theme_mod( 'headings_font' );
	$text_font      = get_theme_mod( 'text_font' );
	$accent_font    = get_theme_mod( 'accent_font' );
	
	if( $headings_font != $defaults['headings_font'] || $text_font != $defaults['text_font'] || $accent_font != $defaults['accent_font'] ) {
		$fonts = stylish_custom_fonts();
	}
	
	if( $headings_color != $defaults['headings_color'] ) {
		$input .= "input[type=submit] .badge,
.page-footer-bottom,
.page-content-inner .facebook-share-cite,
.blog-single-head {
	background-color: {$headings_color};
}
.form-control:focus,
input:not([type=submit]):focus,
textarea:focus,
input[type=submit]:hover,
.page-content-inner .facebook-share-cite:before,
.share-button:hover,
.download-button:hover,
.share-button.active,
.download-button.active,
.posts-list article:hover figure figcaption,
.posts-list .more-posts-button li.active a {
	border-color: {$headings_color};
}
blockquote,
blockquote p,
input[type=submit],
.owl-imbt .slider-count,
.owl-imbt .owl-controls,
.menu-search-container .subscribe-menu h3,
.page-head.affix .page-header .header-right-js-button i,
.page-head.light .page-header,
.page-head.light .page-header p,
.page-head.light .page-title-or-author,
.page-head.light .page-title-or-author h1,
.page-head.light .page-title-or-author h3,
.blog-single-head.without-bg .article-details span,
.page-footer-top .subscribe h3,
.comment-container .title-comments {
	color: {$headings_color};
}
@media (max-width: 800px) {
	.page-head.light.affix .page-header .header-left-js-button .menu-button span,
	.page-head.light.affix .page-header .header-right-js-button .menu-button span {
		background-color: {$headings_color};
	}
	.page-content-inner .facebook-share-cite:before {
		border-color: {$headings_color};
	}
	.page-head.light.affix .page-header .header-right-js-button i {
		color: {$headings_color};
	}
}\n";
	}
	
	if( $text_color != $defaults['text_color'] ) {
		$input .= ".help-block,
.posts-list article figure .article-details p,
.posts-list article figure .article-details {
	color: {$text_color};
}\n";
	}
	
	if( $link_color != $defaults['link_color'] ) {
		$input .= ".panel-title > a,
.btn-primary .badge,
.btn-primary:hover,
.btn-success,
.page-head .page-title-or-author,
.page-head .page-header .header-left-js-button .menu-button.light span,
.page-head.affix .page-header .header-left-js-button .menu-button span,
.page-head.affix .page-header .header-right-js-button .menu-button span,
.page-head.light .page-header .header-left-js-button .menu-button span,
.page-head.light .page-header .header-right-js-button .menu-button span,
.page-head.light .page-header .header-content .left-side a:hover,
.page-head.light .page-header .header-content .right-side .social-icons-list ul li a:hover,
.page-head.light .page-title-or-author .btn,
.page-footer-top .widgets-area .widget .tagcloud a:hover,
.page-footer-top .widgets-area .widget ul li:hover:before,
body.menu-is-open .page-head .page-header .header-left-js-button .menu-button span,
body.menu-is-open-search .page-head .page-header .header-left-js-button .menu-button span,
body.menu-is-open .page-head .page-header .header-right-js-button .menu-button span,
body.menu-is-open-search .page-head .page-header .header-right-js-button .menu-button span {
	background-color: {$link_color};
}
.page-head .page-header .header-content .left-side a:hover,
.btn:hover,
.btn-primary,
.btn-success,
.nav-tabs > li > a:hover,
.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus,
.panel-title > a,
.page-head.light .page-header .header-content .left-side a,
.page-head.light .page-header .header-content .right-side .social-icons-list ul li a,
.page-footer-top .widgets-area .widget .tagcloud a:hover,
.page-footer-top .widgets-area .widget ul li:hover a:hover {
	border-color: {$link_color};
}
a, {
.btn,
.btn:hover,
.btn-default,
.btn-default .badge,
.btn-primary,
.btn-success .badge,
.btn-success:hover,
.nav-tabs > li > a,
.panel-title > a,
.panel-title > a:before,
.panel-title > a.collapsed:before,
.menu-search-container .main-menu ul li a,
.page-head .page-title-or-author .btn:hover,
.page-head.light .page-header a,
.page-head.light .page-header .header-content .right-side .social-icons-list ul li a i,
.blog-single-head.without-bg h1 a,
.blog-single-head.without-bg .article-details > a,
.blog-single-head.without-bg .article-details span a,
.blog-single-head.without-bg .article-details > a a,
.page-footer-top .widgets-area .widget ul li:hover a,
.page-head.light .page-title-or-author .btn,
.page-head.light .page-title-or-author .btn:hover,
.comment-container ul.comments li .comment .right-section .comment-reply-link,
.comment-container ul.comments li .comment .right-section h1 a,
.share-button,
.download-button,
.share-button .badge,
.download-button .badge,
.share-button > span,
.download-button > span,
.posts-list article figure .article-details h2 a,
.posts-list article:hover figure figcaption i {
	color: {$link_color};
}\n";
	}
	
	if( $accent_color != $defaults['accent_color'] ) {
		$input .= ".posts-list.related-posts > h4 {
			color: {$accent_color};
		}\n";
	}
	
	if( $headings_font != $defaults['headings_font'] ) {
		$family = sprintf( '%1$s, %2$s', $fonts[ $headings_font ]['family'], $fonts[ $headings_font ]['category'] );
		$input .= "h1, h2, h3, h6,
input[type=submit],
.btn,
.nav-tabs > li > a,
.panel-title > a,
.menu-search-container .main-menu ul li a,
.menu-search-container .subscribe-menu h3,
.page-head .page-header .header-content .left-side a,
.page-head .page-header .header-content .middle-side a,
.page-footer-top .subscribe h3,
.page-footer-top .widgets-area .widget h3,
.page-footer-top .widgets-area .widget.widget_rss h3 a,
.page-footer-top .widgets-area .widget .tagcloud a,
.blog-single-head h1,
.blog-single-head h1 a,
.products-list article h3,
.posts-list article figure .article-details h2 a {
	font-family: {$family};
}\n";
	}
	
	if( $text_font != $defaults['text_font'] ) {
		$family = sprintf( '%1$s, %2$s', $fonts[ $text_font ]['family'], $fonts[ $text_font ]['category'] );
		$input .= "html body,
html body p,
h5,
blockquote,
blockquote p,
.form-control,
input:not([type=submit]),
textarea,
.page-head .page-title-or-author h3,
.page-content-inner .wp-caption p,
.page-content-inner .author-info h1 {
	font-family: {$family};
}\n";
	}
	
	if( $accent_font != $defaults['accent_font'] ) {
		$family = sprintf( '%1$s, %2$s', $fonts[ $accent_font ]['family'], $fonts[ $accent_font ]['category'] );
		$input .= "h4,
.owl-imbt .slider-count
.owl-imbt .slider-count span,
.page-head .page-header .header-content .left-side p,
.page-footer-top .widgets-area .widget select,
.comment-container .title-comments,
.comment-container .title-comments a,
.comment-container ul.comments li .comment .right-section h1 a,
.comment-container ul.comments li .comment .right-section .time-comment,
.contact-form-imbt h3,
.comment-respond h3,
.share-button > span,
.download-button > span,
.share-button > ul li a,
.download-button .download-box h3,
.posts-list.related-posts > h4 {
	font-family: {$family};
}\n";
	}
	
	// Add user customized styles
	$input .= get_theme_mod( 'custom_css' );
	
	return $input;
}
add_filter( 'stylish_custom_css', 'stylish_custom_css' );

function stylish_sanitize_custom_css( $input ) {
	/* Sanitize Custom CSS */
	$input = strip_tags( $input );
	$input = str_replace( 'behavior',   '', $input );
	$input = str_replace( 'expression', '', $input );
	$input = str_replace( 'binding',    '', $input );
	$input = str_replace( '@import',    '', $input );
	
	return $input;
}
add_filter( 'stylish_custom_css', 'stylish_sanitize_custom_css', 999 );