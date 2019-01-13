$(document).ready(function(){
    start();
});

function start(){
    $("#slideshow > article:gt(0)").hide();

    setInterval(function() { 
      $('#slideshow > article:first')
        .fadeOut(1000)
        .next()
        .fadeIn(1000)
        .end()
        .appendTo('#slideshow');
    },  3000);
}
