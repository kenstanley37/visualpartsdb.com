<?php
     /**
     * VALIDATE handles all types of validation methods
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    class VALIDATE {
        
        /**
        * Removes slashes, html, and tags from a string
        *
        * @param type $string STRING the requested string to clean up
        * @return $string returns the clean string back
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        function sanitizeString($string)
        {
            $string = stripslashes($string);
            $string = htmlentities($string);
            $string = strip_tags($string);
            return $string;
        }
        
        /**
        * Checks if email is valid and returns header get error if not
        *
        * @param type $email STRING the submitted email address
        * @author Ken Stanley <ken@stanleysoft.org>
        */
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