<?php

/* 
* BboxToGeoJSON
*
* Converts a bounding box (four points) to geoJSON polygon
*
* $px1..$py2 bound points
*/
function BboxToGeoJSON($px1, $py1, $px2, $py2) {
	// Check bounding points
	if($px1>$px2) {
		$tmp=$px1;
		$px1=$px2;
		$px2=$tmp;
	}
	
	if($py1>$py2) {
		$tmp=$py1;
		$py1=$py2;
		$py2=$tmp;
	}	
		
	// Basic 4 dots polygon
	$struct='
	{
	  "type": "FeatureCollection",
	  "features": [
		{
		  "type": "Feature",
		  "properties": {},
		  "geometry": {
			"type": "Polygon",
			"coordinates": [
			  [
				[
				  '.$py1.',
				  '.$px1.'
				],
				[
				  '.$py1.',
				  '.$px2.'
				],
				[
				  '.$py2.',
				  '.$px2.'
				],
				[
				  '.$py2.',
				  '.$px1.'
				],
				[
				  '.$py1.',
				  '.$px1.'
				]
			  ]
			]
		  }
		}
	  ]
	}';

	return $struct;
}

/* 
* GetBBoxfromJSON
*
* Gets a bounding box from GeoJSON.
*
* $px1..$py2 bound points
*/
function GetBBoxfromJSON($content) {
	$minx=9999;
	$miny=9999;
	$maxx=-9999;
	$maxy=-9999;
	
	$json = json_decode($content, true);
	// Iterate each feature
	for($d=0; $d<count($json['features']); $d++) {
		$curarr=$json['features'][$d]['geometry']['coordinates'][0];
		for($i=0; $i<count($curarr); $i++) {
			// new extrem values
			if($minx > $curarr[$i][0]) $minx=$curarr[$i][0];
			if($miny > $curarr[$i][1]) $miny=$curarr[$i][1];
			
			if($maxx < $curarr[$i][0]) $maxx=$curarr[$i][0];
			if($maxy < $curarr[$i][1]) $maxy=$curarr[$i][1];
		}
	}
	return $minx.",".$miny.",".$maxx.",".$maxy;
}



/* 
* GetBBoxfromURLQuery
*
* Gets a bounding box from a url query (&bbox=..)
*
* @param $url string Url containing key/value
* @return string searched value
*/
function GetBBoxfromURLQuery($url) {
	
	return GetValuefromURLQuery($url,"bbox");
}

/* 
* GetLayersfromURLQuery
*
* Gets a list of requestes layers from a url
*
* @param $url string Url containing key/value
* @return string layer list(layer1,layer2,..)
*/
function GetLayersfromURLQuery($url) {
	
	return GetValuefromURLQuery($url,"layers");
}

/* 
* GetValuefromURLQuery
*
* Gets a value to given key from a url
*
* @param $url string Url containing key/value
* @param $keyname string key to search for
* @return string searched value
*/
function GetValuefromURLQuery($url, $keyname) {
	$url=str_replace("?","&",$url);
	
	$spli=explode("&",$url);
	for($i=0; $i<count($spli); $i++) {
		if(strpos(strtolower ($spli[$i]),strtolower($keyname."="))>-1 ) {
			return str_ireplace($keyname."=","",$spli[$i]);
		}
	}
	return "";
}

/* 
* GetURLCapabilityUrl
*
* Convert a url to ?request=GetCapabilities format
*
* @param $url string url 
* @return string converted url
*/
function GetURLCapabilityUrl($url) {
	$spli=explode("&",$url);
	return $spli[0]."?service=WMS&request=GetCapabilities";
}
/*
echo GetBBoxfromURLQuery("sadsadas.com?&bbox=-145.15104058007,21.731919794922,-57.154894212888,58.961058642578&&width=780");
echo "\n";
echo GetBBoxfromJSON(BboxToGeoJSON(5,4,3,2));
*/


