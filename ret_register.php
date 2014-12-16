<?php 
/*	Register a new user


*/
// Include library
include 'dbfunctions.php';


// Get Values
$name=$_POST["reg_name"];
$mail=$_POST["reg_mail"];
$password=$_POST["reg_pw"];

// Register
$ret=RegisterUser($name, $mail, $password);
//returned < 0 if errors, else success and loggedin too
if($ret<0) {
	echo $ret;
} else {
	echo "1";
}
?>