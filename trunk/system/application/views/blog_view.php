<?php if(isset($singlePost)) { ?>

<div id="mainFeed" class="mainFeed">
	<?php echo $singlePost; ?>
</div>
	
<?php } else { ?>

<div id="mainFeed" class="mainFeed">

</div>

<div id="paginationButton" class="paginationButton">
	<span id="btnPageForward" class="paginationButtonMore rollover" onclick="loadMorePosts();">more</span>
</div>

<div id="blogTemplate" class="hidden">

	<div id="postID_0postID0" class="postContainer">
	
		<h1 class="articleHeader">
		
			<a href="<?php echo base_url(); ?>index.php/ubp/index/blogID/0postID0">0postTitle0</a>
			
		</h1>
	
		<p>0postBody0</p>
	
		<?php if ($this->session->userdata("loggedIn")):?>
		<div class="controlPanel">
		
			<span class="blacklistButton bracketize rollover" value="0postID0" onclick="blacklist(0postID0)">blacklist this post</span>
			
		</div>
		<?php endif; ?>
	
	</div>
	
</div>

<script type="text/javascript" src="<?php echo base_url() . "js/blogManager.js"; ?>"></script>

<script type="text/javascript">
/* <![CDATA[ */
	var manager = new blogManager();
	var blogContainer = document.getElementById("mainFeed");
	var blogTemplate = document.getElementById("blogTemplate");
	
	manager.setPostTemplate(manager, blogTemplate);
	
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
			"<?php echo base_url() . "index.php/ubp_ajax/blogLoader"; ?>", // serverScriptURL
			(requestSize ? requestSize : <?php echo $this->session->userdata("loggedIn") ? $this->session->userdata("feedPageSize") : "5"; ?>), // requestSize
			manager.getLastPost(manager), // startFrom
			<?php echo $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0"; ?>, // userID
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
				
				if (manager.blogsRemain)
					loadMorePosts(1);
			});
	
			manager.blacklist(
				manager, // managerObj
				"<?php echo base_url() . "index.php/ubp_ajax/blacklistManager"; ?>", // serverScriptURL
				postID, // postID
				<?php echo $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0"; ?> // userID
			);
		}
	}
	
	loadMorePosts();
/* ]]> */
</script>
<?php } ?>