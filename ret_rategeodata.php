<?php 
/*	
    Get Datas for markers

*/

// Include library
include 'dbfunctions.php';


// Get Values
$geodataid=$GLOBALS["conn"]->real_escape_string($_POST["geodataid"]); // 0 = all, todo
$rating=$GLOBALS["conn"]->real_escape_string($_POST["rating"]);

RateGeodata($geodataid, $rating);

?>