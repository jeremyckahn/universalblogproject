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

<button type="button" name="loadButton" onclick="loadMorePosts()">Load more posts!</button>
</form>

<script type="text/javascript">
	// Some experimental code I'm not done with.  Never you mind this.
	
	var blogXHR = getXmlHttpRequestObject();
	function loadMorePosts()
	{	
		if (blogXHR == null)
		{
			alert("Hmmm... either you are using a very old browser, or you have incredibly strict security settings.  Your browser won't let you load posts.");
			return;	
		}
		
		var url = "<?= base_url() . "index.php/" . $this->uri->segment(1) . "/blogLoader"; ?>";
		var parameters = "requestSize=" + <?= $this->session->userdata("loggedIn") ? $this->session->userdata("feedPageSize") : "\"5\"" ?>;
		parameters += "&startFrom=" + 1;
		parameters += "&userID=" + <?= $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "\"-1\"" ?>;
		parameters += "&sid=" + Math.random();
		
		blogXHR.onreadystatechange = function(){
			if (blogXHR.readyState == 4)
			{
				alert(blogXHR.responseText);
			}
		};
		
		blogXHR.open("POST", url, true);
		blogXHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		blogXHR.send(parameters);
	}
</script>