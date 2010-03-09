<?php

	// If the user is not logged in, send them back to the index page.
	if(!$this->session->userdata("loggedIn")){
		redirect("ubp/index", "refresh");
	} 
?>

<h2>Change your password</h2>

<div class="formContainer">
	
	<span id="passwordChangeError" class="errorText hidden"></span>
	
	<div class ="label">New password:</div>
	
	<input id="txtNewPassword" class="txtStandard" type="password" name="txtNewPassword" value=""/>
	
	<div class ="label">Confirm new password:</div>
	
	<span id="passwordConfirmError" class="errorText hidden"></span>
	
	<input id="txtConfirmNewPassword" class="txtStandard" type="password" name="txtConfirmNewPassword" value=""/>
	
	<div id="passwordRequestOutput" class="serverResponseOutput centerAlign hidden"></div>

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
		
		<span id="btnAuthenticateChanges" class="button rollover left" onclick="submit();">authenticate</span>
		
		<span id="btnCancelChanges" class="button rollover right" onclick="killModal();">cancel</span>
	
	</div>	
	
</div>


<script type="text/javascript" src="<?= base_url() . "js/userManager.js" ?>"></script>
<script type="text/javascript" src="<?= base_url() . "js/modalManager.js" ?>"></script>

<script type="text/javascript">
	var txtNewPassword = document.getElementById("txtNewPassword");
	var txtConfirmNewPassword = document.getElementById("txtConfirmNewPassword");
	var passwordConfirmError = document.getElementById("passwordConfirmError");
	var passwordChangeError = document.getElementById("passwordChangeError");
	var passwordRequestOutput = document.getElementById("passwordRequestOutput");
	var modalContainer = document.getElementById("modalContainer");
	
	// These are not assigned yet because the reference will be lost when the user authenticates an action.  Assigned in registerModal()
	var txtCurrentPassword, btnAuthenticateChanges, btnCancelChanges;
	
	var currentPassword, newPassword, newPasswordConfirm;
	
	//passwordRequestOutput.style.display = "none";
	
	// Pretend enums
	var changePassword = 1;
	var changeEmail = 2;
	var changeSize = 3;
	
	// Placeholder, used for submit() later
	var submitAction;
	
	var modal = new modalManager("modal", modalContainer);
	var user = new userManager();
	
	modal.setFadeInCompleteEventHandler(modal, function(){
		registerModal();
	});
	
	function promptForPassword(){
		modal.showModal(modal);
	}
	
	function submitPassword(){
		newPassword = txtNewPassword.value;
		newPasswordConfirm = txtConfirmNewPassword.value;
		
		if (newPassword != newPasswordConfirm){
			setError(passwordConfirmError, txtConfirmNewPassword, "The passwords do not match.");
		}
		else if(newPassword == ""){
			setError(passwordChangeError, txtNewPassword, "You did not specify a new password.");
		}else {
			submitAction = changePassword;
			promptForPassword();
		}
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
	
	
	
	function submit(){
		currentPassword = txtCurrentPassword.value;
		
		switch(submitAction){
			case changePassword:
				user.setPasswordChangeCompleteEventHandler(user, function(){
					
					if (user.serverJSONResponse.passwordChanged){
						setRemovableOutput(passwordRequestOutput, user.serverJSONResponse.messages[0]);
					}
					else{
						setError(passwordChangeError, txtCurrentPassword, user.serverJSONResponse.messages[0]);
					}
					
				});
				
				user.resetPassword(
					"<?= base_url() . "index.php/ubp/changePassword"; ?>", //serverScriptURL
					currentPassword, // currentPasssword
					newPassword // newPassword
				);
				break;
			case changeEmail:
			
				break;
			case changeSize:
		
				break;
		}
		
		modal.hideModal(modal);
	}
	
	
	
</script>