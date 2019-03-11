<?php
// get the php file being used. This will be used to set the class
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

<section class="admin-title">
    <h4>Administration</h4>
</section>
<section class="admin-nav-links">
    <section class="admin-link"> 
        <i class="fas fa-chart-bar"> <a href="/admin">Dashboard</a></i>
    </section>
    <section class="admin-link">
        <i class="fas fa-users"> <a href="/admin/user.php">User Management</a></i>
    </section>
    <section class="admin-link">
        <i class="fas fa-history"> <a href="/admin/search-history.php">Search History</a></i>
    </section>
    <section class="admin-link">
        <i class="fas fa-cog"> <a href="/admin/part-management.php">Part Management</a></i>
    </section>
    <section class="admin-link">
        <i class="fas fa-edit"> <a href="/admin/update-request.php?sku=active">Update Request</a></i>
    </section>
</section>
<section class="admin-nav-ham">
    <i class="fas fa-bars admin-bar"></i>
</section>