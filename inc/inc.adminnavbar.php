<?php
// get the php file being used. This will be used to set the class
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>


<section class="admin-nav-links">
    <section id='cssmenu'>
        <ul>
            <li><a>Administration</a></li>
            <li><a href='/admin/'>Dashboard</a>
            <li><a href='#'>User Management</a>
                <ul>
                    <li><a href='/admin/user.php'>Member List</a></li>
                    <li><a href='/admin/invite-user.php'>Invite User</a></li>
                    <li><a href='#'>Pending</a></li>
                </ul>
            </li>
            <li><a href='#'>Part Management</a>
                <ul>
                    <li><a href='/admin/part-add.php'>Add Part</a></li>
                    <li><a href='#'>Modify Part</a></li>
                    <li><a href="/admin/update-request.php?sku=active">Update Request</a></li>
                </ul>
            </li>
            <li><a href="/admin/search-history.php">Search History</a></li>
        </ul>
    </section>
</section>