
$(document).ready(function(){
    startup();
});

function startup(){
    navScroll();
    adminNav();
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
         $('.adminnav').toggle('slow');
    });
   
}