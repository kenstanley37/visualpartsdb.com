<?php
/**
* Admin Nav Bar
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
$basename = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>


<div class="admin-nav-links">
    <div id='cssmenu'>
        <ul>
            <li><a href='/admin/'>Dashboard</a>
            <li><a href='#'>User Management</a>
                <ul>
                    <li><a href='/admin/user.php'>Member List</a></li>
                    <li><a href='/admin/invite-user.php'>Invite User</a></li>
                    <li><a href='/admin/user-pending.php'>Pending</a></li>
                    <li><a href='/admin/requested-membership.php'>Requested</a></li>
                </ul>
            </li>
            <li><a href='#'>Part Management</a>
                <ul>
                    <li><a href='/admin/part-add.php'>Add Part</a></li>
                    <li><a href='/admin/modify-part.php'>Modify Part</a></li>
                    <li><a href="/admin/update-request.php?sku=active">Update Request</a></li>
                </ul>
            </li>
            <li><a href="/admin/search-history.php">Search History</a></li>
        </ul>
    </div>
</div>