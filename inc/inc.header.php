<?php
/**
* The header of the site
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
    $user = new USER;
    $basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

    /** Check if the user is logged in and display the correct link */
    if(isset($_SESSION["fname"]))
            {
                $fname = $_SESSION['fname'];
                $userID = $_SESSION['user_id'];
                $loginCheck = $fname.'
                               <a class="fz12" href="/logout.php">(LOGOUT)</a>';
            } else
            {
                $loginCheck = '<a href="/login.php">Login</a>';
            }
?>

    <div class="logo float-left">
        <h1 class="text-light"><a href="/"><span>Visual Parts Database</span></a></h1>
    </div>

    <button type="button" class="nav-toggle"><i class="bx bx-menu"></i></button>
    <nav class="nav-menu">
        <ul>
          <li class="active"><a href="#header">Home</a></li>
          <li><a href="#about">About Us</a></li>
          <li><a href="#why-us">Why Us</a></li>
          <li class="drop-down"><a href="">Drop Down</a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="drop-down"><a href="#">Drop Down 2</a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
              <li><a href="#">Drop Down 5</a></li>
            </ul>
          </li>
          <li><a href="#contact">Contact Us</a></li>
        </ul>
      </nav><!-- .nav-menu -->

<!--

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
    
-->