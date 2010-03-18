<h2>Forgot your password?</h2>

<p id="instructions">Don't worry, just fill out your username and email, and we'll send you instructions on how to reset it.</p>

<div id="forgotPasswordForm" class="forgotPasswordForm">

	<div id="fieldContainer" class="formContainer">
	
		<span class ="label">Username:</span>
		
		<span id="usernameError" class="errorText"></span>
		
		<input id="txtUsername" class="txtStandard" type="text" name="username" value="" onfocus="resetErrorStyle(this, usernameError);"/>
		
		<span class ="label">Email:</span>
		
		<span id="emailError" class="errorText"></span>
		
		<input id="txtEmail" class="txtStandard" type="text" name="email" value="" onfocus="resetErrorStyle(this, emailError);"/>
	
	</div>
	
	<div id="controlContainer" class="customUIButtonFrame">
			
		<span id="btnRequestEmail" class="button rollover left" onclick="requestReminder();">email me</span>
	
	</div>

</div>

<div id="requestOutput">

</div>

<!-- JS includes -->

<script type="text/javascript" src="<?php echo base_url() . "js/userManager.js"; ?>"></script>

<script type="text/javascript">
/* <![CDATA[ */

	var forgotPasswordForm = document.getElementById("forgotPasswordForm");
	var instructions = document.getElementById("instructions");
	var txtUsername = document.getElementById("txtUsername");
	var txtEmail = document.getElementById("txtEmail");
	var usernameError = document.getElementById("usernameError");
	var emailError = document.getElementById("emailError");
	
	var username, email;
	var manager = new userManager();
	
	usernameError.style.display = "none";
	emailError.style.display = "none";

	function requestReminder()
	{
		username = txtUsername.value;
		email = txtEmail.value;
		
		manager.setPasswordResetCompleteEventHandler(manager, function(){			
			var response = manager.serverJSONResponse;
			
			if (response.requestComplete)
			{
				instructions.style.display = "none";
				forgotPasswordForm.innerHTML = response.requestMessage;
			}
			else
			{
				if (response.errorInfo.invalidArg == "username")
				{
					usernameError.style.display = "inline";
					addClass(txtUsername, "errorHighlight");
					usernameError.innerHTML = response.errorInfo.message;
				}
				
				if (response.errorInfo.invalidArg == "email")
				{
					emailError.style.display = "inline";
					addClass(txtEmail, "errorHighlight");
					emailError.innerHTML = response.errorInfo.message;
				}
			}
		});
		
		manager.resetPasswordRequest(
			"<?php echo base_url() . "index.php/ubp_ajax/createPasswordResetrequest"; ?>", // serverScriptURL
			username, // username
			email // email
		);
	}

/* ]]> */
</script>