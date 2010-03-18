<?php if ($postSubmittedSuccessfully) { ?>

<p>Thank you for contributing to the project.</p>

<div id="thankYouContainer">
	<p id="postThankYou" class="postThankYou"></p>
	<div class="customUIButtonFrame">
		<div onclick="window.location.replace('<?php echo base_url() . "index.php/ubp/index"; ?>');" class="button rollover left">back to the blog</div>
	</div>
</div>

<?php } else { ?>

<?php foreach($errorMessages as $error) { ?>

	<p class="boxedErrorMessage"><?php echo $error; ?></p>

<?php } ?>

<div id="previewContainer" class="postContainer">
		
	<h1 id="previewTitle" class="articleHeader"><?php echo $title; ?></h1>
	
	<p id="previewPost"><?php echo $post; ?></p>
	
</div>

<?php } ?>