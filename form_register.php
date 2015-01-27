
<script>
	$(document).ready(function(){
		   // Bind to the submit event of our form
		$("#reg_form").submit(function(event){
				
			// Check InputFields
                        $('#reg_name').val(<?php echo $_SESSION["name"]; ?>);
			var valid=true;
			if($('#reg_name').val().length>12 || $('#reg_name').val().length<4) {
				$('#reg_name_status').html("Please chose a nickname between four and twelve chars.");
				valid=false;
			}
			if($('#reg_mail').val().length>12 || $('#reg_mail').val().length<4 
					|| $('#reg_mail').val().indexOf("@")<0 || $('#reg_mail').val().indexOf(".")<0) {
				$('#reg_mail_status').html("Please insert a valid mail between four and twenty chars.");
				valid=false;
			}
			if($('#reg_pw').val().length>12 || $('#reg_pw').val().length<4) {
				$('#reg_pw_status').html("Please chose a password between four and twelve chars.");
				valid=false;
			}
			if($('#reg_pw').val()!= $('#reg_pw2').val()) {
				$('#reg_pw_status').html("Please insert the same password in both fields.");
				valid=false;
			} 
			
			// Input is valid
			if(valid==true) {
				// Submit Post Request
				$.post('ret_register.php', $(this).serialize(), function(data){

					// Analyze the respons
					if(data ==1) { // Success
						$("#reg_form").hide();
						$('#reg_newmsg').html("Registered ang loggedin. have fun! Wait for site refreshing in 3 seconds..");
						setTimeout(function () { location.reload(true); }, 3000);
					} else { // Error
						if(data == -1) {
							$('#reg_status').html("The nickname '"+$('#reg_name').val()+"' alreadys exists. Please chose a different one or login, if you are the owner of this nickname." );
						} else if(data == -2) {
							$('#reg_status').html("An account with the mail adress '"+$('#reg_mail').val()+"' alreadys exists. Already registered? Then login." );
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

<form id="reg_form" method="post">
    <fieldset>   
     
           <legend>Sign In:</legend>

            <br>
            <label for="nickname">Nickname:</label>
            <?php
                if(IsLoggedIn()==true && $_COOKIE['password']=="") {
                    echo '<input type="text" name="reg_name" id="reg_name" value="'.$_COOKIE['name'].'" readonly="readonly" required class="text" />';
                } else {
                    echo '<input type="text" name="reg_name" id="reg_name" required class="text" />';
                }
            ?>
            

            <div id="reg_name_status" style="color:red"> </div>

            <label for="mail">E-Mail:</label>
            <input type="text" name="reg_mail" id="reg_mail" required class="text" />
            <div id="reg_mail_status" style="color:red"> </div>
            <label for="password">Password:</label>
            <input type="password" name="reg_pw" id="reg_pw" required class="text" />
            <label for="password2">Reiterate Password:</label>
            <input type="password" name="reg_pw2" id="reg_pw2" required class="text" />
            <div id="reg_pw_status" style="color:red"> </div>
            <div id="reg_status" style="color:red"> </div>
            <input type="submit" value="Sign In" class="button round" id="butcom1" />

     
    </fieldset>
</form>

<div id="reg_newmsg"> </div>
