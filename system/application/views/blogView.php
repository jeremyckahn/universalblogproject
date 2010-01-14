	<?= form_open('ubp/index'); ?>

<!--<? if (isset($postArray)) { ?>
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
<? } ?>-->

<div id="content" class="content">

</div>

<button type="button" name="loadButton" onclick="loadMorePosts()">Load more posts!</button>
</form>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>

<script type="text/javascript">
	// Some experimental code I'm not done with.  Never you mind this.
	
	var manager = new blogManager(document.getElementById("content"), getXmlHttpRequestObject());
	
	function loadMorePosts()
	{
		manager.loadMorePosts(
			"<?= base_url() . "index.php/" . $this->uri->segment(1) . "/blogLoader"; ?>",
			<?= $this->session->userdata("loggedIn") ? $this->session->userdata("feedPageSize") : "5" ?>,
			0, // THIS IS TEMPORARY CHANGE IT
			<?= ($this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0") . "\n" ?>
		);
	}
</script>