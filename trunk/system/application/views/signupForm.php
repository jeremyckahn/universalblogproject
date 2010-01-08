<? if(!$this->session->userdata("loggedIn")){ ?>

<div style="color: #f00;"><?= validation_errors(); ?></div>
<?= form_open('ubp/signup'); ?>
	<div class ="label">Username:</div>
	<input id="txtUsername" class="txtStandard" type="text" name="username" value="<?= set_value('username'); ?>"/>
			
	<div class ="label">Password:</div>
	<input id="txtPassword" class="txtPassword" type="password" name="password" value="<?= set_value('password'); ?>"/>
	
	<div class ="label">Password confirm:</div>
	<input id="txtPasswordConfirm" class="txtPassword" type="password" name="passwordConfirm" value="<?= set_value('passwordConfirm'); ?>"/>
	
	<div class ="label">Email:</div>
	<input id="txtEmail" class="txtStandard" type="text" name="email" value="<?= set_value('email'); ?>"/>
	
	<input type="submit" value="Submit" />
</form>

<?} else {?>
<p>You are signed up.  Welcome!</p>
<? } ?>