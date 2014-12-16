script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
<script>
	$(document).ready(function(){
		   // Bind to the submit event of our form
		$("#login_form").submit(function(event){
				
			// Check InputFields
			var valid=true;

			if($('#log_nickname').val().length>12 || $('#log_nickname').val().length<4  {
				$('#log_nickname_status').html("Please insert a valid nickname between four and twelve chars.");
				valid=false;
			}
			
			if($('#log_pass').val().length>12 || $('#log_pass').val().length<4) {
				$('#log_pass_status').html("Please insert your password. This one is too short or long.");
				valid=false;
			}
			

			
			// Input is valid
			if(valid==true) {
				// Submit Post Request
				$.post('ret_login.php', $(this).serialize(), function(data){

					// Analyze the respons
					if(data ==1) { // Success
						$("#login_form").hide();
						$('#login_newmsg').html("Registered ang loggedin. have fun! Wait for site refreshing in 3 seconds..");
						setTimeout(function () { location.reload(true); }, 3000);
					} else { // Error
						if(data == 0) {
							$('#reg_status').html("Wrong login datas for '"+$('#log_nickname').val()+"'. " );
							$('#log_pass').val("");
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



<form id="login_form" method="post" >
	<fieldset>   
	 <pre>
			<legend>Login:</legend>
			<label for="log_nickname">Nickname:</label>
			<input type="text" id="log_nickname" class="text" />
			<div id="log_nickname_status" style="color:red"> </div>
			
			<label for="log_pass">Password:</label>
			<input type="password" id="log_pass" class="text" />
			<div id="log_pass_status" style="color:red"> </div>
			
			<input type="submit" value="Login" class="button round" id="butcom1" />
	 </pre>
	</fieldset>
</form>

<div id="login_newmsg"> </div>
