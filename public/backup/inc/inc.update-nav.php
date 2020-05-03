<?php
/**
* Nav Bar for SKU Update Page
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

<nav>
    <ul>
        <li><a href="/admin/update-request.php?sku=active" class="navLinks <?php if($basename == 'mysearches.php'){ echo 'button1';}?>">Active</a></li>
        <li><a href="/admin/update-request.php?sku=complete" class="navLinks <?php if($basename == 'myexportlist.php'){ echo 'button1';}?>">Complete</a></li>
    </ul>
</nav>