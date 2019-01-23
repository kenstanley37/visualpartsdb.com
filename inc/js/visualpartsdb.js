
$(document).ready(function(){
    startup();
});

function startup(){
    navScroll();
    navClick();
    mainNavButton();
}

function navScroll(){
    //change nav bar design on scroll down
    $(window).scroll(function(){
       var scroll = $(window).scrollTop();
        if(scroll >= 100){
            $('.adminnav').addClass('shadow');
            $('.mainnav .mainNavLogo i').removeClass('none');
        } else {
            $('.adminnav').removeClass('shadow');
            $('.mainnav .mainNavLogo i').addClass('none');
        }
    });
} // end nav scroll

function navClick(){
    $('.mainNavlinks a').click(function(){
        $('.mainNavlinks a').removeClass('button1');
        $(this).addClass('button1');
    });
}