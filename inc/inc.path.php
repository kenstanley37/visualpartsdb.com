<?php
/**
* Sets the path
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/

/** defines */
define('ROOT_DIR', dirname(__FILE__));

//remove the \inc from the ROOT_DIR
$path = str_replace('inc', DIRECTORY_SEPARATOR, ROOT_DIR);
?>