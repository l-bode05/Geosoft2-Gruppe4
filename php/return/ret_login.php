<?php 
/*	Login a user


*/
// Include library
include '../database/dbfunctions.php';


// Get Values
$name=$GLOBALS["conn"]->real_escape_string($_POST["log_nickname"]);
$password=$GLOBALS["conn"]->real_escape_string($_POST["log_pass"]);



// Register
$ret=Login($name, $password);

//returned < 0 if errors, else success and loggedin too
if($ret==true) {
	echo "1";
} else {
	echo "0";
}
?>