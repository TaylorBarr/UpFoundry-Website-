<!-- ================================================== -->
<!-- =============== START JS OPTIONS ================ -->
<!-- ================================================== -->
	
jQuery(document).ready(function($){
	
	"use strict";

	// FitVids
	$("html").fitVids({ customSelector: "embed[src*='wordpress.com'], embed[src*='wordpress.tv'], iframe[src*='wordpress.com'], iframe[src*='wordpress.tv'], iframe[src*='www.dailymotion.com'], iframe[src*='blip.tv'], iframe[src*='www.viddler.com']"});

	if ( jQuery('.blog-single-head.without-bg').length > 0 ) {

		jQuery('.page-head').addClass('light star-change').css('min-height','1px');

	};

	if ($('.title img').length > 0) {

		$('.title img').before('<div class="image-alt"></div>');

		$('.page-head').addClass('with-avatar');

	};

	var wordsContainer = $('.skills-text-list');

	var word = $('.skills-text-list span');

	var firstWord = $('.skills-text-list span:first-child');

	var lastWord = $('.skills-text-list span:last-child');

	firstWord.addClass('active');

	setInterval(function(){

		wordsContainer.find('span.active').each(function(){

			$(this).next().addClass('active');

			$(this).removeClass('active').appendTo('.skills-text-list');

		});


	},3500);

	function avatarScroll(iH , sH , sU , sS) {

		$('.page-head .page-title-or-author img').css('top',iH);

		var imageHeight = iH,
			stopHeight = sH,
			scaleUntil = sU

	    $(window).scroll(function(e) {
			var windowScroll = $(window).scrollTop(),
		    	newHeight = imageHeight - windowScroll,
		    	actualScale = 1 - (windowScroll/scaleUntil),
		    	stopScale = sS;
		    if(newHeight>=stopHeight){
		        $('.title img').css("top", newHeight);
		        $('.title img').css({
		        	'-webkit-transform':'translateX(-50%) scale(' + actualScale + ')',
		        	'-moz-transform':'translateX(-50%) scale(' + actualScale + ')',
		        	'transform':'translateX(-50%) scale(' + actualScale + ')',
		        });
		    } else {
		        $('.title img').css("top", stopHeight);
			    $('.title img').css({
		        	'-webkit-transform':'translateX(-50%) scale(' + stopScale + ')',
		        	'-moz-transform':'translateX(-50%) scale(' +  + ')',
		        	'transform':'translateX(-50%) scale(' + stopScale + ')',
		        });
		    }
		});

	}

	if ($(window).width() >= 1400){  
		
		avatarScroll(270,10,550,0.534545454545455);

	} else {
		$(window).resize(function(){
			if ($(window).width() >= 1400){  
				
				avatarScroll(270,10,550,0.534545454545455);

			}
		});
	};

	if ($(window).width() <= 1400){  
		
		avatarScroll(150,10,550,0.756363636363636);

	} else {
		$(window).resize(function(){
			if ($(window).width() <= 1400){  
				
				avatarScroll(150,10,550,0.756363636363636);

			}
		});
	};

	if ($(window).width() <= 500){  
		
		jQuery(window).scroll(function(){

			var actualScroll = jQuery(window).scrollTop() ,
				coverHeight = jQuery('.page-head').outerHeight();

			console.log(actualScroll);
			console.log(coverHeight);

			if ( actualScroll >= coverHeight ) {

				jQuery('.page-head .page-header .header-left-js-button .menu-button').addClass('light');

			} else {

				jQuery('.page-head .page-header .header-left-js-button .menu-button').removeClass('light');

			};

		});

	} else {
		$(window).resize(function(){
			if ($(window).width() <= 500){  
				
				jQuery(window).scroll(function(){

					var actualScroll = jQuery(window).scrollTop() ,
						coverHeight = jQuery('.page-head').outerHeight();

					console.log(actualScroll);
					console.log(coverHeight);

					if ( actualScroll >= coverHeight ) {

						jQuery('.page-head .page-header .header-left-js-button .menu-button').addClass('light');

					} else {

						jQuery('.page-head .page-header .header-left-js-button .menu-button').removeClass('light');

					};

				});				

			}
		});
	};

	var lastScrollTop = 0;

	$(window).scroll(function(event){

		var st = $(this).scrollTop();

		var thisH = $('.page-title-or-author , .blog-single-head').outerHeight() - $('.page-header').outerHeight();

//		var thisHH = thisH + 300;

//		if (st > thisHH) {

		if (st > thisH) {

			if (st > lastScrollTop){

				$('.page-head.affix').addClass('scroll-down');

			} else {

				$('.page-head.affix').removeClass('scroll-down');

			}

			lastScrollTop = st;

		};
		
	});

	function projectSingleAffix() {

		var wHH = $('.page-title-or-author , .blog-single-head').outerHeight();

		$('.page-head').affix({

			offset: {

				top: wHH - 75

			}

		});

		$('.page-head.start-change').affix({

			offset: {

				top: 75

			}

		});

	};

	function headerHeight() {

		$('.page-head').each(function(){

			var thisH = $('.page-title-or-author , .blog-single-head').outerHeight();

			$(this).css('height',thisH);

		});

	};

	headerHeight();

	projectSingleAffix();

	$(window).resize(function(){

		headerHeight();

		projectSingleAffix();

	});

	$('.title * , .blog-single-head:not(.without-bg) .container').each(function(){

		var fadeStart= 0
			,fadeUntil= 350
			,fading = $(this);

		$(window).bind('scroll', function(){

			var offset = $(document).scrollTop()
				,opacity=0;

			if( offset<=fadeStart ){

				opacity=1;

			} else if ( offset<=fadeUntil ){

				opacity=1-offset/fadeUntil;

			}

			fading.css('opacity',opacity);

		});

	});

	if ( $('.page-head .page-title-or-author img').is(':visible') ) {

		$('.page-head .page-header .header-content .middle-side , .title * , .blog-single-head .container').each(function(){

			var fadeStart= 0
				,fadeUntil= 350
				,fading = $(this);

			$(window).bind('scroll', function(){

				var offset = $(document).scrollTop()
					,opacity=0;

				if( offset<=fadeStart ){

					opacity=1;

				} else if ( offset<=fadeUntil ){

					opacity=1-offset/fadeUntil;

				}

				fading.css('opacity',opacity);

			});

		});

	};

	// Blog Settings

	$('.share-button , .download-button').click(function(e){

		$(this).toggleClass('active');

		$(this).siblings().removeClass('active');

		e.stopPropagation();

	});

	$('.share-button > ul , .download-button .download-box').click(function(e){

		e.stopPropagation();

	});

	$('body').click(function(){

		$('.share-button , .download-button').removeClass('active');

	});

	$('.full-width-image').each(function(index, element) {
		$(element).css("min-height", $(element).find('img').height());
		$( window ).resize(function() {
			$(element).css("min-height", $(element).find('img').height());
		});
	});

	$('.full-width-blockquote , .full-width-blockquote .blockquote').each(function(index, element) {
		$(element).css("height", $(element).find('blockquote').outerHeight());
		$( window ).resize(function() {
			$(element).css("height", $(element).find('blockquote').outerHeight());
		});
	});

	window.onload = function() {

		$('.full-width-image').each(function(index, element) {
			$(element).css("min-height", $(element).find('img').height());
			$( window ).resize(function() {
				$(element).css("min-height", $(element).find('img').height());
			});
		});

		$('.full-width-blockquote , .full-width-blockquote .blockquote').each(function(index, element) {
			$(element).css("height", $(element).find('blockquote').outerHeight());
			$( window ).resize(function() {
				$(element).css("height", $(element).find('blockquote').outerHeight());
			});
		});

	};

	// Sub Menu HoverIdent

	function slideDownDivaMenu(){

		$(this).find('ul').slideDown(100);

	}

	function slideUpDivaMenu(){

		$(this).find('ul').slideUp(100);

	}

	$(".main-menu ul").hoverIntent({
		over: slideDownDivaMenu,
		out: slideUpDivaMenu,
		interval: 100,
		selector: 'li'
	});

	// Menu Hover Siblings Fade

	$('.main-menu ul li').hover(function(){

		$(this).siblings().toggleClass('no-hovered');

	});

	// Open Menu Function

	$('.header-left-js-button .menu-button').click(function(){

		$('body').toggleClass('menu-is-open');

		$('.main-menu').fadeIn('400');

		if ($('.menu-is-open-search').length > 0) {

			$('body').removeClass('menu-is-open');

			$('body').removeClass('menu-is-open-search');

			$('.main-menu').delay(400).queue(function(next){
				$(this).show();
			});

			$('.seacrh-form').fadeOut('400');

		};

	});

	// Open Search Function

	$('.header-right-js-button i').click(function(){

		$('body').addClass('menu-is-open-search');

		$('.seacrh-form').fadeIn('400');
		
		setTimeout(function() {
			$('#s').focus();
		}, 400)
		
	});

	$('.header-right-js-button div').click(function(){

		$('body').removeClass('menu-is-open');

		$('body').removeClass('menu-is-open-search');

		$('.main-menu').delay(400).queue(function(next){
			$(this).show();
		});

		$('.seacrh-form').fadeOut('400');

	});

	// Slider Script

	var owlIMBT = $('.owl-imbt .images');

	owlIMBT.on('initialized.owl.carousel', function(event) {

		$(this).parent().find('.current-slide').html(event.item.count);

		$(this).parent().find('.number-of-slides').html(event.item.index + 1);

	});

	owlIMBT.owlCarousel({
		loop:false,
		margin:0,
		nav:true,
		items: 1,
		navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>']
	});
	
	owlIMBT.on('changed.owl.carousel', function(event) {

		$(this).parent().find('.current-slide').html(event.item.count);

		$(this).parent().find('.number-of-slides').html(event.item.index + 1);

	});

	$('.change-menu').on('scrollSpy:enter', function() {
		$('.page-head').addClass('light-layout');
	});

	$('.change-menu').on('scrollSpy:exit', function() {
		$('.page-head').removeClass('light-layout');
	});

	$('.change-menu').scrollSpy();
	
	// PrettyPhoto
	$("*[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:9999, autoplay_slideshow: false});

	$(document).keyup(function(e){

		if ( e.keyCode === 27 ) {

			$('body').removeClass('menu-is-open menu-is-open-search');

			$('.main-menu').fadeOut('400');

		}

	});

});

<!-- ================================================== -->
<!-- =============== END JS OPTIONS ================ -->
<!-- ================================================== -->