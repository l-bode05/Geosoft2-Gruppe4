<script src="js/kmlgpxtogeojson.js">
</script>

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

            if($('#ins_title').val().length>30 || $('#ins_title').val().length<4) {
                $('#ins_title_status').html("Please insert a title between four and thirty chars.");
                valid=false;
            }

            if($('#Latitude').val().length<1 || $('#Longitude').val().length<1) {
                $('#ins_latlong_status').html("Please chose a correct position.");
                valid=false;
            }

            if($('#ins_url').val().length<3) {
                $('#ins_url_status').html("Please leave a url with atleast three chars.");
                valid=false;
            }
            
             if($('#ins_comment').val().length<3) {
                $('#ins_comment_status').html("Please leave a comment with atleast three chars.");
                valid=false;
            }


            
            // Input is valid
            if(valid==true) {
                // Add "anonym" for blank username field
                if ($('#ins_username').length ) {
                    $('#ins_username').val("anonym");
                }
                
                // Submit Post Request
                $.post('php/return/ret_insertcomment.php', $(this).serialize(), function(data){

                    // Analyze the responds
                    if(data == 1) { // Success
                            $("#insertcomment_form").hide();
                            $('#ins_newmsg').html("Created Comment");
                            ShowMarkers_all();
                            // 
                            <?php
                                if(IsLoggedIn()==FALSE) 
                                {
                                    echo "$('#ins_newmsg').html('Created Comment');";
                                    echo "setTimeout(function () { location.reload(true); }, 3000);";
                                }
                            ?>
                    } else { // Error
                            if(data == -1) {
                                $('#ins_status').html("Username already taken. Please chose a different one" );
                            } 
                    }

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

	function DetectUrlChange() {
		var curv=$("#ins_url").val();
		
		if($('#ins_url').val().length>4) {
			var format="";
			
			// Get format
			if(curv.toLowerCase().contains(".kml")) {
				format="kml";
			}else if(curv.toLowerCase().contains(".gpx")) {
				format="gpx";
			}else if(curv.toLowerCase().contains("bbox=")) {
				format="query";
			}else if(curv.toLowerCase().contains("wms/") || curv.toLowerCase().contains("/wms")) {
				format="wms";
			};
		  
			
			// Get extern content
			$("#spatialexp_status").val("Loading..");
			if(format!="") {
				curv=curv.replace("&", "|");
				
				$.post('php/return/ret_getexternfile.php', "url="+curv+"&format="+format, function(data){
					if(data==-1) {

						$("#spatialexp_status").val("Can't read bounding box.  You can set spatial expansion manually.");
					} else {
						if(format=="kml" || format=="gpx") data=ConvertKmlToGeoJson(data);
						$("#ins_spatialexp").val(data);
						
						// Mark as detected and show it
						$("#spatialexp_status").val("Set. Automatically created from URL. Detected format: "+format);
						ShowSpaceExp($("#ins_spatialexp").val());
					}
				});
			} else {
				$("#spatialexp_status").val("Unknown format. You can set spatial expansion manually");
			}
		}
	}
</script>

<form id="insertcomment_form" method="post">
    <fieldset style="line-height:0.3">   
           <legend>Create Comment:</legend>
           
           <?php 
                if(IsLoggedIn()==false) {
                   /* echo "
                        <label for='ins_username'>Username:</label>
                        <input type='text' Placeholder='(Optional) Username' name='ins_username' id='ins_username' class='text' />
                        <div id='ins_username_status' style='color:red'> </div>
                        ";
                */} 
            ?>

            <label for="ins_title">Title:</label>
            <input type="text" Placeholder="Title" name="ins_title" id="ins_title" class="text" />
            <div id="ins_title_status" style="color:red"> </div>
            
            
            <label for="Latitude">Latitude:</label>
            <input id="Latitude" readonly="readonly" type="number" name="Latitude"/> 
            <label for="Longitude">Longitude:</label>
            <input id="Longitude" readonly="readonly" type="number" name="Longitude"/>
            <div id="ins_latlong_status" style="color:red"> </div>
             
             <label for="ins_url">Url:</label>
            <input type="text" Placeholder="Url" name="ins_url" id="ins_url" class="text" onblur="DetectUrlChange()"/>
			
			<label for="spatialexp_status">Spatial expansion:</label>
			<input type="text" Placeholder="Null - insert wms/wmts/kml/gpx/gml or use toolbar on the left side" name="spatialexp_status" id="spatialexp_status" readonly="readonly" class="text" onblur=""/>
			
			
           
            
            <label for="ins_comment">Comment:</label>
            <textarea name="ins_comment" id="ins_comment" wrap="PHYSICAL" Placeholder="Type your comment" cols="30" rows="5"></textarea>
            <div id="ins_comment_status" style="color:red"> </div>
            
            
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
              
           
            <input type="submit" value="Submit" class="button round" />

            <input type="hidden" Placeholder="space" name="ins_spatialexp" id="ins_spatialexp" class="text" />


    </fieldset>
</form>

<div id="ins_newmsg"> </div>