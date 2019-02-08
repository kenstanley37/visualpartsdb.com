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
    $('.admin-bar').click(function(){
        //$('.admin-nav-links').toggleClass('nav-bar-show');
        $('.admin-nav-links').toggle();
        //$('.admin-nav-links').css('display','block');
    });
}

function mySearchCharts(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var usersID = '';
    userID = $('#users option:selected').val();
    if(userID == ''){
        userID = $('#tempID').val();
    }

    var data ={};
    data['dfrom'] = dfrom;
    data['dto'] = dto;
    data['userID'] = userID;
    $.ajax({
       type: "POST",
       url: url,
       data: data, // set $_POST.
       success: function(data)
       {
           console.log(data);
           chart = c3.generate({
               bindto: '#my-search-pie',
                data: {
        //          x: 'name',
                  json: data,
                  type: 'pie',
                },

              });
           
           chart = c3.generate({
               bindto: '#my-search-graph',
                data: {
        //          x: 'name',
                  json: data,
                  type: 'bar',
                },
               bar: {
                width: {
                    ratio: 1 // this makes bar width 50% of length between ticks
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