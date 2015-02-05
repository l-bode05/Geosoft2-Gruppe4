<?php
// Include main function
include "../../dbfunctions.php";

// Get Values
$searchkey = $GLOBALS["conn"]->real_escape_string($_GET["q"]);
$count = $GLOBALS["conn"]->real_escape_string($_GET["count"]);


// Build SQL Statement
$sql="SELECT * FROM comments WHERE answer_to='0' AND content "
        . "LIKE '%$searchkey%' ";
if($count!="") $sql.="LIMIT $count";

// Get Datas
$res = GetSelectAssocArray($sql);

// Generate assoc Array 
$arr = array();
for($i=0; $i< count($res) ; $i++) {
    // Build single array
    $smarr = array("id"=>"http://giv-geosoft2d.uni-muenster.de/index.php?comment=".$res[$i]["permalink"],
        "text"=>$res[$i]["content"], 
            "rating"=> GetAverageRating($res[$i]["geodata_id"]),
             "itemUnderReview" => GeoIdToLink($res[$i]["geodata_id"])
              );
    
    // Delete null ratings
    if(GetAverageRating($res[$i]["geodata_id"]) ==0)unset($smarr['rating']);
      
    array_push($arr,$smarr);
}

// Add Header
$harr = array("resource"=>curPageURL(),
            "comments"=>$arr);


// Encode and give out
echo json_encode($harr);



// Generate a nice pageurl
// @source: http://webcheatsheet.com/php/get_current_page_url.php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?> 