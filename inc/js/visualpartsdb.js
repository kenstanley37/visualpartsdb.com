$(document).ready(function(){
    startup();
});

function startup(){
    navScroll();
    adminNav();
    datePickerSetup();
  
}
  
function datePickerSetup(){
    $( function() {
        var dateFormat = "yy-mm-dd",
          from = $( "#dfrom" )
            .datepicker({
              defaultDate: "-1w", 
              dateFormat: "yy-mm-dd",
              changeMonth: true
            })
            .on( "change", function() {
              to.datepicker( "option", "minDate", getDate( this ) );
            }),
          to = $( "#dto" ).datepicker({
            changeMonth: true,
              dateFormat: "yy-mm-dd"
          })
          .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
          });

        function getDate( element ) {
          var date;
          try {
            date = $.datepicker.parseDate( dateFormat, element.value );
          } catch( error ) {
            date = null;
          }
          return date;
        }
      } );
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
        $('.admin-nav').toggleClass('show');
    });
}