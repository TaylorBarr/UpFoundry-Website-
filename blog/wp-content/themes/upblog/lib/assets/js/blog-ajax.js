/**
 * @author StylishThemes
 */

var transition = function($newEl) {
    var $oldEl = this;      // reference to the DOM element that is about to be replaced


    // ** Fadeout then slide in **
    $oldEl.fadeOut('fast', function () {
        $oldEl.after($newEl);

        $newEl.hide();
        //$newEl.slideDown('slow');
        $newEl.fadeIn('slow');

        $oldEl.remove();	// removes 'oldEl'
    });

};

function reinitfeaturePageH() {

    var wH = jQuery(window).height();

    jQuery('.page-feature-image').css('height',wH);

}

function reinitroyalSliderAva() {

    jQuery('#ava-slider').each(function(){

        var wH = jQuery(window).height();

        jQuery(this).parent().css('height',wH);

        jQuery(this).css('height',wH);

        jQuery(this).royalSlider({
            arrowsNav: true,
            loop: false,
            keyboardNavEnabled: true,
            controlsInside: false,
            imageScaleMode: 'fill',
            arrowsNavAutoHide: false,
            autoScaleSlider: false,
            controlNavigation: false,
            thumbsFitInViewport: false,
            navigateByClick: true,
            startSlideId: 0,
            autoPlay: false,
            transitionType:'move',
            globalCaption: false,
            slidesSpacing: 0,
            deeplinking: {
                enabled: true,
                change: false
            }
        });

    });

    jQuery('#ava-slider-home').each(function(){

        var wH = jQuery(window).height();

        jQuery(this).parent().css('height',wH);

        jQuery(this).css('height',wH);

        jQuery(this).royalSlider({
            arrowsNav: true,
            loop: false,
            keyboardNavEnabled: true,
            controlsInside: false,
            fadeinLoadedSlide: true,
            imageScaleMode: 'fill',
            arrowsNavAutoHide: false,
            autoScaleSlider: false,
            controlNavigation: false,
            thumbsFitInViewport: false,
            navigateByClick: true,
            startSlideId: 0,
            autoPlay: false,
            transitionType:'fade',
            globalCaption: false,
            slidesSpacing: 0,
            deeplinking: {
                enabled: true,
                change: false
            }
        });

    });

}

function reinitisotope() {

    var container = jQuery('.front-articles-container');

    container.imagesLoaded(function() {

        container.isotope({
            masonry: {

                columnWidth: 1

            }
        });

    });

    container.isotope({
        masonry: {

            columnWidth: 1

        }
    });

    var jQueryoptionSets = jQuery('.option-set'),

        jQueryoptionLinks = jQueryoptionSets.find('a');

    jQueryoptionLinks.click(function(){

        var jQuerythis = jQuery(this);

        if ( jQuerythis.hasClass('selected') ) {
            return false;
        }

        var jQueryoptionSet = jQuerythis.parents('.option-set');

        jQueryoptionSet.find('.selected').removeClass('selected');

        jQuerythis.addClass('selected');

        var options = {},
            key = jQueryoptionSet.attr('data-option-key'),
            value = jQuerythis.attr('data-option-value');

        value = value === 'false' ? false : value;

        options[ key ] = value;

        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
            changeLayoutMode( jQuerythis, options )
        } else {
            container.isotope( options );
        }

        return false;

    });

}

function reinitaffix() {

    var wHH = jQuery(window).height();

    jQuery('.can-be-affix').affix({
        offset: {
            top: wHH - 110
        }
    });

}

