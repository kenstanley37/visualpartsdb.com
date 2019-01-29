<?php
    $user = new USER;
    $basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

    <section class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a>
    </section>
    <nav class="navlinks">
        <section><a href="/">Home</a></section>
        <section>
            <?php if(isset($_SESSION['user_id']))
        { 
            ?> 
            <a href="/user/mysearches.php" class="<?php if($basename == 'mysearches.php'){ echo 'button1';}?>">My Searches</a>
            <?php
        } ?>
        </section>
        <section>
        <?php if(isset($_SESSION['user_id']))
        { 
            ?>
            <a href="/user/myexportlist.php" class="<?php if($basename == 'myexportlist.php'){ echo 'button1';}?>">My List</a>
            <?php
        } ?>
        </section>
    </nav>
     <section class="searchbox">
        <form class="search" action="/search.php" method="get">
            <label for="search" hidden>Search</label>
            <input class="toupper" type="search" name="search" id="search" placeholder="search">
            <input type="submit" class="submit_3" value="Search" />
        </form>
    </section>
    <section class="name">
        <i class="fas fa-user"></i> <?php $user->isLogin(); ?>
    </section>

    <section class="admin-extra">
        <section class="admin-ham">
            <?php if($user->accessCheck() == 'ADMIN'){ echo '<i class="fas fa-bars adminnavbar"> Admin</i> ';} ?>
        </section>
    </section>