/* 
* BboxFromXml
*
* Reads a GetCapability xml file and iterates through each tag to get bounding box
* http://www.wms.nrw.de/geobasis/wms_nw_dtk25
* @param: $xml string file
* @param $layers string filter for layers, only in layer tags with these names will be crawled
*/
$curminx=9999;
$curminy=9999;
$curmaxx=-9999;
$curmaxy=-9999;
function BboxFromXml($xml, $layers) {
	$xml=strtolower($xml);
	$layers=strtolower($layers);
	
	
	// Init
	$doc = new DOMDocument();
	$doc->loadXML( $xml );
	$xpath = new DOMXpath( $doc );
	$nodes = $xpath->query( '//*' );
	$nodeNames = array();
	
	// Reset globals
	$GLOBALS["curminx"]=9999;
	$GLOBALS["curminy"]=9999;
	$GLOBALS["curmaxx"]=-9999;
	$GLOBALS["curmaxy"]=-9999;
 
	// Start searching
	GetBboxFromDom($nodes, $layers, false);
	// nothing found with layers, so try again without to get general bbox
	if($GLOBALS["curmaxy"]==-9999) GetBboxFromDom($nodes, "", true);
	
	return $GLOBALS["curminy"].",".$GLOBALS["curminx"].",".$GLOBALS["curmaxy"].",".$GLOBALS["curmaxx"];
}

/* 
* GetBboxFromDom
*
* Recursive function to get bbox datas from a dom node. Written to Globals 'curmin'
*
* @param $gn dom node containg the bbox
* @param $layers string filter for layers, only in layer tags with these names will be crawled
* @param $withinlayer bool within a layer tag
*/
function GetBboxFromDom($gn, $layers, $withinlayer) {
	$validlayer=false;	
	
	// if layers empty, always valid
	if($layers=="") {$withinlayer=true; $validlayer=true;}

	
	foreach($gn as $node )
	{
		if($withinlayer) {
			// Compare layer names
			if($node->nodeName=="name") {
				$spli=explode(",",$layers);
				$validlayer=false;
				for($i=0; $i<count($spli); $i++) {
					if($spli[$i]==$node->nodeValue) {$validlayer=true; }
				}
			}
			if($layers=="") {$validlayer=true;}
			// Check for bbox tags
			if($validlayer && ($node->nodeName=="latlonboundingbox" || $node->nodeName=="boundingbox")){
				// Validate crs format
				if(($node->getAttribute("crs")=="" && $node->getAttribute("srs")=="") || $node->getAttribute("crs")=="crs:84" || $node->getAttribute("srs")=="crs:84") {
					// Get Values
					if($node->getAttribute("minx")!="") {
						$minx=$node->getAttribute("minx");
						$miny=$node->getAttribute("miny");
						$maxx=$node->getAttribute("maxx");
						$maxy=$node->getAttribute("maxy");
					}
					
					// Set them
					if($minx<$GLOBALS["curminx"]) $GLOBALS["curminx"]=$minx;
					if($miny<$GLOBALS["curminy"]) $GLOBALS["curminy"]=$miny;
					if($maxx>$GLOBALS["curmaxx"]) $GLOBALS["curmaxx"]=$maxx;
					if($maxy>$GLOBALS["curmaxy"]) $GLOBALS["curmaxy"]=$maxy;
					
					
				}
			}
		}
		
		// Check if in layers
		if($node->nodeName=="layer") { $withinlayer=true;}
		if($node->childNodes-> length>0) GetBboxFromDom($node->childNodes, $layers, $withinlayer);
		
	}
	
	if($gn->childNodes-> length>0) GetBboxFromDom($gn->childNodes, $layers, $withinlayer);
	
}

/* 
* GetExternFile
*
* Get a file on an other server
*
* @param $url string url 
* @return string content
*/
function GetExternFile($url) {
	$proxy = "wwwproxy.uni-muenster.de";
	$port = 80;
	$fp = fsockopen($proxy, $port);
	fputs($fp, "GET $url HTTP/1.0\r\nHost: $proxy\r\n\r\n");
	$content="";
	while(!feof($fp)){
	  $line = fgets($fp, 4000);
	  $content.=$line;
	}
	fclose($fp);
	$spli=explode("Proxy-Connection: close",$content);
	$content=$spli[1];
	return trim($content);
	
}

?>
