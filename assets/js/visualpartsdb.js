$(document).ready(function(){
   var myIndex = 0;
    carousel();
});

function carousel() {
 $("#slideshow > article:gt(0)").hide();

    setInterval(function() { 
      $('#slideshow > article:first')
        .fadeOut(1000)
        .next()
        .fadeIn(1000)
        .end()
        .appendTo('#slideshow');
    },  5000);
}
