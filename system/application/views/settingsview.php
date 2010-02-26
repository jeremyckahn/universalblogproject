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
	
	<span id="passwordError" class="errorText hidden"></span>
	
	<span id="passwordRequestOutput"></span>
	
	<input id="txtConfirmNewPassword" class="txtStandard" type="password" name="txtConfirmNewPassword" value=""/>

</div>

<div class="customUIButtonFrame">
			
	<span id="btnSubmitNewPassword" class="button rollover left" onclick="submitPassword();">change password</span>

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
	
	// These are not assigned yet because the pointer will be lost the use authenticates an action.  Assigned in registerModal()
	var txtCurrentPassword, btnAuthenticateChanges, btnCancelChanges;
	
	var currentPassword, newPassword, newPasswordConfirm;
	
	//passwordError.style.display = "none";
	passwordRequestOutput.style.display = "none";
	
	var modal = new modalManager("modal", modalContainer);
	var user = new userManager();
	
	modal.setFadeInCompleteEventHandler(modal, function(){
		registerModal();
	});
	
	function authenticatePasswordChange(){
		modal.showModal(modal);
	}
	
	function submitPassword(){
		//currentPassword = txtCurrentPassword.value;
		newPassword = txtNewPassword.value;
		newPasswordConfirm = txtConfirmNewPassword.value;
		
		if (newPassword != newPasswordConfirm){
			setError(passwordError, txtConfirmNewPassword, "The passwords do not match.");
		}
		
		/*user.resetPasswordRequest(
				"<?= base_url() . "index.php/ubp/changePassword"; ?>", //serverScriptURL
				currentPassword, // currentPasssword
				newPassword // newPassword
			);*/
	}
	
	function killModal(){
		modal.hideModal(modal);	
	}
	
	function registerModal(){
		txtCurrentPassword = document.getElementById("txtCurrentPassword");
		btnAuthenticateChanges = document.getElementById("btnAuthenticateChanges");
		btnCancelChanges = document.getElementById("btnCancelChanges");
		txtCurrentPassword.value = "";
		txtCurrentPassword.focus();
	}
	
	function setError(errorOutput, errorField, errorText){
		//errorOutput.style.display = "inline";
		removeClass(errorOutput, "hidden");
		addClass(errorField, "errorHighlight");
		errorOutput.innerHTML = errorText;
		errorField.onfocus = function(){
			addClass(errorOutput, "hidden");
			removeClass(errorField, "errorHighlight");
			errorOutput.innerHTML = "";	
		};
	}
	
	
	
</script>