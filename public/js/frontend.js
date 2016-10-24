$(window).scroll(function () {
    headerHeight = $('.header').height();
    if ($(window).scrollTop() > headerHeight) {
        $('.main-nav').css('position', 'fixed');
        $('.main-nav').css('top', '0');
        $('.main-nav').css('width', '100%');
        $('.main-nav').css('marginBottom', '77x');
    } else {
        $('.main-nav').css('position', 'relative');
        $('.header').css('marginBottom', '0px');
    }
});

jQuery(document).ready(function () {
    jQuery('#gallery').unitegallery({
        thumb_image_overlay_effect:true,
        thumb_image_overlay_type:"sepia"
    });
});

