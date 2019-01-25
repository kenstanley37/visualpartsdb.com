
$(document).ready(function(){
    startup();
});

function startup(){
    navScroll();
    adminNav();
    if($('.adminnav').is(":visible")){
        $('.index-main').addClass('index-main-tog');
    } else  {
        $('.index-main').removeClass('index-main-tog');
    }
}

function navScroll(){
    //change nav bar design on scroll down
    $(window).scroll(function(){
       var scroll = $(window).scrollTop();
        if(scroll >= 100){
            $('header').addClass('shadow');
        } else {
            $('header').removeClass('shadow');
        }
    });
} // end nav scroll

function adminNav(){
    $('.admin-ham').click(function(){
       //$('.adminnav').toggleClass('toggle', 1000);
        //$('.adminnav').hide('1000');
        $('.adminnav').toggle(1000, function(){
            /*
            if($('.adminnav').is(":visible")){
                $('main').addClass('index-main-tog');
            } else  {
                $('main').removeClass('index-main-tog');
            }
            */

        });    

    });
   
}