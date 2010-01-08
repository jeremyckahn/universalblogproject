<? if(!$this->session->userdata("loggedIn")){ ?>

<div style="color: #f00;"><?= validation_errors(); ?></div>
<?= form_open('ubp/login'); ?>
	<span class ="label">Username:</span>
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?= set_value('username'); ?>"/>
			
	<span class ="label">Password:</span>
	<input id="txtPassword" class="txtPassword" type="password" name="password" value="<?= set_value('password'); ?>"/>
	<input type="submit" value="Submit" />
</form>

<?} else {?>
	<p>You are logged in.  Welcome back!</p>
<? } ?>