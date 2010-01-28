<? if(!$this->session->userdata("loggedIn")){ 
	
	 $formAttributes = array(
						"id" => "loginForm",
						"class" => "formContainer"
); ?>

<h2 id="welcomeHeader">Hey again.  We just need a little info.</h2>

<?= form_open('ubp/login', $formAttributes); ?>

	<span class ="label">Username:</span>
	
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?= set_value('username'); ?>"/>
			
	<span class ="label">Password:</span>
	
	<input id="txtPassword" class="txtPassword" type="password" name="password" value="<?= set_value('password'); ?>"/>
	
	<div style="color: #f00;"><?= validation_errors(); ?></div>

	<div class="customUIButtonFrame">
		
		<span class="button rollover" onclick="login();">log in</span>
	
	</div>

</form>

<script type="text/javascript">
	function login()
	{
		document.getElementById("<?= $formAttributes["id"] ?>").submit();
	}
</script>

<?} else {?>
	<h1>Welcome back, <?= $this->session->userdata("username") ?>.</h1>
	
	<p>Click <?= anchor("ubp/post", "here"); ?>  to make a post. You can also click your username under the navigation area to access your user control panel.</p>
<? } ?>