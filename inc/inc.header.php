<?php
    $user = new USER;
    $basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>
    <section class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a>
        <i class="fas fa-images none"> <a href="/">VPD</a> </i>
    </section>
    <section class="navlinks">
        <section><a href="/">Home</a></section>
        <section>
            <?php if(isset($_SESSION['user_id']))
        { 
            ?> 
            <a href="/user/mysearches.php?searchhist" class="<?php if($basename == 'mysearches.php'){ echo 'button1';}?>">My Searches</a>
            <?php
        } ?>
        </section>
        <section>
        <?php if(isset($_SESSION['user_id']))
        { 
            ?>
            <a href="/user/myexportlist.php?mylist" class="<?php if($basename == 'myexportlist.php'){ echo 'button1';}?>">My List</a>
            <?php
        } ?>
        </section>
    </section>
     <section class="searchbox">
        <form class="search" action="/search.php" method="get">
            <label for="search" hidden>Search</label>
            <input class="toupper" type="text" name="search" id="search" placeholder="search"><i class="fas fa-search"></i>
        </form>
    </section>
    <section class="name">
        <?php $user->isLogin(); ?>
    </section>