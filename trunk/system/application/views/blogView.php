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

<script type="text/javascript">
	// Some experimental code I'm not done with.  Never you mind this.

	// Function taken from W3Schools.  http://www.w3schools.com/ajax/ajax_example_suggest.asp
	/*function getXmlHttpRequestObject()
	{
		if (window.XMLHttpRequest)
		{
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  return new XMLHttpRequest();
		}
		if (window.ActiveXObject)
		{
		  // code for IE6, IE5
		  return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}
	
	var blogXHR = getXmlHttpRequestObject();
	function loadMorePosts()
	{	
		if (blogXHR == null)
		{
			alert("Hmmm... either you are using a very old browser, or you have incredibly strict security settings.  Your browser won't let you load posts.");
			return;	
		}
		
		//var url = "scripts/php/feed/feedFormatter.php";
		var url = "<?= $this->config->item('base_url') . "index.php/" . $this->uri->segment(1) . "/blogLoader"; ?>";
		var parameters = "requestSize=" + 5;
		parameters += "&startFrom=" + 1;
		parameters += "&userID=" + 1;
		parameters += "&sid=" + Math.random();
		
		blogXHR.onreadystatechange = loadBlogsStateChanged;
		
		blogXHR.open("POST", url, true);
		blogXHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		blogXHR.send(parameters);
	}
	
	function loadBlogsStateChanged()
	{
		if (blogXHR.readyState == 4)
		{
			alert(blogXHR.responseText);
		}	
	}*/
</script>