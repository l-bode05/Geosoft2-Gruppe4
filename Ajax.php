<?php

$db = mysql_connect("localhost","root","dark knight rises zakbum") or die("could not connect");

mysql_select_db("test") or die("couldn't connect to database");

$input = $_REQUEST['input'];

$input = mysql_real_escape_string(trim($input));

    $sql = "SELECT * FROM `comments` WHERE content LIKE '%".$input."%'";
    
    $data = mysql_query($sql);
    
    $arrcnt = -1;
    
    $dataArray= array();
    
    while($temp = mysql_fetch_assoc($data))
    {
        
        foreach ($temp as $key=>$val)
        {
            $temp[$key]         = stripslashes($val);
            $arrcnt++;
            
        }
        $dataArray[$arrcnt]     = $temp;
    }
    
    
  $list = "<ul style='width:auto;height:auto;'>";
        foreach($dataArray as $val)
    {
        $list = "<li>".$val['content']."</li>";
    }
    $list   .="</ul>";
    echo $list;

    ?>