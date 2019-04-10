<?php
     /**
     * VALIDATE handles all types of validation methods
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */

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
        * @param string $str the requested string to clean up
        * @return $str returns the clean string back
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        function sanitizeString($str)
        {
            $str = stripslashes($str);
            $str = htmlentities($str);
            $str = strip_tags($str);
            return $str;
        }
        
        /**
        * Checks if email is valid and returns header get error if not
        *
        * @param string $emailAddress the submitted email address
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        function validEmail($emailAddress){
            $_SESSION['emailcheck'] = $emailAddress;
            if(empty($emailAddress)){
                header('location: /login.php?error=noemail');
            } else {
                if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
                    header('location: /login.php?error=invalidemail');
                }
            }
            
        } // end validEmail
    }

?>