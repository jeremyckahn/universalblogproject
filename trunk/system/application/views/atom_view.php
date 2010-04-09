<?php /* Redundant tag echoing done to prevent silly server short-tag interperetations. */ ?>
<?php echo "<?"; ?>xml version="1.0" encoding="UTF-8" standalone="yes"<?php echo "?>"; ?>

<feed xmlns="http://www.w3.org/2005/Atom">
	<id>http://www.universalblogproject.com/</id>
	<title>The Universal Blog Project</title>
	<updated><?php echo formatDate($blogData[0]["datePosted"]); ?></updated>
	<?php foreach ($blogData as $blog): ?>
	<entry>
		<title><?php echo urldecode($blog["title"]); ?></title>
		<link href="<?php echo(base_url() . "index.php/ubp/index/blogID/" . $blog["blogID"]) ?>"/>
		<published><?php echo formatDate($blog["datePosted"]); ?></published>
		<updated><?php echo formatDate($blog["datePosted"]); ?></updated>
		<author><name></name></author>
		<content type="html"><?php echo urldecode($blog["post"]); ?></content>
	</entry>
	<?php endforeach ?>	
</feed>
	
<?php

// Formats the date string and adds 5 hours so it displays correctly
// This can probably be done better.  Look at single_blog_view.php to see how it handles dates.
function formatDate($dateTime)
{
	$dateTime = explode(" ", $dateTime);
	$date = $dateTime[0];
	$time = $dateTime[1];
	
	$time = explode(":", $time);
	
	return $date . "T" . $time[0] . ":" . $time[1] . ":" . $time[2] . "-05:00";
}

?>