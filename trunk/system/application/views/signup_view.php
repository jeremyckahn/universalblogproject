<?php if(!$this->session->userdata("loggedIn")){  
	
	 $formAttributes = array(
						"id" => "signupForm",
						"class" => "formContainer"
); ?>

<h2>Welcome to the Universal Blog Project.</h2>

<p>We just need a little information and you'll be ready to post.</p>

<?php echo form_open('ubp/signup', $formAttributes); ?>
	
	<div class ="label">Username:</div>
	
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?php echo set_value('username'); ?>"/>
			
	<div class ="label">Password:</div>
	
	<input id="txtPassword" class="txtStandard" type="password" name="password" value="<?php echo set_value('password'); ?>"/>
	
	<div class ="label">Please re-type password, just to be sure:</div>
	
	<input id="txtPasswordConfirm" class="txtStandard" type="password" name="passwordConfirm" value="<?php echo set_value('passwordConfirm'); ?>"/>
	
	<div class ="label">Email:</div>
	
	<input id="txtEmail" class="txtStandard" type="text" name="email" value="<?php echo set_value('email'); ?>"/>
	
	<?php if (validation_errors()) { ?>
	<div class="boxedErrorMessage"><?php echo validation_errors(); ?></div>
	<?php } ?>
	
	<div class="customUIButtonFrame">
		
		<span class="button rollover" onclick="signup();">sign up</span>
	
	</div>

</form>

<script type="text/javascript">
	function signup()
	{
		document.getElementById("<?php echo $formAttributes["id"]; ?>").submit();
	}
</script>

<?php } else {?>
	<h1>Welcome to the project, <?php echo $this->session->userdata("username"); ?>.</h1>
	
	<p>You're all signed up and logged in.  To get started, click <?php echo anchor("ubp/post", "post"); ?> to the right and write a blog.  It's that easy.</p>
<?php } ?>