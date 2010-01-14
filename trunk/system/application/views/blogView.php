<div id="content" class="content">

</div>

<button type="button" name="loadButton" onclick="loadMorePosts()">Load more posts!</button>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>

<script type="text/javascript">
/* <![CDATA[ */
	var manager = new blogManager();
	var blogContainer = document.getElementById("content");
	
	function loadMorePosts(requestSize)
	{
		manager.setloadCompleteEventHandler(manager, function(){
			
		});
		
		manager.loadMorePosts(
			"<?= base_url() . "index.php/" . $this->uri->segment(1) . "/blogLoader"; ?>", // serverScriptURL
			(requestSize ? requestSize : <?= $this->session->userdata("loggedIn") ? $this->session->userdata("feedPageSize") : "5" ?>), // requestSize
			manager.getLastPost(manager), // startFrom
			<?= $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0" ?>, // userID
			blogContainer // blogContainer
		);
	}
	
	function blacklist(postID)
	{
		if (confirm("Blacklist this post?"))
		{
			manager.setBlacklistCompleteEventHandler(manager, function(){
				divToCut = document.getElementById("postID_" + postID);
				blogContainer.removeChild(divToCut);
				loadMorePosts(1);
			});
	
			manager.blacklist(
				manager, // managerObj
				"<?= base_url() . "index.php/" . $this->uri->segment(1) . "/blacklistManager"; ?>", // serverScriptURL
				postID, // postID
				<?= $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0" ?> // userID
			);
		}
	}
	
	loadMorePosts();
/* ]]> */
</script>