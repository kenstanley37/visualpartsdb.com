<?php
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
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/jquery-ui-1.12.1.custom/jquery-ui.min.css" >
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/fontawesome.min.css" > <!--load all styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="/vendor/c3-0.6.12/c3.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/reset.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/visualpartsdb.css" />
    

    <!-- JavaScript -->
    <script src="/inc/js/jquery-3.3.1.min.js"></script>
    <script src="/vendor/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <script src="/vendor/d3/d3.min.js"></script>
    <script src="/vendor/c3-0.6.12/c3.min.js"></script>
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