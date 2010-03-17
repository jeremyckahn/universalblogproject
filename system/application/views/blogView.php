<? if(isset($singlePost)) { ?>

<div id="mainFeed" class="mainFeed">
	<?= $singlePost ?>
</div>
	
<? } else { ?>

<div id="mainFeed" class="mainFeed">

</div>

<div id="paginationButton" class="paginationButton">
	<span id="btnPageForward" class="paginationButtonMore rollover" onclick="loadMorePosts();">more</span>
</div>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>

<script type="text/javascript">
/* <![CDATA[ */
	var manager = new blogManager();
	var blogContainer = document.getElementById("mainFeed");
	
	function loadMorePosts(requestSize)
	{
		manager.setloadCompleteEventHandler(manager, function(){
			if (!manager.blogsRemain)
			{
				var moreButton = document.getElementById("btnPageForward");
				moreButton.className += " disabled";
				moreButton.onclick = "";
			}
		});
		
		manager.loadMorePosts(
			"<?= base_url() . "index.php/ubp_ajax/blogLoader"; ?>", // serverScriptURL
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
				"<?= base_url() . "index.php/ubp_ajax/blacklistManager"; ?>", // serverScriptURL
				postID, // postID
				<?= $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0" ?> // userID
			);
		}
	}
	
	loadMorePosts();
/* ]]> */
</script>
<? } ?>