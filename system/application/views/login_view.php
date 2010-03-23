<?php if(!$this->session->userdata("loggedIn")){ 
	
	 $formAttributes = array(
						"id" => "loginForm",
						"class" => "formContainer"
); ?>

<h2 id="welcomeHeader">Hey again.  We just need a little info.</h2>

<?php echo form_open('ubp/login', $formAttributes); ?>

	<span class ="label">Username:</span>
	
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?php echo set_value('username'); ?>"/>
			
	<span class ="label">Password:</span>
	
	<input id="txtPassword" class="txtStandard" type="password" name="password" value="<?php echo set_value('password'); ?>"/>
	
	<?php if (validation_errors()) { ?>
	<div class="boxedErrorMessage"><?php echo validation_errors(); ?></div>
	<?php } ?>

	<div class="customUIButtonFrame">
		
		<span class="button rollover" onclick="login();">log in</span>
	
	</div>
	
	<?php echo anchor("ubp/forgotPassword", "I forgot my password", "class=\"forgotPassword rollover bracketize\""); ?>

</form>

<script type="text/javascript">
	function login()
	{
		document.getElementById("<?php echo $formAttributes["id"]; ?>").submit();
	}
</script>

<?php } else {?>
	<h1>Welcome back, <?php echo $this->session->userdata("username"); ?>.</h1>
	
	<p>Click <?php echo anchor("ubp/post", "here"); ?>  to make a post. You can also click your username under the navigation area to access your user control panel.</p>
<?php } ?>