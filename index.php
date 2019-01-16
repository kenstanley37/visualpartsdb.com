<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php require_once("inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        <section class="banner">
            <section class="text">
                <h1 class="banner-text">Electrolux, Frigidaire, and more. Start your search today!</h1>
            </section>
        </section>
        <main class="main">
            <section id="slideshow">
                <article>
                    <img class="article-img" src="/assets/images/slideshow1.jpg" hidden>
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow2.jpg">
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow3.jpg" hidden>
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow4.jpg" hidden>
                </article>
            </section>
            <section id="staticImg">
                    <img class="article-img" src="/assets/images/slideshow1-icon.jpg">
                    <img class="article-img" src="/assets/images/slideshow2-icon.jpg">
                    <img class="article-img" src="/assets/images/slideshow3-icon.jpg">
                    <img class="article-img" src="/assets/images/slideshow4-icon.jpg">
            </section>
        </main>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
</body>
</html>