<?php 
/*	
    Get Datas for markers

*/

// Include library
include '../database/dbfunctions.php';


// Get Values
$geodataid=$GLOBALS["conn"]->real_escape_string($_POST["geodataid"]); // 0 = all, todo
$rating=$GLOBALS["conn"]->real_escape_string($_POST["rating"]);

echo $rating; 

RateGeodata($geodataid, $rating);

?>