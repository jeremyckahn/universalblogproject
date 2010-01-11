	<?= form_open('ubp/index'); ?>

<? if (isset($postArray)) { ?>
	<? foreach ($postArray as $post){ ?>
    <div id="postID_<?= $post['blogID'] ?>" class="postContainer">
		<?= htmlentities(urldecode($post['title'])) . "\n" ?>
		<br/>
		<?= htmlentities(urldecode($post['post'])) . "\n" ?>
		<? if($this->session->userdata("loggedIn")){ ?>
<button type="submit" name="blacklistButton" value="<?= $post['blogID'] ?>">Blacklist this post</button>
	<? } ?>
    </div>
	<? } ?>
<? } ?>
</form>