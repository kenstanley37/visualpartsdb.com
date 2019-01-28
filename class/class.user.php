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

        // *************************************************************
        // Usage: isLogin()
        // Logged In: If user is logged in show name with link to user profile
        // Not Logged In: show link to login screen
        // *************************************************************
        
        function isLogin()
        {
            if(isset($_SESSION["fname"]))
            {
                $fname = $_SESSION['fname'];
                $userID = $_SESSION['user_id'];
                $loginCheck = '<a href="/profile.php?userid='.$userID.'">'.$fname.'</a>
                               <a href="/logout.php">Logout</a>';
            } else
            {
                $loginCheck = '<a href="/login.php">Login</a>';
            }
            
            echo $loginCheck;
        } // end isLogin
        
        // *************************************************************
        // Usage: login(test@test.com, password)
        // Verify that user is in the database and sets session for:
        // 1: User ID
        // 2: User Role (Member, Admin)
        // 3: User First Name
        // 4: User Last Name
        // *************************************************************
        
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
                        header('Location: /');
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
        
        // *************************************************************
        // Usage: accessCheck();
        // returns the role of the current user to secure access 
        // to different sections of the website.
        // *************************************************************
        
        public function accessCheck()
        {
            if(isset($_SESSION['user_id']))
            {
                $uid = $_SESSION['user_id'];
                try
                {
                    $stmt = $this->conn->prepare("SELECT * FROM user 
                    left join role on user_role_id = role_id
                    WHERE user_id=:uid");
                    $stmt->execute(array(':uid'=>$uid));
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
        
        // *************************************************************
        // Usage: addUserVerify($fname, $lname, $email);
        // Send new user verification email
        // *************************************************************
        
            public function addUserVerify($fname, $lname, $email, $memFName, $memLName, $memuserID)
            {
                $fname = strtolower($fname);
                $fname = ucfirst($fname);
                
                $lname = strtolower($lname);
                $lname = ucfirst($lname);
                
                $email = strtolower($email);

                //Load Composer's autoloader
                require '../vendor/autoload.php';
                // for user registeration
                $code = substr(md5(mt_rand()),0,15);
                
                $mail = new PHPMailer(true);                              // Passing `true` enables xceptions
                
                try {
                    $stmt = $this->conn->prepare("INSERT INTO verify (verify_fname, verify_lname, verify_email, verify_code, verify_added_by) VALUES(:fname, :lname, :email, :code, :memuserID)");

                    $stmt->bindparam(":fname", $fname);
                    $stmt->bindparam(":lname", $lname);
                    $stmt->bindparam(":email", $email);
                    $stmt->bindparam(":code", $code);
                    $stmt->bindparam(":memuserID", $memuserID);
                    $stmt->execute();	
                    $db_id = $this->conn->lastInsertId();
                    
                    
                    //Server settings
                    $mail->SMTPDebug = 2;                           // Enable verbose debug output
                    $mail->isSMTP();                                // Set mailer to use SMTP
                    $mail->Host = 'visualpartsdb.com';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                         // Enable SMTP authentication
                    $mail->Username = 'info@visualpartsdb.com';     // SMTP username
                    $mail->Password = '#r.MTs%{@OEy';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                              // TCP port to connect to

                    //Recipients
                    $mail->setFrom('info@visualpartsdb.com', 'Visual Parts Database');
                    $mail->addAddress($email, $fname.' '.$lname);     // Add a recipient
                    //$mail->addAddress($email);               // Name is optional
                    $mail->addReplyTo('info@visualpartsdb.com', 'NoReply');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Welcome to Visual Parts Database';
                    $mail->Body    = 'Hello '.$fname.', <br><br> You have been invited by <b>'.$memFName.' '. $memLName.'</b> to be a user of Visual Parts Database. <br><br>Your Activation Code is: <b>'.$code.'</b><br><br> Please click on this link https://visualpartsdb.com/user/register.php?id='.$db_id.'&code='.$code.' to activate your account.';
                    $mail->AltBody = 'Your Activation Code is: '.$code.' Please click on this link https://visualpartsdb.com/user/register.php?id='.$db_id.'&code='.$code.' to activate your account.';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
            }
        
            // *************************************************************
            // Usage: checkVerify($id, $code);
            // Used for email verification. Adds record into user table
            // *************************************************************
            public function checkVerify($id, $code)
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT * from verify where verify_id=:id and verify_code=:code");							  
                    $stmt->bindparam(":id", $id);
                    $stmt->bindparam(":code", $code);
                    $stmt->execute();	
                    if($stmt->rowCount() == 1){
                        $row = $stmt->fetch();
                        $userFName = $row['verify_fname'];
                        $userLName = $row['verify_lname'];
                        $userEmail = $row['verify_email'];
                        $userActive = 1;
                        $userPassword = 'temp1';
                        $userRole = 1;
                        try
                        {
                            $adduser = $this->conn->prepare("INSERT INTO user (user_fName, user_lName, user_email, user_active, user_password, user_role_id) VALUES(:fname, :lname, :email, :active, :password, :role)");
                            $adduser->bindparam(":fname", $userFName);
                            $adduser->bindparam(":lname", $userLName);
                            $adduser->bindparam(":email", $userEmail);
                            $adduser->bindparam(":active", $userActive);
                            $adduser->bindparam(":password", $userPassword);
                            $adduser->bindparam(":role", $userRole);
                            $adduser->execute();
                            $db_id = $this->conn->lastInsertId();
                            $this->setSession($db_id, $userFName, $userLName);
                        } catch(PDOException $e)
                        {
                            return false;
                        }
                      return true;
                    } else {
                        echo 'invalid code';
                      return false;
                    }
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
            }
        
        
        // *************************************************************
        // Usage: updatePassword($userid, $password);
        // Updates the password for the user
        // *************************************************************
        public function updatePassword($userid, $password){
            $userid = $_SESSION['user_id'];
            $password = password_hash($password, PASSWORD_DEFAULT);
            try 
            {
                $stmt = $this->conn->prepare("UPDATE user SET user_password=:password where user_id=:userid ");
                $stmt->bindparam(":userid", $userid);
                $stmt->bindparam(":password", $password);
                $stmt->execute();
                return true;
            } catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        
        // *************************************************************
        // Usage: setSession($id, $fname, $lname);
        // Sets session after registeration and/or user login
        // *************************************************************
        
        public function setSession($id, $fname, $lname)
        {
            $_SESSION['user_id'] = $id;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            return true;
        }
        
        // *************************************************************
        // Usage: dropdownUser();
        // returns a list of all users in a select  
        // *************************************************************
        
        public function dropDownUser($userID)
        {
            //$userID = 1;
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM user");
                $stmt->execute();
                // if email if found check password
                ?> <option value=""></option><?php
                while($row = $stmt->fetch())
                {
                    ?>
                    <option value="<?php echo $row['user_id']; ?>"
                            <?php 
                                if($row['user_id'] == $userID ){ echo 'selected';}
                            ?>
                            ><?php echo $row['user_fName']; ?> <?php echo $row['user_lName']; ?></option>
                    <?php
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end accessCheck
        
        
        // *************************************************************
        // Usage: registerRequest($fname, $lname, $email, $phone, $company, $message);
        // Writes a record to the data base for an ADMIN to approve or deny
        // *************************************************************
        
        public function registerRequest($fname, $lname, $email, $phone, $company, $message)
        {
            if(isset($_SESSION['user_id']))
            {
                $uid = $_SESSION['user_id'];
                return 'alreadyregistered';
            } else {
                try
                {
                    // Lets check if the user is already registered
                    $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_email=:user_email");
                    $stmt->bindparam(":user_email", $email);
                    $stmt->execute();
                    if($stmt->rowCount() == 1)
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
            }
        } // end accessCheck
        
        
        // *************************************************************
        // Usage: doLogout()
        // unsets and destory sessions. Sends user back to root url
        // *************************************************************
        
        public function doLogout()
        {   
            session_destroy();
            header("Location: /");
            exit();
        }
        
        
    } // End Class
?>