function reinitpopups() {
    jQuery('.filter-list nav ul li').hover(function(){

        jQuery(this).siblings().toggleClass('no-hovered');

    });

    jQuery('.share-list nav ul li a').hover(function(){

        jQuery(this).parent().siblings().toggleClass('no-hovered');

    });

    jQuery('.filter-button , .filter-list nav .x-filter , .filter-list nav ul li a').click(function(e){

        e.preventDefault();

        jQuery('.filter-list').toggleClass('active');

    });

    jQuery('.bottom-single-post .share , .share-list nav .x-share').click(function(e){

        e.preventDefault();

        jQuery('.share-list').toggleClass('active');

    });

    jQuery('.change-header-items-color').on('scrollSpy:enter', function() {
        jQuery('.menu-button').addClass('light');
        jQuery('.filter-button').addClass('light');
        jQuery('.logo').addClass('change-logo');
    });

    jQuery('.change-header-items-color').on('scrollSpy:exit', function() {
        jQuery('.menu-button').removeClass('light');
        jQuery('.filter-button').removeClass('light');
        jQuery('.logo').removeClass('change-logo');
    });

    jQuery('.change-header-items-color').scrollSpy();

    jQuery('.menu-button').removeClass('without-bg-normal');
    jQuery('.filter-button').removeClass('without-bg-normal');

    jQuery('.royalSlider').on('scrollSpy:enter', function() {
        jQuery('.menu-button').addClass('without-bg-normal');
        jQuery('.filter-button').addClass('without-bg-normal');
    });

    jQuery('.royalSlider').on('scrollSpy:exit', function() {
        jQuery('.menu-button').removeClass('without-bg-normal');
        jQuery('.filter-button').removeClass('without-bg-normal');
    });

    jQuery('.royalSlider').scrollSpy();

    jQuery("*[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:9999, autoplay_slideshow: false});
}

function reinitInfiniteScroll() {

    opts = {
        offset: '110%'
    };

    jQuery('.portfolio-load-posts').waypoint(function(direction) {

        if(direction == 'down') {

            var link = jQuery('a#portfolio-load-more-button').attr('href');

            var button = jQuery('a#portfolio-load-more-button');

            if(link !== undefined) {
                jQuery.get(link, function (data) {

                    var newhref = jQuery(data).find("#pagination-ajax-inner-portfolio a").attr('href');

                    var elements = jQuery(data).find("#blog-ajax-container article");

                    var container = jQuery("#blog-ajax-container");

                    container.isotope('insert', elements);
                    setTimeout(function() {
                        container.isotope('layout');
                    },300);

                    if (newhref !== undefined) {
                        jQuery('#pagination-ajax-inner-portfolio a').attr('href', newhref);
                    } else {
                        jQuery('#pagination-ajax-inner-portfolio').html(' ');
                    }

                }).done(function () {

                    jQuery.waypoints('refresh');

                });
            }

        }

    }, opts);

    jQuery('.blog-load-posts').waypoint(function(direction) {

        if(direction == 'down') {

            var link = jQuery('a#blog-load-more-button').attr('href');

            var button = jQuery('a#blog-load-more-button');

            if(link !== undefined) {
                jQuery.get(link, function (data) {

                    var newhref = jQuery(data).find("#pagination-ajax-inner a").attr('href');

                    var elements = jQuery(data).find("#blog-ajax-container article");

                    var container = jQuery("#blog-ajax-container");

                    container.isotope('insert', elements);
                    setTimeout(function() {
                        container.isotope('layout');
                    },300);

                    if (newhref !== undefined) {
                        jQuery('#pagination-ajax-inner a').attr('href', newhref);
                    } else {
                        jQuery('#pagination-ajax-inner').html(' ');
                    }

                }).done(function () {

                    jQuery.waypoints('refresh');

                });
            }

        }

    }, opts);
}


jQuery('document').ready(function($) {

    setTimeout(function() {
        $('body').djax('.updatable2', ['.pdf','.doc','.eps','.png','.zip','admin','wp-','wp-admin','feed','#', '?lang=', '&lang=', '&add-to-cart=', '?add-to-cart=', '?remove_item', 'download_file='], transition);
    }, 500);


    // On djax click
    $(window).bind('djaxClick', function(e, data) {

        e.preventDefault();

        jQuery('html').addClass('loading');

        var bodyelem = ($.browser.safari) ? bodyelem = $("body") : bodyelem = $("html,body");
        bodyelem.animate({scrollTop: 0}, 300);
    });


    // On djax load
    $(window).bind('djaxLoad', function(e, data) {

        setTimeout(function() {

            // Close the menu
            jQuery('html').removeClass('menu-is-opened');

            reinitfeaturePageH();
            reinitroyalSliderAva();
            reinitisotope();
            reinitaffix();
            reinitpopups();

            smoothScroll.init({
                speed: 500, // Integer. How fast to complete the scroll in milliseconds
                easing: 'easeInOutCubic', // Easing pattern to use
                updateURL: false, // Boolean. Whether or not to update the URL with the anchor hash on scroll
                offset: 0, // Integer. How far to offset the scrolling anchor location in pixels
                callbackBefore: function ( toggle, anchor ) {}, // Function to run before scrolling
                callbackAfter: function ( toggle, anchor ) {} // Function to run after scrolling
            });

            data = data.response.replace(/(<\/?)body( .+?)?>/gi,'$1NOTBODY$2>', data);
            var nobodyClass = $(data).filter('notbody').attr("class");

            $('body').attr("class", nobodyClass);

            $('html').removeClass('loading');

            reinitInfiniteScroll();

        }, 300);

        setTimeout(function() {
            jQuery('.logo-secondary-table').each(function(){

                var fadeStart= 0
                    ,fadeUntil= 400
                    ,fading = jQuery(this);

                jQuery(window).bind('scroll', function(){

                    var offset = jQuery(document).scrollTop()
                        ,opacity=0;

                    if( offset<=fadeStart ){

                        opacity=1;

                    } else if ( offset<=fadeUntil ){

                        opacity=1-offset/fadeUntil;

                    }

                    fading.css('opacity',opacity);

                });

            });
        }, 700);

        jQuery('html').removeClass('loading');

        //lets do some Google Analytics Tracking
        if (window._gaq) {
            _gaq.push(['_trackPageview']);
        }
    });

});


jQuery('a#blog-load-more-button').click(function(event){

    event.preventDefault();

    var link = jQuery(this).attr('href');

    var button = jQuery(this);

    button.addClass('loading-posts');

    jQuery.get( link, function( data ) {

        var newhref = jQuery(data).find("#pagination-ajax-inner a").attr('href');

        var elements = jQuery(data).find("#blog-ajax-container article");

        var container = jQuery("#blog-ajax-container");

        container.append(elements);

        if(newhref !== undefined) {
            jQuery('#pagination-ajax-inner a').attr('href', newhref);
        } else {
            jQuery('#pagination-ajax-inner').html(' ');
        }

    }).done(function(){

        button.removeClass('loading-posts');

    });

});
jQuery(document).ready(function() {
    //jQuery('.blog-load-posts').waypoint('infinite', {
    //    container: '#blog-ajax-container',
    //    items: '.blog-article-02',
    //    more: '#blog-load-more-button',
    //    onAfterPageLoad: function() {
    //        //jQuery('body').djax('.updatable2', ['.pdf','.doc','.eps','.png','.zip','admin','wp-','wp-admin','feed','#', '?lang=', '&lang=', '&add-to-cart=', '?add-to-cart=', '?remove_item', 'download_file='], transition);
    //    }
    //});

    opts = {
        offset: '100%'
    };

    jQuery('.blog-load-posts').waypoint(function(direction) {

        if(direction == 'down') {

            var link = jQuery('a#blog-load-more-button').attr('href');

            var button = jQuery('a#blog-load-more-button');

            if(link !== undefined) {
                jQuery.get(link, function (data) {

                    var newhref = jQuery(data).find("#pagination-ajax-inner a").attr('href');

                    var elements = jQuery(data).find("#blog-ajax-container article");

                    var container = jQuery("#blog-ajax-container");

                    container.isotope('insert', elements);

                    setTimeout(function() {
                        container.isotope('layout');
                    },300);

                    if (newhref !== undefined) {
                        jQuery('#pagination-ajax-inner a').attr('href', newhref);
                    } else {
                        jQuery('#pagination-ajax-inner').html(' ');
                    }

                }).done(function () {

                    jQuery.waypoints('refresh');

                });
            }

        }

    }, opts);
});


jQuery('a#portfolio-load-more-button').click(function(event){

    event.preventDefault();

    var link = jQuery(this).attr('href');

    var button = jQuery(this);

    button.addClass('loading-posts');

    jQuery.get( link, function( data ) {

        var newhref = jQuery(data).find("#pagination-ajax-inner-portfolio a").attr('href');

        var elements = jQuery(data).find("#blog-ajax-container article");

        var container = jQuery("#blog-ajax-container");

        container.isotope( 'insert', elements);

        container.imagesLoaded(function() {

            container.isotope();

        });

        if(newhref !== undefined) {
            jQuery('#pagination-ajax-inner-portfolio a').attr('href', newhref);
        } else {
            jQuery('#pagination-ajax-inner-portfolio').html(' ');
        }

    }).done(function(){

        button.removeClass('loading-posts');

    });

});
jQuery(document).ready(function() {

    opts = {
        offset: '100%'
    };

    jQuery('.portfolio-load-posts').waypoint(function(direction) {

        if(direction == 'down') {

            var link = jQuery('a#portfolio-load-more-button').attr('href');

            var button = jQuery('a#portfolio-load-more-button');

            if(link !== undefined) {
                jQuery.get(link, function (data) {

                    var newhref = jQuery(data).find("#pagination-ajax-inner-portfolio a").attr('href');

                    var elements = jQuery(data).find("#blog-ajax-container article");

                    var container = jQuery("#blog-ajax-container");

                    container.isotope('insert', elements);

                    setTimeout(function() {
                        container.isotope('layout');
                    },300);

                    if (newhref !== undefined) {
                        jQuery('#pagination-ajax-inner-portfolio a').attr('href', newhref);
                    } else {
                        jQuery('#pagination-ajax-inner-portfolio').html(' ');
                    }

                }).done(function () {

                    jQuery.waypoints('refresh');

                });
            }

        }

    }, opts);

});

