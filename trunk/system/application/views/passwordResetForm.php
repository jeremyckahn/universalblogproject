<? if ($passwordSuccessfullyChanged){ ?>

<p class="blockNarrow">Your password has been changed!  You may now <?= anchor("ubp/login", "log in"); ?> with your new password.</p>

<? }elseif (!$requestIsValid){ ?>
	<div class="boxedErrorMessage">The "reset ID" you provided in the URL is either not valid, or has expired.  Please keep in mind that password reset requests expire after 20 minutes.  You may make another reset request <?= anchor("ubp/forgotPassword", "here"); ?>.</div>
<? } else {
	
	$formAttributes = array(
						"id" => "passworResetForm",
						"class" => "formContainer"
		); 
?>

<h2>Password reset form</h2>

<?= form_open('ubp/resetPassword/resetID/' . $uniqueIdentifier, $formAttributes); ?>


	<span class ="label">New password:</span>
	
	<input id="txtPassword" class="txtStandard" type="password" name="password" value=""/>
	
	<span class ="label">Confirm password:</span>
	
	<input id="txtConfirmPassword" class="txtStandard" type="password" name="confirmPassword" value=""/>

</form>

<? if (validation_errors()) { ?>
<div class="boxedErrorMessage"><?= validation_errors(); ?></div>
<? } ?>

<div class="customUIButtonFrame">
	
	<span class="button rollover" onclick="reset();">reset password</span>

</div>

<script type="text/javascript">
	function reset()
	{
		document.getElementById("<?= $formAttributes["id"] ?>").submit();
	}
</script>
	
<? } ?>