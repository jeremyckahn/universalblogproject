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
			
	<span id="btnSubmitNewPassword" class="button rollover left" onclick="authenticatePasswordChange();">change password</span>

</div>

<div id="modalContainer" style="display: none;">
	
	<h2>Please confirm your current password.</h2>
	
	<div class="formContainer">
	
		<div class ="label">Current password:</div>
		
		<input id="txtCurrentPassword" class="txtStandard" type="password" name="txtCurrentPassword" value=""/>
		
	</div>
	
	<div class="customUIButtonFrame main">
		
		<span id="btnAuthenticateChanges" class="button rollover left" onclick="killModal();">authenticate</span>
		
		<span id="btnCancelChanges" class="button rollover right" onclick="killModal();">cancel</span>
	
	</div>	
	
</div>


<script type="text/javascript" src="<?= base_url() . "js/userManager.js" ?>"></script>
<script type="text/javascript" src="<?= base_url() . "js/modalManager.js" ?>"></script>

<script type="text/javascript">
	var txtNewPassword = document.getElementById("txtNewPassword");
	var txtConfirmNewPassword = document.getElementById("txtConfirmNewPassword");
	var passwordError = document.getElementById("passwordError");
	var passwordRequestOutput = document.getElementById("passwordRequestOutput");
	var modalContainer = document.getElementById("modalContainer");
	
	var modal = new modalManager("modal", modalContainer.innerHTML);
	
	var currentPassword, newPassword, newPasswordConfirm;
	
	passwordError.style.display = "none";
	passwordRequestOutput.style.display = "none";
	
	function authenticatePasswordChange(){
		modal.showModal(modal);
	}
	
	function submitPassword(){
		newPassword = txtNewPassword.value;
		newPasswordConfirm = txtConfirmNewPassword.value;
	}
	
	function killModal(){
		modal.killModal(modal);	
	}
	
	//alert(document.body.clientHeight);
	
</script>