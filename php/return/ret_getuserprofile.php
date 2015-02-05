<?php 
/*	Register a new user


*/
// Include library
include '../database/dbfunctions.php';

// Get Values
$id=$GLOBALS["conn"]->real_escape_string($_POST["id"]);

//Get whole thread
$regdate = GetUserRegistrationDate($id);
$res = GetCommentsByUser($id);

// Gen html txt
$html="<b>User: ".  GetUsername($id)."</b>";
$html=$html. "<br><br>Registration date: ".GetUserRegistrationDate($id);
$html=$html. "<br>Geodatas: ".count($res);


if(count($res)>0) {
    $html=$html. "<br><br><p>Last geodatas:".count($res);

    // Generate html txt 
    for($i=0; $i < count($res); $i++) {
        $html=$html.'<br><b>Show Position: </b>'."<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
                                  ")' > Goto </a> <br>";
        $html=$html. "<a href='#' onclick='ShowComment(".$res[$i]["id"].")' > Show Thread </a> <br>";
        $html=$html.'<b>User: </b>'.GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
        $html=$html.'<b>Date: </b>'.$res[$i]["timecreated"]."<br>";
        $html=$html.'<b>Title: </b>'.$res[$i]["title"]."<br>"; 
        $html=$html.'<b>Comment: </b>'.$res[$i]["content"]."<br>";
        $html=$html.'<b>Permalink: </b>'."<a href='index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
        $html=$html.'<b>Tags: </b>'.TagsIdsToName($res[$i]["tags_ids"])."<br>";
        $html=$html."<br>";
    }
}

echo $html;


?>