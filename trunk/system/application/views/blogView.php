<p>This is the blog view.</p>

<?= form_open('ubp/index'); ?>
<? foreach ($postArray as $post){ ?>
	<?= htmlentities(urldecode($post['title'])) . "\n" ?>
	<br/>
	<?= htmlentities(urldecode($post['post'])) . "\n" ?>
	<? if($this->session->userdata("loggedIn")){ ?>
	<button type="submit" name="blacklistButton" type="button" value="<?= $post['blogID'] ?>">Blacklist this post</button>
	<? } ?>
	<br/><br/>
<? } ?>
</form>