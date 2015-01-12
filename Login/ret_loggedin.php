<?php 
/*	Check if loggedin


*/
// Include library
include 'dbfunctions.php';


// Get Values
if(IsLoggedIn()==true)
{ 
	return 1; 
} else {
	return 0;
}
?>