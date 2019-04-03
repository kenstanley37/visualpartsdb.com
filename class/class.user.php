<?php
    require_once('class.db.php');
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


    class USER
    {
        private $conn;

        // *************************************************************
        // Constructor to connect to the database
        // *************************************************************
        public function __construct()
        {
            try 
            {
                $database = new Database();
                $db = $database->dbConnection();
                $this->conn = $db;
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets the error mode
            } catch (PDOException $e)
            {
                echo "Connection error: " . $e->getMessage(); // return error message
            }
        } // end construct
        
        // *************************************************************
        // Destruct - end database connection
        // *************************************************************
        public function __destruct()
        {
            $this->conn = null;
        } // end destruct 
        
        /**
        * This function logs the user in by setting the session
        *
        * @param Place   $umail  the users email address
        * @param integer $upass the users password
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function doLogin($umail,$upass)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_email=:umail ");
                $stmt->execute(array(':umail'=>$umail));
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                // if email if found check password
                if($stmt->rowCount() == 1)
                {
                    // if password matches then successful login
                    if(password_verify($upass, $userRow['user_password']))
                    {
                        $_SESSION['user_id'] = $userRow['user_id'];
                        $_SESSION['fname'] = $userRow['user_fName'];
                        $_SESSION['lname'] = $userRow['user_lName'];
                    
                        if(isset($_SESSION['emailcheck'])){
                            unset($_SESSION['emailcheck']);
                        }
                        $access = $this->accessCheck();
                        if($access == 'ADMIN')
                        {
                            header('Location: /admin');
                        } else 
                        {
                            header('Location: /user/myexportlist.php');
                        }
                        return true;
                    }
                    else
                    {
                        header('Location: /login.php?error=invalidpassword');
                        return false;
                    }
                } else 
                {
                    header('Location: /login.php?error=notregistered');
                    return false;
                }
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end doLogin
        
        /**
        * Returns the user role name
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return role_name (USER or ADMIN)
        */
        public function accessCheck()
        {
            if(isset($_SESSION['user_id']))
            {
                $user_id = $_SESSION['user_id'];
                try
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user 
                    left join role on user_role_id = role_id
                    WHERE user_id=:uid");
                    $stmt->bindparam(":uid", $user_id);
                    $stmt->execute();
                    $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                    // if email if found check password
                    if($stmt->rowCount() == 1)
                    {
                        return $userRow['role_name'];
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        } // end accessCheck
        
        /**
        * Checks if the user has been disabled
        *
        * @param interger $userID the users ID number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return send to location /user/disabled.php or true
        */
        public function activeCheck($userID)
        {
            if(isset($_SESSION['user_id']))
            {
                $uid = $_SESSION['user_id'];
                try
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user 
                    WHERE user_id=:uid");
                    $stmt->bindparam(":uid", $uid);
                    $stmt->execute();
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    $status = $userRow['user_active'];
                    // if email if found check password
                    if($status == 0)
                    {
                        header("location: /user/disabled.php");
                    }
                    else
                    {
                        return true;
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        } // end activeCheck
        
            /**
            * Adds a new user and sends email verification
            *
            * @param string $fname users first name
            * @param string $lname users last name
            * @param string $email users email address
            * @param string $company users company
            * 
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true or pdo error
            */
        
            public function addUserVerify($fname, $lname, $email, $company)
            {
                
                $admin = $_SESSION['user_id'];
                $adminName = $this->userFullName($admin);
                
                $fname = strtolower($fname);
                $fname = ucfirst($fname);
                
                $lname = strtolower($lname);
                $lname = ucfirst($lname);
                
                $email = strtolower($email);
                
                $role = 1; // default to normal user
                
                $status = 1; // 1 = active user

                //Load Composer's autoloader
                require '../vendor/autoload.php';
                // for user registeration
                $code = $this->code();
                
                $mail = new PHPMailer(true);                              // Passing `true` enables xceptions
                
                try {
                    $stmt = $this->conn->prepare("INSERT INTO user (user_fName, user_lName, user_email, user_company, user_active, user_role_id, user_added_by, user_code) VALUES(:fname, :lname, :email, :company, :status, :role, :admin, :code)");

                    $stmt->bindparam(":fname", $fname);
                    $stmt->bindparam(":lname", $lname);
                    $stmt->bindparam(":email", $email);
                    $stmt->bindparam(":company", $company);
                    $stmt->bindparam(":status", $status);
                    $stmt->bindparam(":role", $role);
                    $stmt->bindparam(":admin", $admin);
                    $stmt->bindparam(":code", $code);
                    
                    $stmt->execute();	
                    $db_id = $this->conn->lastInsertId();
                    
                    
                    //Server settings
                    $mail->SMTPDebug = 0;                           // Enable verbose debug output
                    $mail->isSMTP();                                // Set mailer to use SMTP
                    $mail->Host = 'visualpartsdb.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                         // Enable SMTP authentication
                    $mail->Username = 'register@visualpartsdb.com';     // SMTP username
                    $mail->Password = '#r.MTs%{@OEy';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                              // TCP port to connect to

                    //Recipients
                    $mail->setFrom('register@visualpartsdb.com', 'Visual Parts Database');
                    $mail->addAddress($email, $fname.' '.$lname);     // Add a recipient
                    //$mail->addAddress($email);               // Name is optional
                    $mail->addReplyTo('register@visualpartsdb.com', 'NoReply');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$mail->addAttachmedsnt('/tmp/image.jpg', 'new.jpg');    // Optional name

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Welcome to Visual Parts Database';
                    $mail->Body    = 'Hello '.$fname.', <br><br> You have been invited by <b>'.$adminName.'</b> to be a user of Visual Parts Database. <br><br>Your Activation Code is: <b>'.$code.'</b><br><br> Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to activate your account.';
                    $mail->AltBody = 'Your Activation Code is: '.$code.' Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to activate your account.';

                    $mail->send();
                    return true;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        
            /**
            * Password reset function. Sends an email to the user to reset the password
            *
            * @param string $email  the users email address
            * 
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true or false
            */
            public function sendPassLink($email)
            {

                //Load Composer's autoloader
                require '../vendor/autoload.php';
                // for user registeration
                $code = $this->code();
                
                $mail = new PHPMailer(true);                              // Passing `true` enables xceptions
                
                try 
                {
                    $stmt = $this->conn->prepare("SELECT * from user where user_email =:email");
                    $stmt->bindparam(":email", $email);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $db_id = $row['user_id'];
                    $rowCount = $stmt->rowCount();
                    if($rowCount >= 1)
                    {
                        try 
                        {
                            $update = $this->conn->prepare("UPDATE user SET user_code = :code 
                                WHERE user_email = :email");
                            $update->bindparam(":email", $email);
                            $update->bindparam(":code", $code);
                            $update->execute();	

                            //Server settings
                            $mail->SMTPDebug = 0;                           // Enable verbose debug output
                            $mail->isSMTP();                                // Set mailer to use SMTP
                            $mail->Host = 'visualpartsdb.com';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                         // Enable SMTP authentication
                            $mail->Username = 'register@visualpartsdb.com';     // SMTP username
                            $mail->Password = '#r.MTs%{@OEy';                           // SMTP password
                            $mail->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 465;                              // TCP port to connect to

                            //Recipients
                            $mail->setFrom('register@visualpartsdb.com', 'Visual Parts Database');
                            $mail->addAddress($email, $fname.' '.$lname);     // Add a recipient
                            //$mail->addAddress($email);               // Name is optional
                            $mail->addReplyTo('register@visualpartsdb.com', 'NoReply');
                            //$mail->addCC('cc@example.com');
                            //$mail->addBCC('bcc@example.com');

                            //Attachments
                            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                            //Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'Visual Parts Database Password Reset';
                            $mail->Body    = 'Hello '.$fname.', <br><br> Someone has requested a password reset for your acount. If you did not do this, please ignore.  <br><br>Your Activation Code is: <b>'.$code.'</b><br><br> Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to activate your account.';
                            $mail->AltBody = 'Your Password Reset Code is: '.$code.' Please click on this link https://visualpartsdb.com/user/password_reset.php?id='.$db_id.'&code='.$code.' to change your password.';

                            $mail->send();
                            return true;
                        } catch (Exception $e) 
                        {
                            echo $e->getMessage();
                        }
                        return true;
                    }
                    else
                    {
                        return 'Email Not Found';
                    }
                    
                } catch (Exception $e) 
                {
                    echo $e->getMessage();
                }

            }

        
            /**
            * Generates a code for use in password reset, new user, etc.
            *
            * 
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return generate code
            */
        
            public function code()
            {
                $code = substr(md5(mt_rand()),0,15);
                return $code;
            }
        
        
            /**
            * Password reset verification. Updates the database user_verify field
            *
            * @param integer $userID the users ID from the database
            * @param string  $code the code from the email verification
            * 
            * @author Ken Stanley <ken@stanleysoft.org>
            * @return true, noaccount, no record found
            */
            public function checkVerify($userID, $code)
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT * from user where user_id=:id and user_code=:code");							  
                    $stmt->bindparam(":id", $userID);
                    $stmt->bindparam(":code", $code);
                    $stmt->execute();	
                    if($stmt->rowCount() == 1){
                        $row = $stmt->fetch();
                        $userFName = $row['user_fName'];
                        $userLName = $row['user_lName'];
                        $userEmail = $row['user_email'];
                        $existsCheck = $this->checkID($userEmail);
                        
                        if($existsCheck)
                        {
                            $verify = 1;
                            try
                            {
                                $adduser = $this->conn->prepare("UPDATE user SET user_verify = :verify
                                    WHERE user_id = :userID");
                                $adduser->bindparam(":verify", $verify);
                                $adduser->bindparam(":userID", $userID);
                                $adduser->execute();
                                return true;
                            } catch(PDOException $e)
                            {
                                echo $e->getMessage();
                            } 
                        } else
                        {
                            return 'noaccount';
                        } 
                    } else {
                      echo 'No record found';
                    }
                     return true;
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
            }
            
        /**
        * Checks if an email is already in the database
        *
        * @param string $email  the users email address
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function checkID($email)
        {
            try 
            {
                $stmt = $this->conn->prepare("SELECT * from user WHERE user_email = :email");
                $stmt->bindparam(":email", $email);
                $stmt->execute();
                $rowCount = $stmt->rowCount();
                if($rowCount >= 1)
                {
                    return true;
                } else
                {
                    return false;
                }
            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        
        /**
        * Updates the password field in the user database
        *
        * @param integer $userID the users database ID
        * @param integer $password the users new password
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true
        */
        public function updatePassword($userID, $password){
            $password = password_hash($password, PASSWORD_DEFAULT);
            try 
            {
                $stmt = $this->conn->prepare("UPDATE user SET user_password=:password, user_code = null
                    WHERE user_id=:userid ");
                $stmt->bindparam(":userid", $userID);
                $stmt->bindparam(":password", $password);
                $stmt->execute();
                
                return true;
            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        /**
        * Removes all traces of the user from the database. 
        *
        * @param integer $userid the users database ID
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function remUser($userid){
            try
            {
                $stmt = $this->conn->prepare("DELETE user_part_list_skus FROM user_part_list_skus
                    LEFT join user_part_list on pl_id = pls_list_id 
                    WHERE pl_user_id=:userid ");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                try
                {
                    $stmt = $this->conn->prepare("DELETE FROM user_part_list
                        WHERE pl_user_id=:userid ");
                    $stmt->bindparam(":userid", $userid);
                    $stmt->execute();
                        try
                        {
                            $stmt = $this->conn->prepare("DELETE FROM sku_update_request
                                WHERE update_request_by=:userid ");
                            $stmt->bindparam(":userid", $userid);
                            $stmt->execute();
                                    try 
                                    {
                                        $stmt = $this->conn->prepare("DELETE FROM sku_search WHERE sku_search_by=:userid ");
                                        $stmt->bindparam(":userid", $userid);
                                        $stmt->execute();
                                            try 
                                            {
                                                $stmt = $this->conn->prepare("DELETE FROM user WHERE user_id=:userid ");
                                                $stmt->bindparam(":userid", $userid);
                                                $stmt->execute();
                                                return true;
                                            } catch(PDOException $e)
                                            {
                                                echo $e->getMessage();
                                            }
                                    } catch(PDOException $e)
                                    {
                                        echo $e->getMessage();
                                    }
                        } catch(PDOException $e)
                        {
                            echo $e->getMessage();
                        }	  
                } catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	

            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }		
        }
        
        /**
        * Sets the $_SESSION after login
        *
        * @param integer $email the users database ID
        * @param string $fname the users first name
        * @param string $lname the users last name
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true
        */
        
        public function setSession($userID, $fname, $lname)
        {
            $_SESSION['user_id'] = $userID;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            return true;
        }
        
        /**
        * Returns a list of all users
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return array of users
        */
        
        public function getUserList()
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user");
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end dropDownUser
        
        
        /**
        * Returns the users First and Last name as one field
        *
        * @param integer $userID the users database ID
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return $fullname
        */
        public function userFullName($userID)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user
                    WHERE user_id = :userid");
                $stmt->bindparam(":userid", $userID);
                $stmt->execute();
                $row = $stmt->fetch();
                $fullName = $row['user_fName'].' '.$row['user_lName'];
                return $fullName;
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end userFullName
        
        /**
        * Returns a list of all companies as an array
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return array of companies
        */
        public function dropDownCompany()
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM company");
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end dropDownCompany
        
        /**
        * Returns an array of users based on status
        *
        * @param string $userID can be 'active' 'pending' or 'disabled'
        * will return data based on selection
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return array of results
        */
        public function userList($type)
        {
            try
            {
                if($type == 'active')
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user
                        LEFT JOIN company on company_id = user_company
                        LEFT JOIN role on role_id = user_role_id
                        WHERE user_verify = 1");
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } elseif ($type == 'pending')
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user
                        LEFT JOIN company on company_id = user_company
                        LEFT JOIN role on role_id = user_role_id
                        WHERE user_verify is null ");
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } elseif ($type == 'disabled')
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user
                        LEFT JOIN company on company_id = user_company
                        LEFT JOIN role on role_id = user_role_id
                        WHERE user_active = 0");
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end userList
        
        /**
        * Toggles the users active status in the database
        *
        * @param integer $userID the users database ID
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true
        */
        public function activeSwitch($userID)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user where user_id = :userid");
                $stmt->bindparam(":userid", $userID);
                $stmt->execute();
                $row = $stmt->fetch();
                
                if($row['user_active'] == 1){
                    $change = 0;
                } else {
                    $change = 1;
                }
                
                try 
                {
                    $update = $this->conn->prepare("UPDATE user SET user_active = :change where user_id = :userid");
                    $update->bindparam(":userid", $userID);
                    $update->bindparam(":change", $change);
                    $update->execute();
                    return true;
                } 
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end activeSwitch
        
         /**
        * Records register request into the database
        *
        * @param string $fname the users first name
        * @param string $lname the users last name
        * @param string $email the users email address
        * @param int $phone the users phone number
        * @param string $company the users company
        * @param string $message the users request message
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return 'success' or 'alreadyrequested' or'alreadyregistered'
        */
        public function registerRequest($fname, $lname, $email, $phone, $company, $message)
        {
                try
                {
                    // Lets check if the user is already registered
                    $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_email=:user_email");
                    $stmt->bindparam(":user_email", $email);
                    $stmt->execute();
                    $rowCount = $stmt->rowCount();
                    if($rowCount >= 1)
                    {
                        return 'alreadyregistered';
                    } else {
                        // So the user isn't registered. Lets check if they have already requested to register
                        try
                        {
                            $stmt = $this->conn->prepare("SELECT * FROM register_request WHERE rr_email=:user_email");
                            $stmt->bindparam(":user_email", $email);
                            $stmt->execute();
                            if($stmt->rowCount() == 1)
                            {
                                return 'alreadyrequested';
                            } else {
                                try
                                {
                                    $stmt = $this->conn->prepare("INSERT INTO register_request (rr_fname, rr_lname, rr_email, rr_phone, rr_company, rr_message) VALUES (:fname, :lname, :email, :phone, :company, :message)");
                                    $stmt->bindparam(":fname", $fname);
                                    $stmt->bindparam(":lname", $lname);
                                    $stmt->bindparam(":email", $email);
                                    $stmt->bindparam(":phone", $phone);
                                    $stmt->bindparam(":company", $company);
                                    $stmt->bindparam(":message", $message);
                                    $stmt->execute();
                                    return 'success';

                                }
                                catch(PDOException $e)
                                {
                                    echo $e->getMessage();
                                }
                            }
                        }
                        catch(PDOException $e)
                        {
                            echo $e->getMessage();
                        }
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
        } // end registerRequest
        
        
        /**
        * Returns an array of users that have requested membership
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return $result array
        */
        public function regRequestList()
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM register_request");
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end regRequestList
        
         /**
        * Deletes register request from the database
        *
        * @param integer $regID the ID from the database of the register request
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function regDelete($regID)
        {
            try
            {
               $stmt = $this->conn->prepare("DELETE from register_request
                WHERE rr_id = :regID"); 
                $stmt->bindparam(":regID", $regID);
                $stmt->execute();
                return true; 
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        
        
         /**
        * Returns an array of the users SKU list
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return $result array
        */
        public function myList()
        {
            $userid = $_SESSION['user_id'];
            $listactive = '';
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user_part_list
                        where pl_user_id = :userid ");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myList
        
        /**
        * Records how many records are in the users list
        *
        * @param integer $listID the users list ID from the database
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return integer row count
        */
        public function myListCount($listID)
        {
            $li = $listID;
            try
            {
                $stmt = $this->conn->prepare("SELECT count(*) as count from user_part_list_skus
                    WHERE pls_list_id = :listid");
                $stmt->bindparam(":listid", $li);
                $stmt->execute();
                $row = $stmt->fetch();
                
                return $row['count'];
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListCount
        
         /**
        * Deletes a list and the assoicated SKUs from the user_part_list_skus table
        *
        * @param integer $listID the users list ID from the database
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function myListDelete($listID)
        {
            $li = $listID;
            try
            {
                $stmt = $this->conn->prepare("DELETE from user_part_list_skus
                    WHERE pls_list_id = :listid");
                $stmt->bindparam(":listid", $li);
                $stmt->execute();
                
                 $stmt = $this->conn->prepare("DELETE from user_part_list
                    WHERE pl_id = :listid");
                $stmt->bindparam(":listid", $li);
                $stmt->execute();
                
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListDelete
        
        
         /**
        * Records a new user list in the database then sets it as active
        *
        * @param string $listname the users list name
        * @param string $listdescription the users list description
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function myListAdd($listname, $listdescription)
        {
            $userid = $_SESSION['user_id'];
            $ln = $listname;
            $ld = $listdescription;
            $active = 1;
            $this->myListDeActive();
            try
            {
                $stmt = $this->conn->prepare("INSERT INTO user_part_list (pl_user_id, pl_list_name, pl_list_desc, pl_active) VALUES(:userid, :listname, :listdescription, :pl_active)");
                $stmt->bindparam(":userid", $userid);
                $stmt->bindparam(":listname", $ln);
                $stmt->bindparam(":listdescription", $ld);
                $stmt->bindparam(":pl_active", $active);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListAdd
        
         /**
        * Removes the user active list then sets selected list as active
        *
        * @param string $listid the users list ID from the database
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function myListActive($listid)
        {
            $userid = $_SESSION['user_id'];
            $this->myListDeActive();
            try
            {
                $stmt = $this->conn->prepare("UPDATE user_part_list set pl_active = 1
                    WHERE pl_user_id = :userid and pl_id = :listid");
                $stmt->bindparam(":userid", $userid);
                $stmt->bindparam(":listid", $listid);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }

        } // end myListActive
        
         /**
        * Removes all of the user list active status
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function myListDeActive()
        {
            $userid = $_SESSION['user_id'];
            try
            {
                $stmt = $this->conn->prepare("UPDATE user_part_list set pl_active = null
                    WHERE pl_user_id = :userid");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListDeActive
        
         /**
        * Returns the name of a user list 
        *
        * @param string $listid can be list ID from the database or 'none'
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return integer list id
        */
        public function getMyListName($listid)
        {
            if(isset($_SESSION['user_id']))
            {
                $userid = $_SESSION['user_id'];
            }
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user_part_list
                    WHERE pl_user_id = :userid and pl_id = :pl_id");
                $stmt->bindparam(":userid", $userid);
                $stmt->bindparam(":pl_id", $listid);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row['pl_id'];
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListReturnActive
        
        /**
        * Returns the users active list name 
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return string list name
        */
        public function getMyActiveListName()
        {
            if(isset($_SESSION['user_id']))
            {
                $userid = $_SESSION['user_id'];
            }
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user_part_list
                    WHERE pl_user_id = :userid and pl_active = 1");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row['pl_list_name'];
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListReturnActive
        
         /**
        * Returns the ID of the users active list
        *
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return integer list id
        */
        public function getMyActiveListID()
        {
            if(isset($_SESSION['user_id']))
            {
                $userid = $_SESSION['user_id'];
            }
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user_part_list
                    WHERE pl_user_id = :userid and pl_active = 1");
                $stmt->bindparam(":userid", $userid);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row['pl_id'];
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListReturnActive
        
        
        /**
        * Returns a count of the user list or a count of the skus in requested list
        *
        * @param integer $userID the users ID
        * @param string $type can be 'list' or 'sku' to either get a count of the list or skus
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return list count or sku count
        */
        public function getMyListCount($userID, $type)
        {    
            if($type == 'list')
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT count(*) as List_Count FROM user_part_list
                        WHERE pl_user_id = :userID");
                    $stmt->bindparam(":userID", $userID);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $list_count = $row['List_Count'];
                    return number_format($list_count);
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            } elseif($type == 'skus')
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT count(*) as List_Count FROM user_part_list_skus
                        LEFT JOIN user_part_list on pl_id = pls_list_id
                        WHERE pl_user_id = :userID");
                    $stmt->bindparam(":userID", $userID);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $list_count = $row['List_Count'];
                    return number_format($list_count);
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
            
        } // end myListReturnActive
        
        /**
        * Adds a sku to the users active list
        *
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function myListAddSku($sku)
        {
            $listid = $this->getMyActiveListID();
            try
            {
                $stmt = $this->conn->prepare("INSERT INTO user_part_list_skus (pls_list_id, pls_list_sku) VALUES(:pl_list_id, :pl_list_sku )");
                $stmt->bindparam(":pl_list_id", $listid);
                $stmt->bindparam(":pl_list_sku", $sku);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListAddSku
        
        /**
        * Checks if the sku is already in the users active list
        *
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function myListSkuCheck($sku)
        {
            $listid = $this->getMyActiveListID();
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user_part_list_skus WHERE pls_list_sku = :pl_list_sku and pls_list_id = :pl_list_id");
                $stmt->bindparam(":pl_list_id", $listid);
                $stmt->bindparam(":pl_list_sku", $sku);
                $stmt->execute();
                $rowcount = $stmt->rowCount();
                if($rowcount < 1)
                {
                    return false;
                } else 
                {
                    return true;
                }
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListSkuCheck
        
        /**
        * Removes a sku from a users list
        *
        * @param string $sku is the part number
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function myListRemSku($sku, $list)
        {
            // get the active list 
            $listid = $this->getMyActiveListID();
            try
            {
                $stmt = $this->conn->prepare("DELETE from user_part_list_skus WHERE pls_list_sku = :pl_list_sku and pls_list_id = :pl_list_id");
                $stmt->bindparam(":pl_list_id", $listid);
                $stmt->bindparam(":pl_list_sku", $sku);
                $stmt->execute();
                return;
                
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListSkuCheck
        
        /**
        * Returns an array of all the users list
        *
        * @param string $listid is the list ID from the database
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return array $result
        */
        public function myListContent($listid)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user_part_list 
                    LEFT JOIN user_part_list_skus on pl_id = pls_list_id
                    LEFT JOIN sku on sku_id = pls_list_sku
                    WHERE pl_id = :pl_id
                    ORDER by pls_list_sku");
                $stmt->bindparam(":pl_id", $listid);
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
             
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListContent
        
        /**
        * Add record to the database of user and requested sku to be updated
        *
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function requestUpdate($sku)
        {
            $user = $_SESSION['user_id'];
            try
            {
                $stmt = $this->conn->prepare("INSERT INTO sku_update_request (update_sku, update_request_by) VALUES(:update_sku, :update_request_by )");
                $stmt->bindparam(":update_sku", $sku);
                $stmt->bindparam(":update_request_by", $user);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListAddSku
        
       /**
        * RChecks if the user has already requested a sku update
        *
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function requestUpdateCheck($sku)
        {
            $user = $_SESSION['user_id'];
            try
            {
                $stmt = $this->conn->prepare("SELECT * from sku_update_request
                    WHERE update_request_by = :userid and update_sku = :skuID");
                $stmt->bindparam(":userid", $user);
                $stmt->bindparam(":skuID", $sku);
                $stmt->execute();
                $rowcount = $stmt->rowCount();
                if($rowcount < 1)
                {
                    return false;
                } else 
                {
                    return true;
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end myListAddSku
        
        
        /**
        * Sets the user role to either USER or ADMIN
        *
        * @param integer $user is the user ID from the database
        * @param integer $role is 1 for USER or 2 for ADMIN 
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or pdo error
        */
        public function setUserRole($user, $role)
        {
            try
            {
                $stmt = $this->conn->prepare("UPDATE user SET user_role_id = :role_id 
                    WHERE user_id = :user_id");
                $stmt->bindparam(":user_id", $user);
                $stmt->bindparam(":role_id", $role);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end setUserRole
        
        /**
        * Returns the users current Role name
        *
        * @param integer $user is the users ID from the database
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return string $role = role name
        */
        public function getUserRole($user)
        {
            $userID = $_SESSION['user_id'];
            try
            {
                $stmt = $this->conn->prepare("SELECT * from user 
                    LEFT JOIN role on user_role_id = role_id
                    WHERE user_id = :user_id");
                $stmt->bindparam(":user_id", $user);
                $stmt->execute();
                $row = $stmt->fetch();
                $role = $row['role_name'];
                return $role;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end setUserRole
        
        
        
        /**
        * Destories session and sends user to the landing page
        *
        * @param string $sku is the part number
        * @param string $sku is the part number
        * 
        * @author Ken Stanley <ken@stanleysoft.org>
        * @return true or false
        */
        public function doLogout()
        {   
            session_destroy();
            header("Location: /");
            exit();
        }
        
        
    } // End Class
?>