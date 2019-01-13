<?php
    $user = new USER;
?>

<header>
    <div class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a> 
    </div>
    <div class="search">
        <input class="searchbox" type="text" placeholder="Search..">
    </div>
    <div class="name"><?php $user->isLogin(); ?></div>
</header>