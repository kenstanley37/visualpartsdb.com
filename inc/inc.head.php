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
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css" >
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" >
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/fontawesome.min.css" > <!--load all styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/c3-0.6.12/c3.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/reset.css" />
    <!-- CSS MENU -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/cssmenu/cssmenu.css" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="/vendor/DataTables/datatables.min.css"/>
    <!-- VisualPartsDB CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/visualpartsdb.css" />
    

    <!-- JavaScript -->
    <script src="/inc/js/jquery-3.3.1.min.js"></script>
    <script src="/vendor/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <!-- Graphs and Charts -->
    <script src="/vendor/d3/d3.min.js"></script>
    <script src="/vendor/c3-0.6.12/c3.min.js"></script>
    <!-- CSS MENU -->
    <script src="/inc/cssmenu/cssmenu.js"></script>
    <!-- DataTables -->
    <script src="/vendor/DataTables/datatables.min.js"></script>
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

    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>