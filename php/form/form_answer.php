
<script>
    $(document).ready(function(){
        // Bind to the submit event of our form
        $("#answer_form").submit(function(event){
            // Reset all fields
             $('#ans_username_status').html("");
             $('#ans_title_status').html("");
             $('#ans_comment_status').html("");


              
            // Check InputFields 
            var valid=true;
            if ($('#ans_username').length && $('#is_edit').val()=="edit_id" ) {
                if($('#ans_username').val().length>0 &&
                        ($('#ans_username').val().length > 12 || $('#ans_username').val().length < 4) ) {
                    $('#ans_username_status').html("Please insert an unique username between four and twelve chars.");
                    valid=false;
                }
            }

            if($('#ans_title').val().length>12 || $('#ans_title').val().length<4) {
                $('#ans_title_status').html("Please insert a title between four and twelve chars.");
                valid=false;
            }
            
             if($('#ans_comment').val().length<5) {
                $('#ans_comment_status').html("Please leave a comment with atleast five chars.");
                valid=false;
            }

            // Input is valid
            if(valid==true) {
                
                
                // Submit Post Request
                $.post('php/return/ret_answer.php', $(this).serialize(), function(data){
 
                    // Analyze the respons
                    if(data == 1) { // Success
                            $("#answer_form").hide();
                            $('#ans_newmsg').html("Created Comment");
                            
                            <?php
                                if(IsLoggedIn()==FALSE) 
                                {
                                    echo "$('#ans_newmsg').html('Created Comment');";
                                    echo "setTimeout(function () { location.reload(true); }, 3000);";
                                }
                            ?>
                    } else { // Error
                            if(data == -1) {
                                $('#ans_status').html("Username already taken. Please chose a different one" );
                            } 
                    }

                }).fail(function() { // Posting Error
                        alert( "Posting failed. Error Code: rfpost" );

                });
            } 

            // Prevent Site refreshing
            return false;
            
         });
     });


</script>

<form id="answer_form" method="post">
    <fieldset style="line-height:0.3">   
           <legend><b>Create Answer:</b></legend>
           
           <?php 
                if(IsLoggedIn()==false) {
                 echo "
                  <div id='userdiv'>
                    <label for='ans_username'><b>Username:</b></label>
                    <input type='text' Placeholder='Username (Optional)' name='ans_username' id='ans_username' class='text' />
                    <div id='ans_username_status' style='color:red'> </div>
                  </div>  ";
                } 
            ?>
            <input type="hidden" name="answer_to" id="answer_to" class="text" value="anid" />
            <input type="hidden" name="is_edit" id="is_edit" class="text" value="edit_id" />
 
            <label for="ans_title"><b>Title:</b></label>
            <input type="text" Placeholder="Title" name="ans_title" id="ans_title" class="text" />
            <div id="ans_title_status" style="color:red"> </div>
            
            <label for="ans_comment"><b>Comment:</b></label>
            <textarea name="ans_comment" id="ans_comment" wrap="PHYSICAL" Placeholder="Type your comment" cols="30" rows="5"></textarea>
            <div id="ans_comment_status" style="color:red"> </div>

            <label for="ans_comment"><b>Resources:</b></label>
            <textarea name="ans_resources" id="ans_resources" wrap="PHYSICAL" Placeholder="Resources (Optional)" cols="30" rows="5"></textarea>
             
            <div id="ans_status" style="color:red"> </div>
              
            <input type="submit" value="Submit" class="button round" id="butcom1"/>


    </fieldset>
</form>

<div id="ans_newmsg"> </div>

