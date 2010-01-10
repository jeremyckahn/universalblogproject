<p>This is the post form.</p>
	
	<? if($this->session->userdata("loggedIn")){ ?>

	<div style="color: #f00;"><?= validation_errors(); ?></div>
	<?= form_open('ubp/post'); ?>
		<div class ="label">Title:</div>
		<input id="txtTitle" class="txtTitle" type="text" name="title" value="<?= set_value('title'); ?>"/>
				
		<div class ="label">Post:</div>
		<textarea id = "postInput" class="txtPost" name="post" rows="10" cols="30"><?= set_value('post'); ?></textarea>
		
		<input type="submit" value="Submit" />
	</form>
	
	<?} else {?>
		<p>You need to be logged in to make a post.</p>
	<? } ?>