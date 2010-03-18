<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<feed xmlns="http://www.w3.org/2005/Atom">
	<id>http://www.universalblogproject.com/</id>
	<title>The Universal Blog Project</title>
	<updated><?php echo formatDate($blogData[0]["datePosted"]); ?></updated>
	<?php foreach ($blogData as $blog): ?>
	<entry>
		<title><?php echo urldecode($blog["title"]); ?></title>
		<published><?php echo formatDate($blog["datePosted"]); ?></published>
		<updated><?php echo formatDate($blog["datePosted"]); ?></updated>
		<author><name></name></author>
		<content type="html"><?php echo urldecode($blog["post"]); ?></content>
	</entry>
	<?php endforeach ?>	
</feed>
	
<?php

// Formats the date string and adds 5 hours so it displays correctly
function formatDate($dateTime)
{
	$dateTime = explode(" ", $dateTime);
	$date = $dateTime[0];
	$time = $dateTime[1];
	
	$time = explode(":", $time);
	
	return $date . "T" . $time[0] . ":" . $time[1] . ":" . $time[2] . "-05:00";
}

?>