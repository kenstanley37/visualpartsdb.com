<?php
    /**
    * Author - Ken Stanley
    * File Name -inc.mainnavbar.php
    * Revision Date - April, 10 2019
    */
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>


<section class="mainNavLogo">
    <i class="fas fa-images none"> <a href="/">VPD</a> </i>
</section>
<section class="mainNavlinks">
    <!--
    <a href="/" class="navLinks <?php if($basename == ''){ echo 'button1';}?>"><?php echo $basename; ?>VPD Home</a>
    -->
    <?php if(isset($_SESSION['user_id']))
    { 
        ?> 
        <a href="/user/mysearches.php?searchhist" class="navLinks <?php if($basename == 'mysearches.php'){ echo 'button1';}?>">My Searches</a>
        <?php
    } ?> 
    <?php if(isset($_SESSION['user_id']))
    { 
        ?>
        <a href="/user/myexportlist.php?mylist" class="navLinks <?php if($basename == 'myexportlist.php'){ echo 'button1';}?>">My List</a>
        <?php
    } ?>
</section>
<section class="mainNavInfo"></section>