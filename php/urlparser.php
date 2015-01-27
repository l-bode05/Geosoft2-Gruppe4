
/*
*Pharse url from given geodata url
*
*
*/
<?php 
// Include library
include 'dbfunctions.php';



$url=parse_url($geodata_id);



if ((strpos($url['query'],"service=WFS")!==false) or (strpos($url['query'],"service=wfs")!==false)) {
   echo "WFS";
   echo $url['query'];

   }
else
if ((strpos($url['query'],"service=WMS")!==false) or (strpos($url['query'],"service=wms")!==false) or (strpos($url['query'],"wms")!==false) or (strpos($url['query'],"WMS")!==false) or (strpos($url, "WMSServer")!==false)) {
   echo "WMS";
   echo $url['query'];
   $wmslayer=$url;
   }
else
if ((strpos($url['query'],"service=WMTS")!==false) or (strpos($url['query'],"service=wmts")!==false)) {
   echo "WMTS";
   echo $url['query'];
   }
else
if ((strpos($url,"KML")!==false) or (strpos($url,"kml")!==false)) {
   echo "KML";
   $kmllayer=$url
   }

else
if ((strpos($url,"GML")!==false) or (strpos($url,"gml")!==false)) {
   echo "GML";
   }


   ?>