<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Include library
include 'dbfunctions.php';

// Get Values
$keyword=$GLOBALS["conn"]->real_escape_string($_POST["keyword"]);
$tag=$GLOBALS["conn"]->real_escape_string($_POST["tag"]);
$date_start=$GLOBALS["conn"]->real_escape_string($_POST["date_start"]);
$date_end=$GLOBALS["conn"]->real_escape_string($_POST["date_end"]);

$rate_min=$GLOBALS["conn"]->real_escape_string($_POST["rate_min"]);
$rate_max=$GLOBALS["conn"]->real_escape_string($_POST["rate_max"]);

$bbox=$GLOBALS["conn"]->real_escape_string($_POST["bbox"]);

$f_Longitude=$GLOBALS["conn"]->real_escape_string($_POST["f_Longitude"]);
$f_Latitude=$GLOBALS["conn"]->real_escape_string($_POST["f_Latitude"]);
$distance=$GLOBALS["conn"]->real_escape_string($_POST["distance"]);


$res=GetCommentsFiltered($tag, $keyword, $rate_min, $rate_max, $date_start, $date_end, $bbox,
                                                                        $f_Longitude, $f_Latitude, $distance);



// Generate html txt 
for($i=0; $i < count($res); $i++) {
    $html=$html."<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
                              ")' > Goto </a> <br>";
    $html=$html. "<a href='#' onclick='ShowComment(".$res[$i]["id"].")' > Show Thread </a> <br>";
    $html=$html.GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
    $html=$html.$res[$i]["timecreated"]."<br>";
    $html=$html.$res[$i]["title"]."<br>"; 
    $html=$html.$res[$i]["content"]."<br>";
    $html=$html."<a href='/final/index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
    $html=$html.TagsIdsToName($res[$i]["tags_ids"])."<br>";
    $html=$html."<br>";
   
    
}
echo $html."°";
    
// Generate marker datas
$arr = array();
for($i=0; $i < count($res); $i++) {
    $answers_num=count(GetCommentThread($res[$i]["id"]));
    $obj = array('positionx'=>$res[$i]["positionx"],
        'positiony'=>$res[$i]["positiony"] ,
        'content'=>$res[$i]["title"]."<br>".$res[$i]["content"]."<br>Comments:". $answers_num , 
        'id'=>$res[$i]["id"]);
   array_push($arr,$obj);
        
} 
echo json_encode($arr);
   
   
?> 