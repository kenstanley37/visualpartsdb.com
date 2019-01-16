(function (d) {
    var w = d.documentElement.offsetWidth,
        t = d.createTreeWalker(d.body, NodeFilter.SHOW_ELEMENT),
        b;
    while (t.nextNode()) {
        b = t.currentNode.getBoundingClientRect();
        if (b.right > w || b.left < 0) {
            t.currentNode.style.setProperty('outline', '1px dotted red', 'important');
            console.log(t.currentNode);
        }
    };
}(document));

$(document).ready(function(){
   var myIndex = 0;
    //carousel();
    
    

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
