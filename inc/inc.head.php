<?php
/**
* The head of the site
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/

    // google analytics tracking by user
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- CSS MENU -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/cssmenu/cssmenu.css" />
    <!-- BOXICONS -->
    <link href="/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">


    <!-- VisualPartsDB CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="/inc/css/visualpartsdb.css" />


    <!-- Graphs and Charts -->
    <script src="/vendor/d3/d3.min.js"></script>
    <script src="/vendor/c3-0.6.12/c3.min.js"></script>
    <!-- CSS MENU -->
    <!--
    <script src="/inc/cssmenu/cssmenu.js"></script>
        -->
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

    <!-- auto google ads -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <script> (adsbygoogle = window.adsbygoogle || []).push({ google_ad_client: "ca-pub-1367051821928582", enable_page_level_ads: true }); </script>

    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>