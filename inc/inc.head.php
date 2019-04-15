<?php
/**
* The head of the site
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
    $useridgtag = '';
    
    if(isset($_SESSION['user_id']))
    {
        $useridgtag = $_SESSION['user_id'];
    } else {
        $useridgtag = "Anonymous";
    }

     
?>
    <!-- Google Adsense -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
         (adsbygoogle = window.adsbygoogle || []).push({
              google_ad_client: "ca-pub-1367051821928582",
              enable_page_level_ads: true
         });
    </script>
    <!-- META -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <!-- jQuery -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jquery-ui-1.12.1.custom/jquery-ui.min.css" >
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/fontawesome.min.css" > <!--load all styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/c3-0.6.12/c3.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/reset.css" />
    <!-- CSS MENU -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/cssmenu/cssmenu.css" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/ju/jq-3.3.1/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>

    <!-- VisualPartsDB CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/visualpartsdb.css" />

    <!-- JavaScript -->
    <script src="/inc/js/jquery-3.3.1.min.js"></script>
    <script src="/vendor/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/ju/jq-3.3.1/jszip-2.5.0/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>
    <!-- Graphs and Charts -->
    <script src="/vendor/d3/d3.min.js"></script>
    <script src="/vendor/c3-0.6.12/c3.min.js"></script>
    <!-- CSS MENU -->
    <script src="/inc/cssmenu/cssmenu.js"></script>
    <!-- Visual Parts DB JS -->
    <script src="/inc/js/visualpartsdb.js"></script>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132361266-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('set', {'user_id': '<?php echo $useridgtag; ?>'}); // Set the user ID using signed-in user_id.
        gtag('config', 'UA-132361266-1');
    </script>

     <script src="https://www.google.com/recaptcha/api.js?render=6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>

    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>