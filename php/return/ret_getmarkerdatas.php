<?php 
/*	
    Get Datas for markers

*/

// Include library
include '../database/dbfunctions.php';


// Get Values
$cat=$GLOBALS["conn"]->real_escape_string($_POST["cat"]); // 0 = all, todo
$filtertime=$GLOBALS["conn"]->real_escape_string($_POST["filtertime"]);
$filterbb=$GLOBALS["conn"]->real_escape_string($_POST["filterbb"]);
$filterkeyword=$GLOBALS["conn"]->real_escape_string($_POST["filterkeyword"]);


$res=GetAllFirstComments();


$arr= array();

for($i=0; $i < count($res); $i++) {
    $answers_num=count(GetCommentThread($res[$i]["id"]));
    $obj = array('positionx'=>$res[$i]["positionx"],
        'positiony'=>$res[$i]["positiony"] ,
        'content'=>$res[$i]["title"]."<br>".$res[$i]["content"]."<br>Comments:". $answers_num , 
        'id'=>$res[$i]["id"],
        'geo_data'=>GeoIdToLink($res[$i]["geodata_id"]));
   array_push($arr,$obj);
        
} 

echo json_encode($arr);

 
?>