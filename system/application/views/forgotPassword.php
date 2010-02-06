<h2>Forgot your password?</h2>

<p>Don't worry, just fill out your username and email, and we'll send you instructions on how to reset it.</p>

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

<script type="text/javascript">
/* <![CDATA[ */

	var txtUsername = document.getElementById("txtUsername");
	var txtEmail = document.getElementById("txtEmail");
	var usernameError = document.getElementById("usernameError");
	var emailError = document.getElementById("emailError");
	
	var username, email;
	var managerObj = new userManager();
	
	usernameError.style.display = "none";
	emailError.style.display = "none";

	function requestReminder()
	{
		username = txtUsername.value;
		email = txtEmail.value;
		
		userManager.changePassword(
			// serverScriptURL
			// oldPassword
			// newPassword
		);
	}

/* ]]> */
</script>