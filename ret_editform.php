<?php
// Include library
include 'dbfunctions.php';


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Get Values
$id=$GLOBALS["conn"]->real_escape_string($_POST["id"]);
$arr=GetCommentById($id);
$title=$arr[0]["title"];
$content=$arr[0]["content"];


// Insert Answer form and input id
$html=$html."<br><br><br>";
$html=$html. requireToVar("form_answer.php");
$html= str_replace("anid", $id, $html);
$html= str_replace("edit_id", $id, $html);


// Start FillFormout function
$html=$html . "<script>$(document).ready(function(){
                FillAnswerForm('$title', '$content');
               });</script>";


echo $html;


?>