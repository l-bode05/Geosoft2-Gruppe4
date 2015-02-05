<?php
// Include library
include '../parsing/parsing.php';

$url = $_POST["url"];
$url=str_replace("|","&",$url);

$format = $_POST["format"];

if($format=="kml" || $format =="gpx") {
	echo GetExternFile($url);
 
} else if($format=="query") {
	
	$bbox=GetBBoxfromURLQuery($url);
	$spli=explode(",",$bbox);
	
	echo BboxToGeoJSON($spli[0],$spli[1],$spli[2],$spli[3]);
} else if($format=="wms") {
	$bbox=BboxFromXml(GetExternFile(GetURLCapabilityUrl($url)), GetLayersfromURLQuery($url));
	
	if($bbox=="9999,9999,-9999,-9999") {
		echo -1;
	} else {			
		$spli=explode(",",$bbox);
		echo BboxToGeoJSON($spli[0],$spli[1],$spli[2],$spli[3]);
	}
}

?>


