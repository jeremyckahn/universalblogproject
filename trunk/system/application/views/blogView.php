<p>This is the blog view.</p>

<? foreach ($postArray as $post){ ?>
	<?= htmlentities(urldecode($post['title'])) ?>
	<br/>
	<?= htmlentities(urldecode($post['post'])) ?>
	<br/><br/>
<? } ?>