<?php
    $user = new USER;
?>
    <div class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a> 
    </div>
    <form class="search" action="/search.php" method="get">
        <label for="search">Search</label>
        <input type="text" name="search" id="search" placeholder="Enter Part Number">
    </form>
    <div class="name"><?php $user->isLogin(); ?></div>
