<? if(!$this->session->userdata("loggedIn")){  
	
	 $formAttributes = array(
						"id" => "signupForm",
						"class" => "formContainer"
); ?>

<h2>Welcome to the Universal Blog Project.</h2>

<p>We just need a little information and you'll be ready to post.</p>

<?= form_open('ubp/signup', $formAttributes); ?>
	
	<div class ="label">Username:</div>
	
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?= set_value('username'); ?>"/>
			
	<div class ="label">Password:</div>
	
	<input id="txtPassword" class="txtStandard" type="password" name="password" value="<?= set_value('password'); ?>"/>
	
	<div class ="label">Please re-type password, just to be sure:</div>
	
	<input id="txtPasswordConfirm" class="txtStandard" type="password" name="passwordConfirm" value="<?= set_value('passwordConfirm'); ?>"/>
	
	<div class ="label">Email:</div>
	
	<input id="txtEmail" class="txtStandard" type="text" name="email" value="<?= set_value('email'); ?>"/>
	
	<div style="color: #f00;"><?= validation_errors(); ?></div>
	
	<!--<input type="submit" value="Submit" />-->
	<div class="customUIButtonFrame">
		
		<span class="button rollover" onclick="signup();">sign up</span>
	
	</div>

</form>

<script type="text/javascript">
	function signup()
	{
		document.getElementById("<?= $formAttributes["id"] ?>").submit();
	}
</script>

<?} else {?>
	<h1>Welcome to the project, <?= $this->session->userdata("username") ?>.</h1>
	
	<p>You're all signed up and logged in.  To get started, click <?= anchor("ubp/post", "post"); ?> to the right and write a blog.  It's that easy.</p>
<? } ?>