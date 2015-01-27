 
<script>
    $(document).ready(function(){
        // Bind to the submit event of our form
        $("#insertcomment_form").submit(function(event){

            // Reset all fields
             $('#ins_username_status').html("");
             $('#ins_title_status').html("");
             $('#ins_latlong_status').html("");
             $('#ins_url_status').html("");
             $('#ins_comment_status').html("");


              
            // Check InputFields
            var valid=true;
            if ($('#ins_username' ).length ) {
                if($('#ins_username').val().length>0 &&
                        ($('#ins_username').val().length>12 || $('#ins_username').val().length<4) ) {
                    $('#ins_username_status').html("Please insert an unique username between four and twelve chars.");
                    valid=false;
                }
            }

            if($('#ins_title').val().length>12 || $('#ins_title').val().length<4) {
                $('#ins_title_status').html("Please insert a title between four and twelve chars.");
                valid=false;
            }

            if($('#Latitude').val().length<1 || $('#Longitude').val().length<1) {
                $('#ins_latlong_status').html("Please chose a position.");
                valid=false;
            }

            if($('#ins_url').val().length<7) {
                $('#ins_url_status').html("Please leave a url with atleast three chars.");
                valid=false;
            }
            
             if($('#ins_comment').val().length<3) {
                $('#ins_comment_status').html("Please leave a comment with atleast three chars.");
                valid=false;
            }


            
            // Input is valid
            if(valid==true) {
                
                // Submit Post Request
                $.post('php/return/ret_insertcomment.php', $(this).serialize(), function(data){

                    // Analyze the responds
                    if(data == 1) { // Success
                            $("#insertcomment_form").hide();
                            $('#ins_newmsg').html("Created Comment");
                            ShowMarkers_all();
                            
                            <?php
                                if(IsLoggedIn()==FALSE) 
                                {
                                    echo "$('#ins_newmsg').html('Created Comment');";
                                    echo "setTimeout(function () { location.reload(true); }, 3000);";
                                }
                            ?>
                    } else { // Geodata already inserted
                        $('#ins_url_status').html("This dataset already exists. You can leave a comment <a href='http://giv-geosoft2d.uni-muenster.de/steffen/index.php?comment="+data.trim()+"'>there</a>.");
                    } 
                    /*else { // Error
                            if(data == -1) {
                                $('#ins_status').html("Username already taken. Please chose a different one" );
                            } 
                    }*/

                }).fail(function() { // Posting Error
                        alert( "Posting failed. Error Code: rfpost" );

                });
            } 

            // Prevent Site refreshing
            return false;
            
         });
         
         
         // Bind tagbox
         $('#singleFieldTags').tagit({
            availableTags: usedTags,
            // This will make Tag-it submit a single form value, as a comma-delimited field.
            singleField: true,
            singleFieldNode: $('#ins_tags')
         });
    
     });


</script>

<form id="insertcomment_form" method="post">
    <fieldset style="line-height:0.3">   
           <legend>Create Comment:</legend>
           
           

            <label for="ins_title">Title:</label>
            <input type="text" Placeholder="Title" name="ins_title" id="ins_title" class="text" />
            <div id="ins_title_status" style="color:red"> </div>
            
            
            <label for="Latitude">Latitude:</label>
            <input id="Latitude" readonly="readonly" type="number" name="Latitude"/> 
            <label for="Longitude">Longitude:</label>
            <input id="Longitude" readonly="readonly" type="number" name="Longitude"/>
            <div id="ins_latlong_status" style="color:red"> </div>
             
            <label for="ins_url">Url:</label>
            <input type="text" Placeholder="Url" name="ins_url" id="ins_url" class="text" />
            <div id="ins_url_status" style="color:red"> </div>
            
            <label for="ins_comment">Comment:</label>
            <textarea name="ins_comment" id="ins_comment" wrap="PHYSICAL" Placeholder="Your comment" cols="30" rows="5"></textarea>
            <div id="ins_comment_status" style="color:red"> </div>
            
            <label for="ins_resources">Resources:</label>
            <textarea name="ins_resources" id="ins_resources" wrap="PHYSICAL" Placeholder="Resources (Optional)" cols="30" rows="5"></textarea>
            
            
            <label for="ins_tags">Tags:</label>
            <input name="ins_tags" id="ins_tags" value=""  type='hidden'>
            <ul id="singleFieldTags"></ul> 
            <div id="ins_tags_status" style="color:red"> </div>

            
            <label for="ins_datum">Date:</label>
            <input type="text" id="ins_date" name="ins_date" Placeholder="Date" >
      
            <script>
                $(function() {
                    var date = $('#ins_date').datepicker({ dateFormat: 'dd.mm.yy' }).val();
                });
            </script>
            
            <div id="ins_datum_status" style="color:red"> </div>
             
            <div id="ins_status" style="color:red"> </div>
            <br><br>  
                Use the buttons on the left side to create a spatial expansion.
            <br><br>
            <input type="submit" value="Submit" class="button round" />

            <input type="hidden" Placeholder="space" name="ins_spatialexp" id="ins_spatialexp" class="text" />
    </fieldset>
</form>

<div id="ins_newmsg"> </div>