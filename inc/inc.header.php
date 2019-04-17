<?php
/**
* The header of the site
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
    $user = new USER;
    $basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    if(isset($_SESSION["fname"]))
            {
                $fname = $_SESSION['fname'];
                $userID = $_SESSION['user_id'];
                $loginCheck = $fname.'
                               <a class="fz12" href="/logout.php">Logout</a>';
            } else
            {
                $loginCheck = '<a href="/login.php">Login</a>';
            }
    
?>

    <div class="main-logo">
        <a href="/"><h1 class="heading"><i class="fas fa-images"> </i>  Visual Parts Database</h1></a>
    </div>
    <div class="main-nav-bar">
        <nav class="navlinks">
            <div>
                <?php if(isset($_SESSION['user_id']))
            { 
                ?> 
                <a href="/user/mysearches.php" class="<?php if($basename == 'mysearches.php'){ echo 'button1';}?>">My Searches</a>
                <?php
            } ?>
            </div>
            <div>
            <?php if(isset($_SESSION['user_id']))
            { 
                ?>
                <a href="/user/myexportlist.php" class="<?php if($basename == 'myexportlist.php'){ echo 'button1';}?>">My List</a>
                <?php
            } ?>
            </div>
        </nav>
         <div class="searchbox">
            <form class="search" action="/search.php" method="get">
                <input class="toupper" type="search" name="search" id="nav-search" placeholder="search">
                <input type="submit" class="submit_3" value="Search" />
            </form>
        </div>
        <div class="name">
            <i class="fas fa-user"></i> <?php echo $loginCheck; ?>
        </div>
    </div>
    <div class="main-nav-ham">
        <i class="fas fa-bars main-nav-bars"></i>
    </div>
    