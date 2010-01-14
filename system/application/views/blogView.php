	<?= form_open('ubp/index'); ?>

<div id="content" class="content">

</div>

<button type="button" name="loadButton" onclick="loadMorePosts()">Load more posts!</button>
</form>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>

<script type="text/javascript">
	var manager = new blogManager();
	
	function loadMorePosts()
	{
		manager.setLoadEventHandler(manager, function(){
			//alert(manager.blogArray)
		});
		
		manager.loadMorePosts(
			"<?= base_url() . "index.php/" . $this->uri->segment(1) . "/blogLoader"; ?>",
			<?= $this->session->userdata("loggedIn") ? $this->session->userdata("feedPageSize") : "5" ?>,
			0, // THIS IS TEMPORARY CHANGE IT
			<?= $this->session->userdata("loggedIn") ? $this->session->userdata("userID") : "0" ?>,
			document.getElementById("content")
		);
		
	}
</script>