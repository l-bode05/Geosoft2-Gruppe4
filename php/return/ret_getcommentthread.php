<?php 
/*	Register a new user


*/
// Include library
include '../database/dbfunctions.php';

// Get Values
$id=$GLOBALS["conn"]->real_escape_string($_POST["id"]);

 
//Get whole thread
$res=GetCommentThread($id);

// Gen html txt
$html='<script src="js/rateit/jquery.rateit.min.js" type="text/javascript" charset="utf-8"></script>
                  <link href="js/rateit/rateit.css" rel="stylesheet" type="text/css">';

// Show all comments
                  $html=$html.'<fieldset> <legend><b>Comment: </b></legend>';
for($i=0; $i < count($res); $i++) {
    if($i==0) {
      $html=$html.'<b>Show Position: </b>'."<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
                              ")' > Goto </a> <br>";
       $html=$html.'<b>Url: </b>'.GeoIdToLink($res[$i]["geodata_id"]) ."<br>";
    }
    
    if($_SESSION["userid"]==$res[$i]["user_id"]) {
        $html=$html. "<a href='#' onclick='ShowEditForm(".$res[$i]["id"].")'>edit</a><br>";
    }
    $html=$html.'<b>User: </b>'. GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
    $html=$html.'<b>Date: </b>'.$res[$i]["timecreated"]."<br>";
    $html=$html.'<b>Title: </b>'.$res[$i]["title"]."<br>";
    $html=$html.'<b>Comment: </b>'.$res[$i]["content"]."<br>";
    $html=$html.'<b>Resources: </b>'.$res[$i]["resources"]."<br>";
    $html=$html.'<b>Permalink: </b>'."<a href='index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
    $html=$html.'<b>Tags: </b>'.TagsIdsToName($res[$i]["tags_ids"])."<br>";

 
    // Comment with geodata
    if($i==0) {
        // Show Average rating
        $avrating=GetAverageRating($res[$i]["geodata_id"]);
        
        if($avrating!="0") {
           $html=$html.'	
                   Ø Rating: 
                   <input type="range" min="0" max="5" value="'.$avrating.'" step="0.1" id="ratinglist">
                   <div class="rateit" data-rateit-backingfld="#ratinglist" id="rateit-range" data-rateit-readonly="true"></div>
                   <br>
                   <br>';
        }
           
        // Ratebar
        if(CanRateGeodata($res[$i]["id"])==true) {
             $html=$html.'
                  <br><br><br>Your rating:
                  <input type="hidden" min="0" max="5" value="'.$_COOKIE[$res[$i]["id"]] .'" step="0.5" id="ratinglist">
                  <div class="rateit" data-rateit-backingfld="#ratinglist" id="rateit-range"></div>
                 
                  <div id="ratingdiv"><br>
                         <input type="submit" value="Submit Rating" onclick="RateGeodata('. $res[$i]["geodata_id"].');" class="button round" />
                 </div>
             ';
        }
    }
   
    $html=$html."<br>";
}


// Insert Answer form and input id
$html=$html."<br><br>";
$html=$html.requireToVar("../form/form_answer.php");
$html= str_replace("anid", $id, $html);

echo $html."°";
 $html=$html.'</fieldset>';
// Generate marker datas
$arr = array();
for($i=0; $i < count($res); $i++) {
    $answers_num=count(GetCommentThread($res[$i]["id"]));
    $obj = array('positionx'=>$res[$i]["positionx"],
        'positiony'=>$res[$i]["positiony"] ,
        'content'=>$res[$i]["title"]."<br>".$res[$i]["content"]."<br>Comments:". $answers_num , 
        'id'=>$res[$i]["id"]
        );
   array_push($arr,$obj);
        
} 
echo json_encode($arr)."°";
echo GetSelectField("SELECT * FROM geodata WHERE id=".$res[0]["geodata_id"]."", "spatialexp") ;




?>