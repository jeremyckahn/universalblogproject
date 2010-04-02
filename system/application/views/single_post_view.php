<div id="postID_<?php echo $post['blogID']; ?>" class="postContainer">
	
	<h1 class="articleHeader"><?php echo htmlentities(urldecode($post['title'])); ?></h1>
	
	<h3 class="articleDate bracketize"><?php echo date("g:i:s A - l F j, Y", strtotime($post["datePosted"])); ?></h3>
	
	<p><?php echo preserveBRs(htmlentities(urldecode($post['post']))); ?></p>
	
</div>