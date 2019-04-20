


$(document).ready(function(){
    url = '/processors/ajax.php';
    startup();
});

function startup(){
    if($('#MyHistSearchCharts').length != 0) {
        adminSearchCharts();
    }
    
    if($('#mySearchCharts').length != 0) {
        mySearchCharts();
    }
    
    navScroll();
    hamburgerNav();
    datePickerSetup();
    myImgModal();
    
    if($("#dash5").length){
        top30days();
    }
    if($("#dash6").length){
        top7days();
    }
}
  
/* for the search page. when hovering over an image a larger image will display */
function myImgModal(){
    $( ".hover-change" ).hover(function() {
        img = $(this).attr('href');
        alt = $(this).children('img').attr('alt');
        $('.larger-img img').attr('src', img);
        $('.larger-img img').attr('alt', alt);
    });  
    
    $( ".hover-change" ).click(function(e) {
        e.preventDefault();
        img = $(this).attr('href');
        alt = $(this).children('img').attr('alt');
        $('.larger-img img').attr('src', img);
        $('.larger-img img').attr('alt', alt);
    });   
}

/* jQuery UI data picker setup */
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
    
/* changes the shadow on the header when the user scrolls */
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


// open and close menus on hamburger click;
function hamburgerNav(){
    $('.admin-bar').click(function(){
        $('.admin-nav-links').toggleClass('nav-show');
    });

    $('.main-nav-bars').click(function(){
        $('.main-nav-bar').toggleClass('nav-show');
    });
    
}

/* checks the width of the browser window */
function checkWidth(){
    var $window = $('window');
    var $pane = $('')
}



/* creates the charts for the users "My Searches" page */
function mySearchCharts(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var usersID = '';
    usersID = $('#tempID').val();

    var data ={};
    data['dfrom'] = dfrom;
    data['dto'] = dto;
    data['userID'] = usersID;
    data['MySearchCharts'] = 'MySearchCharts';
    
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
                legend: {
                    show: false
                },
              });
           
           chart = c3.generate({
               bindto: '#my-search-graph',
                data: {
        //          x: 'name',
                  json: data,
                  type: 'bar',
                },
               legend: {
                    show: false
                },
               bar: {
                    width: {
                        ratio: 1 // this makes bar width 50% of length between ticks
                    } 
                // or
                //width: 100 // this makes bar width 100px
                },
              });
       },
       error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        }
     });
}

/* creates the charts for the users "Search History" page */
function adminSearchCharts(){
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var usersID = '';
    usersID = $('#users option:selected').val();
    var data ={};
    data['MyHistSearchCharts'] = 'submit';
    data['dfrom'] = dfrom;
    data['dto'] = dto;
    data['userID'] = usersID;
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
                legend: {
                    show: false
                },
              });
           
           chart = c3.generate({
               bindto: '#my-search-graph',
                data: {
        //          x: 'name',
                  json: data,
                  type: 'bar',
                },
               legend: {
                    show: false
                },
               bar: {
                    width: {
                        ratio: 1 // this makes bar width 50% of length between ticks
                    } 
                // or
                //width: 100 // this makes bar width 100px
                },
              });
       },
       error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        }
     });
}

/* creates the charts for the dash board top 30 */
function top30days(){
    var data ={};
    data['top30days'] = 'top30days';
    $.ajax({
       type: "POST",
       url: url,
       data: data, // set $_POST.
       success: function(resultData)
       {
 
           console.log(resultData);
           
           chart = c3.generate({
               bindto: '#dash5',
                data: {
        //          x: 'name',
                  json: resultData,
                  type: 'pie',
                  order: 'desc',
                },
               legend: {
                    show: false
                },
               pie: {
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

/* creates the charts for the dash board top 7 */
function top7days(){
    var data ={};
    data['top7days'] = 'top7days';
    $.ajax({
       type: "POST",
       url: url,
       data: data, // set $_POST.
       success: function(resultData)
       {
 
           console.log(resultData);
           
           chart = c3.generate({
               bindto: '#dash6',
                data: {
        //          x: 'name',
                  json: resultData,
                  type: 'pie',
                  order: 'desc',
                },
               legend: {
                    show: false
                },
               pie: {
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