<?php
// get the php file being used. This will be used to set the class
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

<article class="admin-links">
    <section class="admin-title"><h4>Administration</h4></section>
    <section class="admin-link"> <i class="fas fa-chart-bar"><a href="/admin">Dashboard</a></i></section>
    <section class="admin-link"> <i class="fas fa-plus-square"><a href="#">New Part</a></i></section>
    <section class="admin-link"><i class="fas fa-search-plus"><a href="#">Most Searched</a></i></section>
    <section class="admin-link"><i class="fas fa-chart-pie"><a href="#">Analytics</a></i></section>
    <section class="admin-link"><i class="fas fa-users-cog"><a href="/admin/member.php">User Management</a></i></section>
</article>

