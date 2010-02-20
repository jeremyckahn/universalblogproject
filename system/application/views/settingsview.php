<?php

	// If the user is not logged in, send them back to the index page.
	if(!$this->session->userdata("loggedIn")){
		redirect("ubp/index", "refresh");
	} 
?>

<h2>Change your password</h2>

<div class="formContainer">
	
	<div class ="label">New password:</div>
	
	<input id="txtNewPassword" class="txtStandard" type="password" name="txtNewPassword" value=""/>
	
	<div class ="label">Confirm new password:</div>
	
	<span id="passwordError" class="errorText"></span>
	
	<span id="passwordRequestOutput"></span>
	
	<input id="txtConfirmNewPassword" class="txtStandard" type="password" name="txtConfirmNewPassword" value=""/>

</div>

<div class="customUIButtonFrame">
			
	<span id="btnSubmitNewPassword" class="button rollover left" onclick="submitPassword();">change password</span>

</div>

<script type="text/javascript">
	var txtNewPassword = document.getElementById("txtNewPassword");
	var txtConfirmNewPassword = document.getElementById("txtConfirmNewPassword");
	var passwordError = document.getElementById("passwordError");
	var passwordRequestOutput = document.getElementById("passwordRequestOutput");
	
	var currentPassword, newPassword, newPasswordConfirm;
	
	passwordError.style.display = "none";
	passwordRequestOutput.style.display = "none";
	
	function submitPassword(){
		newPassword = txtNewPassword.value;
		newPasswordConfirm = txtConfirmNewPassword.value;
	}
	
</script>