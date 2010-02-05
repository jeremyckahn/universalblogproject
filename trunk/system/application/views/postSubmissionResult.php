<? if ($postSubmittedSuccessfully) { ?>

<p>Thank you for contributing to the project.</p>

<div id="thankYouContainer">
	<p id="postThankYou" class="postThankYou"></p>
	<div class="customUIButtonFrame">
		<div onclick="window.location.replace('<?= base_url() . "index.php/ubp/index"; ?>');" class="button rollover left">back to the blog</div>
	</div>
</div>

<? } else { ?>

<? foreach($errorMessages as $error) { ?>

	<p class="boxedErrorMessage"><?= $error ?></p>

<? } ?>

<div id="previewContainer" class="postContainer">
		
	<h1 id="previewTitle" class="articleHeader"><?= $title ?></h1>
	
	<p id="previewPost"><?= $post ?></p>
	
</div>

<? } ?>