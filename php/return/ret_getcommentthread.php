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
for($i=0; $i < count($res); $i++) {
    if($i==0) {
      $html=$html."<a href='#' onclick='ShowPosition(".$res[$i]["positionx"].",".$res[$i]["positiony"].
                              ")' > Goto </a> <br>";
       $html=$html. GeoIdToLink($res[$i]["geodata_id"]) ."<br>";
    }
    
    if($_SESSION["userid"]==$res[$i]["user_id"]) {
        $html=$html. "<a href='#' onclick='ShowEditForm(".$res[$i]["id"].")'>edit</a><br>";
    }
    $html=$html. GetUsernameFormatted($res[$i]["user_id"]) ."<br>";
    $html=$html.$res[$i]["timecreated"]."<br>";
    $html=$html.$res[$i]["title"]."<br>";
    $html=$html.$res[$i]["content"]."<br>";
    $html=$html.$res[$i]["resources"]."<br>";
    $html=$html."<a href='/final/index.php?comment=".$res[$i]["permalink"]."'> Permalink</a>". "<br>";
    $html=$html.TagsIdsToName($res[$i]["tags_ids"])."<br>";
    
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
                  <br><br><br>
                  <input type="hidden" min="0" max="5" value="'.$_COOKIE[$res[$i]["id"]] .'" step="0.5" id="ratinglist">
                  <div class="rateit" data-rateit-backingfld="#ratinglist" id="rateit-range"></div>
                  Submit Rating:
                  <div id="ratingdiv">
                         <input type="submit" value="Submit" onclick="RateGeodata('.$res[$i]["id"].');" class="button round" />
                 </div>
             ';
        }
    }
    $html=$html."<br><br><br>";
}


// Insert Answer form and input id
$html=$html."<br><br><br>";
$html=$html.requireToVar("../form/form_answer.php");
$html= str_replace("anid", $id, $html);

echo $html."°";

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