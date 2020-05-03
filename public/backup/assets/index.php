<?php
/**
* VIEW for user setting password after an invite
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");



$user = new USER;
$error='';



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Assets</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav">
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            include($path."inc/inc.adminnavbar.php");
        }
       ?>
        </aside>
        <main class="main">
            <section class="nav">

            </section>
            <section class="title">
                <h1>VPD Assets</h1>
            </section>
            <section class="form">
                <div class="w600 shadow bg-white">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Assets</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fz16">
                                    <a href="media-sources.docx" target="_blank">Media Sources</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fz16">
                                    <a href="dbw.jpg" target="_blank">Database Wireframe</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fz16">
                                    <a href="database-final.sql" target="_blank">Database Script</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="fz16">
                                    <a href="docs" target="_blank">PHP Documentation</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="content">
                
            </section>    
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>