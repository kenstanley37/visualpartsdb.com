<?php
    /**
    * Author - Ken Stanley
    * File Name - inc.header.php
    * Revision Date - April, 10 2019
    */
    $user = new USER;
    $basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    if(isset($_SESSION["fname"]))
            {
                $fname = $_SESSION['fname'];
                $userID = $_SESSION['user_id'];
                $loginCheck = $fname.'
                               <a href="/logout.php">Logout</a>';
            } else
            {
                $loginCheck = '<a href="/login.php">Login</a>';
            }
    
?>

    <section class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"></i> Visual Parts Database</h1></a>
    </section>
    <section class="main-nav-bar">
        <nav class="navlinks">
            <!--
            <section><a href="/" class="<?php if($basename == ''){ echo 'button1';}?>">Home</a></section>
            -->
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
                <input class="toupper" type="search" name="search" id="nav-search" placeholder="search">
                <input type="submit" class="submit_3" value="Search" />
            </form>
        </section>
        <section class="name">
            <i class="fas fa-user"></i> <?php echo $loginCheck; ?>
        </section>
    </section>
    <section class="main-nav-ham">
        <i class="fas fa-bars main-nav-bars"></i>
    </section>
    