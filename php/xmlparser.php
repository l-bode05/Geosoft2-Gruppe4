
/*
*Pharse url from given geodata url
*
*
*/
<?php 

// Include library
include 'dbfunctions.php';
 

 $url=$geo_data;
  if ($url=="") echo("no url");

	// fix missing "?" and add params to be sure they are there
	if (strpos($url,'?')==false){ $url.="?";
	$url.="&SERVICE=WMS&REQUEST=GetCapabilities";
	$xml = file_get_contents($url);
	}
	else
	$xml = file_get_contents($url);

	echo($xml);


?>