
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
            $('.mainnav').addClass('shadow');
            $('.mainnav .mainNavLogo i').removeClass('none');
        } else {
            $('.mainnav').removeClass('shadow');
            $('.mainnav .mainNavLogo i').addClass('none');
        }
    });
} // end nav scroll

/*
function mainNavButton(){
    var hash = location.hash;
    $('.mainNavlinks a').each(function(){
        if($(this).attr('href').includes(hash)){
                $(this).addClass('button1');
           } else {
               $(this).removeClass('button1');
           } 
    });
}
*/
function navClick(){
    $('.mainNavlinks a').click(function(){
        $('.mainNavlinks a').removeClass('button1');
        $(this).addClass('button1');
    });
}