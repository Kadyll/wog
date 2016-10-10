$(window).scroll(function(){
    headerHeight = $('.header').height();
    if($(window).scrollTop() > headerHeight) {
        $('.main-nav').css('position', 'fixed');
        $('.main-nav').css('top', '0');
        $('.main-nav').css('width', '100%');
        $('.main-nav').css('marginBottom', '77x');
    }else{
        $('.main-nav').css('position', 'relative');
        $('.header').css('marginBottom', '0px');
    }
});

