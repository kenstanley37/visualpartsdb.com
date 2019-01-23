<?php
// get the php file being used
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

<section class="navPartManagement">
    <a href="#" class="navLinks">Edit Data</a>
    <a href="#" class="navLinks">New Part</a>
</section>
<section class="navOther">
    <a href="#" class="navLinks">Most Searched</a>
</section>
<section class="navUserManagement">
    <a href="#" class="navLinks">User Management</a>
    <a href="#" class="navLinks">Analytics</a>
</section>