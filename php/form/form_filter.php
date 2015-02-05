
<script>
    $(document).ready(function(){
        
        // Detect distance change
         $("#distance").change(function() {
            UpdateCircle($('#f_Latitude').val(),$('#f_Longitude').val(),$('#distance').val());
        });

                        
        // Form submit event
        $("#filter_form").submit(function(event){
           UpdateCircle($('#f_Latitude').val(),$('#f_Longitude').val(),$('#distance').val());
           
            // Submit Post Request
            $.post('php/return/ret_getfirstcomments.php', $(this).serialize(), function(data){
                // Analyze the respons
               $('#results').html(data.split("°")[0]);
               ShowMarkersFromJson(data.split("°")[1]);
               
            }).fail(function() { // Posting Error
                    alert( "Posting failed. Error Code: rfpost" );

            });

            // Prevent Site refreshing
            return false;
            
         });
         
         // Fill forms
         <?php
            if($GLOBALS["conn"]->real_escape_string($_GET['search'])!="") { 
                echo "$('#keyword').val('".$GLOBALS["conn"]->real_escape_string($_GET['keyword'])."');"
                     . "$('#date_start').val('".$GLOBALS["conn"]->real_escape_string($_GET['date_start'])."');"
                     . "$('#date_end').val('".$GLOBALS["conn"]->real_escape_string($_GET['date_end'])."');"
                     . "$('#bbox').val('".$GLOBALS["conn"]->real_escape_string($_GET['bbox'])."');"
                     . "ShowTab('panel3');"
                     . "$('#filter_form').submit();";
            }
         ?>
                 
                 
     });

     // 
     
    

//     UpdateCircle(lat, lng, distance)
     

     // Permalink for search
     function GeneratePermalink() 
     {
         var query ="keyword="+$('#keyword').val()+"&date_start="+$('#date_start').val()+
                 "&date_end="+$('#date_end').val()+"&bbox="+$('#bbox').val();
         
         var win = window.open("index.php?search=permalink&"+query, '_blank');
     }
     
     // Autosuggest
    function autoSuggest()
    {
        var autoSuggestVal = $('#keyword').val();
        if(autoSuggestVal !== ''&& autoSuggestVal.length > 2)
        {
            $.ajax({
                url: 'php/return/ret_autosuggest.php?query='+autoSuggestVal,
                success: function(result)
                {
                    $('#autosuggest-container').html(result);
                }
            });
        }
    }
     // Hide Autosuggest
    function hideautoSuggest() 
    {
        document.getElementById("autosuggest-container").innerHTML="";
    }

	
</script>



<form id="filter_form" method="post">
    <fieldset style="line-height:0.3">   
           <legend id="filtercom">Filter comments:</legend>
           
            <br> 
            <br>
            
            <b>Keyword:</b><br><br><br> 
            <input type="text" Placeholder="Keyword" id="keyword" name="keyword" onkeyup="autoSuggest()" class="text" />
            <div id="autosuggest-container"></div>
            <br> 
            <br>
            <b>Rating:</b><br><br><br> 
            <input type="text" Placeholder="Rating minimum" id="rate_min"  name="rate_min" class="text" /> 
            
            <input type="text" Placeholder="Rating maximum" id="rate_max" name="rate_max" class="text" />
            <br> 
            <br>
            
             <b>Date: </b><br><br><br> 
             <script>
                $(function() {
                var date = $('#date_start').datepicker({ dateFormat: 'dd.mm.yy' }).val();
            });
            </script>
            <input type="text" Placeholder="Date start" id="date_start"  name="date_start" class="text" /> 
            
            <script>
                $(function() {
                var date = $('#date_end').datepicker({ dateFormat: 'dd.mm.yy' }).val();
            });
            </script>
            <input type="text" Placeholder="Date end" id="date_end" name="date_end" class="text" />
            <br> 
            <br>
            
            <b>Bounding Box:</b><br>
            <br> <br>
			<i>Draw a rectangle to set a bounding box or set values manually.</i>
            <br> <br><br>
            <input type="text" Placeholder="minx miny maxx maxy" id="bbox" name="bbox" class="text" />
            
            <fieldset>
            <b>Area:</b><br>
			<br> <br>
			<i>Click on map to set values.</i>
			<br>
            <br>
            <br>
            <label for="Latitude">Lat/Longitude:</label>
            <input id="f_Latitude" readonly="readonly" type="number" name="f_Latitude"/> 
            <input id="f_Longitude" readonly="readonly" type="number" name="f_Longitude"/>
            <br><br><br>
            <a id="searchpermalink" onclick="SetFilterUserposition()"><b>Set to your position </b></a>
            <br><br><br><br><br>
            <label for="Distance"><b>Radius (100m): </b></label>
            <input id="distance" type="number" name="distance"/>
            </fieldset>
            
            
            <br><br>
            
            
            <input type="submit" value="Submit" class="button round" />
            <br><br><br>
            <a id="searchpermalink" onclick="GeneratePermalink()">Generate Permalink</a>
           
           
            <br>
           
    </fieldset>
     
</form>
<br><br><br>
 
    <div id="results"> </div>

