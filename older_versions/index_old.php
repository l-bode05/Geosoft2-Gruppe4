<?php
	// Connect to db
	include("dbfunctions.php");


	/*insertcomment("content","titled","resources","http://url.dedf","id","name,uber,kunk","222","333");
	$result=getcomments("http://url.ded");
	echo $result[0]["title"];
	*/
        
        //$result=getcommentsfiltered("name", "con");
	//echo $result[0]["title"];
          $boolarray = Array(false => 'false', true => 'true');
          
         /*echo insertcomment("content","titled","resources","http://urdl.dedf","[anonym]dsad","name,uber,kunk","222","333");
         
                  echo $boolarray[IsLoggedIn()];*/
        echo UpdateAutoCompleteIndex("con");
        
   //     echo IsLoggedIn();
?>
