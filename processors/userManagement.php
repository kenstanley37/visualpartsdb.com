<?php
    session_start();
    include("../inc/inc.path.php");
    require_once($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $vpd= new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;

    if(!isset($_SESSION['user_id']))
    {
        header("location: /noaccess.php");
    }

    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
    $rank = $user->getUserRole($userID);
    
    if($rank == 'ADMIN')
    {
        if(isset($_POST['activeSwitch']))
        {
            $userID = $_POST['activeSwitch'];
            $result = $user->activeSwitch($userID);
            header("location: /admin/user.php");
        }
        
        // Change user to USER role
        if(isset($_POST['setToUser']))
        {
            if($rank != 'ADMIN')
            {
                header("location: /noaccess.php");
            } else 
            {
                $userID = $_POST['userID'];
                $role = 1;
                $result = $user->setUserRole($userID, $role);
                header("location: /admin/user.php");
            }
        } 

        // Change user to ADMIN role
        if(isset($_POST['setToAdmin']))
        {
            if($rank != 'ADMIN')
            {
                header("location: /noaccess.php");
            } else 
            {
                $userID = $_POST['userID'];
                $role = 2;
                $result = $user->setUserRole($userID, $role);
                if($result)
                {
                    header("location: /admin/user.php");
                } else 
                {
                    header("location: /admin/user.php?error");
                }
            
            }
        }

        // Delete User
        if(isset($_POST['remUser']))
        {
            if($rank != 'ADMIN')
            {
                header("location: /noaccess.php");
            } else 
            {
            $userID = $_POST['userID'];
            $result = $user->remUser($userID);
            header("location: /admin/user.php");
            }
        }

        // Delete register request
        if(isset($_POST['remRegister']))
        {
            $regID = $_POST['recordID'];
            $result = $user->regDelete($regID);
            if($result)
            {
                header("location: /admin/requested-membership.php?delete=success");
            } else
            {
                header("location: /admin/requested-membership.php?delete=fail");
            }
        }
    }

    if($rank == 'USER' || $rank == 'ADMIN')
    {
        /**********************************************
        // Manage "My List" functions
        ***********************************************/
        // Add a list
        if(isset($_POST['listname']))
        {
            $listname = $_POST['listname'];
            $listdescription = $_POST['listdescription'];
            $listname = $vail->sanitizeString($listname);
            $listdescription = $vail->sanitizeString($listdescription);
            $user->MyListAdd($listname, $listdescription);
            header("location: /user/myexportlist.php");
        }

        // delete list (dangerous!!)
        if(isset($_POST['deletelist']))
        {
            $listID = $_POST['deletelist'];
            $user->myListDelete($listID);
            header("location: /user/myexportlist.php");
        }

        // Set a list to active
        if(isset($_POST['makeActive']))
        {
            $listID = $_POST['makeActive'];
            $user->myListActive($listID);
            header("location: /user/myexportlist.php");
        }

        // Add SKU to active list
        if(isset($_POST['addSkuToList']))
        {
            $skuID = $_POST['skuID'];
            $user->myListaddSku($skuID);
            header("location: /search.php?search=".$skuID);
        }

        // Remove SKU from list
        if(isset($_POST['remSkuFromList']))
        {
            $listID = $_POST['listID'];
            $skuID = $_POST['skuID'];
            $user->myListRemSku($skuID, $listID);
            if(isset($_POST['myListContent']))
            {
                header("location: /user/mylistcontents.php?list=".$listID);
            } else
            {
                header("location: /search.php?search=".$skuID);
            }
        }

        // Request SKU data update
        if(isset($_POST['requestUpdate']))
        {
            $skuID = $_POST['skuID'];
            $result = $user->requestUpdate($skuID);
            header("location: /search.php?search=".$skuID);
        }
    } // END user or admin check
    
?>