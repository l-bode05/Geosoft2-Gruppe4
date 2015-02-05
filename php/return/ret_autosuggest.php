<?php
/*
    Autocomplete for keyword search as filter option
  
 */

// Include library
include '../database/dbfunctions.php';

    // Connection to database
    $query=strtolower($_GET["query"]);
    // Requested elements of the database
    $sql = '
    SELECT content, title
    FROM comments'
    ;
       
    $result = GetSelectResults($sql);
    
    // Elements of database get indicated and are merged to string in a specific style
    while ($row = $result->fetch_assoc()){
	$items = $items. " ". $row['content'] ." ". $row['title']. " ";
    }

    // The merged elements ($Items) get splitted up and every letter gets small
    $items=strtolower($items);
    $fullstr= explode(" ",$items);
	 
    // $e_arr is the array, which incorporates all elements out of the $fullstr array without twice items
    $maxi = count($fullstr);
    $e_arr=array();
       
    for ($i=0; $i < $maxi; $i++ )
    {
        $curstr=$fullstr[$i];

        if($e_arr == null || in_array($curstr, $e_arr, TRUE) == false && $curstr != $query) {
                // $e_arr gets expanded by $curstr
                array_push($e_arr, $curstr);			
                if (strpos($curstr, $query)===0){
                    // All non-twice elements are echod and if you click on a suggestion it is set into the input field
                        echo "<li> <a href='#' onfocus='Complete(\"$curstr\")'> ".$curstr."</a> </li>";
                }
        }
    }

        
?>

<html>
	<script>
		function Complete(query) {
			
			document.getElementById("keyword").value=query;
                        document.getElementById("autosuggest-container").innerHTML="";
		}
	</script>
        
        <style  type="text/css">
                body, html {
                font: 1em "Tahoma", sans-serif;

                }

                #autosuggest-container {
                    display:block;
                    padding-left: 0;
                    list-style:none;
                    border:1px solid #CCC;
                    border-bottom:none;
                    width: 250px;
                    cursor: default;
                    position: absolute;
                    background: #FFF;
                }

                #autosuggest-container li {
                    width: 248px;
                    border: 1px solid #bbb;
                    border-top: 0;
                    padding: 10px 10px;
                    list-style: none;
                    background: #FFF;
                }

                #autosuggest-container li:hover {background:#F1F1F1;
                }



        </style>
</html>

