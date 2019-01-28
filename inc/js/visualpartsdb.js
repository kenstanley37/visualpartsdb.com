$(document).ready(function(){
    url = '/processors/ajax.php';
    startup();
});

function startup(){
    navScroll();
    adminNav();
    datePickerSetup();
    mySearchCharts();
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

function mySearchCharts(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var usersID = '';
    usersID = $('#users option:selected').val();
    if(usersID == ''){
        usesrID = 'NONE';
    }
    
    //alert(usersID);
    
    var data ={};
    data['dfrom'] = dfrom;
    data['dto'] = dto;
    data['usersID'] = usersID;
    $.ajax({
       type: "POST",
       url: url,
       data: data, // set $_POST.
       //dataType: 'json',
       success: function(data)
       {
           //dude = jQuery.parseJSON(data);
           console.log(data);
           chart = c3.generate({
                data: {
        //          x: 'name',
                  json: data,
                  type: 'pie',
                },
               bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            }

              });
       },
       error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        }
     });
    
}