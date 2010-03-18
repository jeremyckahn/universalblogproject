<?php if ($passwordSuccessfullyChanged){ ?>

<p class="serverResponseOutput">Your password has been changed!  You may now <?php echo anchor("ubp/login", "log in"); ?> with your new password.</p>

<?php }elseif (!$requestIsValid){ ?>
	<div class="boxedErrorMessage">The "reset ID" you provided in the URL is either not valid, or has expired.  Please keep in mind that password reset requests expire after 20 minutes.  You may make another reset request <?php echo anchor("ubp/forgotPassword", "here"); ?>.</div>
<?php } else {
	
	$formAttributes = array(
						"id" => "passworResetForm",
						"class" => "formContainer"
		); 
?>

<h2>Password reset form</h2>

<?php echo form_open('ubp/resetPassword/resetID/' . $uniqueIdentifier, $formAttributes); ?>


	<span class ="label">New password:</span>
	
	<input id="txtPassword" class="txtStandard" type="password" name="password" value=""/>
	
	<span class ="label">Confirm password:</span>
	
	<input id="txtConfirmPassword" class="txtStandard" type="password" name="confirmPassword" value=""/>

</form>

<?php if (validation_errors()) { ?>
<div class="boxedErrorMessage"><?php echo validation_errors(); ?></div>
<?php } ?>

<div class="customUIButtonFrame">
	
	<span class="button rollover" onclick="reset();">reset password</span>

</div>

<script type="text/javascript">
	function reset()
	{
		document.getElementById("<?php echo $formAttributes["id"]; ?>").submit();
	}
</script>
	
<?php } ?>