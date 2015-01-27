<?php 
/*	
    Insert an answer

*/

    // Include library
    include '../database/dbfunctions.php';


    // Get Values
    $answer_to=$GLOBALS["conn"]->real_escape_string($_POST["answer_to"]);
    $content=$GLOBALS["conn"]->real_escape_string($_POST["ans_comment"]);
    $title=$GLOBALS["conn"]->real_escape_string($_POST["ans_title"]);
    $resources=$GLOBALS["conn"]->real_escape_string($_POST["ans_resources"]);
    $user_id=$GLOBALS["conn"]->real_escape_string($_POST["ans_username"]);
    
    
    // Edit mode
    if($GLOBALS["conn"]->real_escape_string($_POST["is_edit"])!="edit_id") {
        UpdateComment($GLOBALS["conn"]->real_escape_string($_POST["is_edit"]), $title, $content, $resources);
        echo "1";
    } else { // Normal Insert
        
        // Replace $user_id, if loggedin
        if(IsLoggedIn()==false){ 
            $user_id.="anonym";
        } else {
            $user_id=GetMyUserid();
        }
        
        echo InsertComment($answer_to, $content, $title, $resources,"",$user_id, "","", "");
    }
    
?>