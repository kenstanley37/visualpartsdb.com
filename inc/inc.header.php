<?php
    $user = new USER;
?>
    <section class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a> 
    </section>
    <section class="searchbox">
        <form class="search" action="/search.php" method="get">
            <label for="search" hidden>Search</label>
            <input class="toupper" type="text" name="search" id="search" placeholder="search">
        </form>
    </section>
    <section class="name">
        <?php $user->isLogin(); ?>
    </section>