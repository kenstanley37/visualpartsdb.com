<?php
    class VALIDATE {
        
        // *************************************************************
        // Usage: sanitizeString($string)
        // removes and slashes, tags, and html from string and returns
        // a clean string
        // *************************************************************
        function sanitizeString($string)
        {
            $string = stripslashes($string);
            $string = htmlentities($string);
            $string = strip_tags($string);
            return $string;
        }
        
        // *************************************************************
        // Usage: validEmail($email)
        // checks if email and domain is valid return 0 if invalid
        // *************************************************************
        
        function validEmail($email){
            $_SESSION['emailcheck'] = $email;
            if(empty($email)){
                header('location: /login.php?error=noemail');
            } else {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    header('location: /login.php?error=invalidemail');
                }
            }
            
        } // end validEmail
    }

?>