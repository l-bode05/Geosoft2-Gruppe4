<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script>
$(document).ready(function(){
  // Bind to the submit event of our form
	$("#form").submit(function(event){

    // show that something is loading
        $('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.post('createcommentPHP.php', $(this).serialize(), function(data){
             
            // show the response
            $('#status').html(data);
             console.log("hh");
        }).fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             console.log("sdff");
        });
 
        // to prevent refreshing the whole page page
        return false;
	 });
 });


</script>

</head>
<body>
<form id="form" >
<fieldset>   
 <pre>
       <legend>Create Comment:</legend>

	<br>	<label for="title">Title:</label>
        <input type="text" Placeholder="Title" name="title" id="title" class="text" />
        <label for="url">Url:</label>
        <input type="text" Placeholder="Url" name="url" id="url" class="text" />
        <label for="comment">Comment:</label>
        <textarea id ="comment" name="comment" wrap="PHYSICAL" Placeholder="Comment" cols="30" rows="5"></textarea>
        <label for="mapage">Datum:</label>
        <input type="date" Placeholder="Datum" name="datum" id="datum" class="date" />
        <input type='submit' value='Submit' />
		
 </pre>
</fieldset>
</form> 
<div id="status"></div>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.js"></script>
</body>

</html>