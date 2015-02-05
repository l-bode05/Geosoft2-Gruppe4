<?php 
/*	
    Insert a new comment with/without geodata
    

*/

    // Include library
    include '../database/dbfunctions.php';

    // Get Values
    $answer_to=$GLOBALS["conn"]->real_escape_string($_POST["answer_to"]);
    $content=$GLOBALS["conn"]->real_escape_string($_POST["ins_comment"]);
    $title=$GLOBALS["conn"]->real_escape_string($_POST["ins_title"]);
    $resources=$GLOBALS["conn"]->real_escape_string($_POST["ins_resources"]);
    $geodata_link=$GLOBALS["conn"]->real_escape_string($_POST["ins_url"]);
    $user_id=$GLOBALS["conn"]->real_escape_string($_POST["ins_username"]);
    if(IsLoggedIn()==false){ 
         $user_id.="anonym";
    } else {
        $user_id=GetMyUserid();
    }
    $taglist=$GLOBALS["conn"]->real_escape_string($_POST["ins_tags"]);
    $pointx=$GLOBALS["conn"]->real_escape_string($_POST["Latitude"]);
    $pointy=$GLOBALS["conn"]->real_escape_string($_POST["Longitude"]);
    $datestamp=$GLOBALS["conn"]->real_escape_string($_POST["ins_date"]);    
    $spatialexp=$GLOBALS["conn"]->real_escape_string($_POST["ins_spatialexp"]);

            
    if($answer_to=="") { // Add GeoData
        echo InsertCommentGeodata($answer_to, $content, $title, $resources,
                $geodata_link, $user_id, $taglist,$pointx, $pointy, $datestamp,null, $spatialexp);
    } else { // Add answer/comment without geodata urls
        echo InsertComment($answer_to, $content, $title, $resources, $geodata_link, $user_id, $taglist,$pointx, $pointy);
    }
   
?>