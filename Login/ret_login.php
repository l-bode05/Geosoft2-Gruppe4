<?php 
/*	Login a user


*/
// Include library
include 'dbfunctions.php';


// Get Values
$name=$_POST["log_nickname"];
$password=$_POST["log_pass"];

// Register
$ret=Login($name, $password);

//returned < 0 if errors, else success and loggedin too
if($ret==true) {
	echo "1";
} else {
	echo "0";
}
?>