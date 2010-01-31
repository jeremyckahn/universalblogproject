<? if($this->session->userdata("loggedIn")){ 
	
	$formAttributes = array(
					"id" => "fieldContainer",
					"class" => "formContainer");
?>

<!--<?= form_open('ubp/post', $formAttributes); ?>-->
<div id="fieldContainer" class="formContainer">

	<div class ="label">Title:</div>

	<input id="txtTitle" class="txtTitle" type="text" name="title" value="<?= set_value('title'); ?>"/>
			
	<div class ="label">Post:</div>

	<textarea id = "txtPost" class="txtPost" name="post" rows="10" cols="30"><?= set_value('post'); ?></textarea>

	<div style="color: #f00;"><?= validation_errors(); ?></div>
	
	<!--<input type="submit" value="Submit" />-->
	
<!--</form>-->
</div>

<div id="previewContainer" class="postContainer">
	
	<h1 id="previewTitle" class="articleHeader"></h1>
	
	<p id="previewPost"></p>
	
</div>

<div class="customUIButtonFrame">
		
		<span id="btnPreviewToggle" class="button rollover" onclick="preview();">preview</span>
	
	</div>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>

<script type="text/javascript">

	var manager = new blogManager();

	var fieldContainer = document.getElementById("fieldContainer");
	var previewContainer = document.getElementById("previewContainer");
	var btnPreviewToggle = document.getElementById("btnPreviewToggle");
	var txtTitle = document.getElementById("txtTitle");
	var txtPost = document.getElementById("txtPost");
	var previewTitle = document.getElementById("previewTitle");
	var previewPost = document.getElementById("previewPost");
	
	var titleText, postText;
	var inPreviewMode = false;
	
	previewContainer.style.display = "none";

	function preview()
	{		
		titleText = txtTitle.value;
		postText = txtPost.value;
		previewTitle.innerHTML = titleText;
		previewPost.innerHTML = postText;
		
		togglePreviewMode();
		
		if (inPreviewMode)
		{ 
			manager.setPostValidationCompleteEventHandler(manager, function(){
				var validationJSON = manager.postValidationJSON.errors;
				var numberOfErrors = JSONLength(validationJSON);
				//alert(JSONToArray(validationJSON));
			});
			
			var results = manager.validatePost(
				"<?= base_url() . "index.php/ubp/validatePost"; ?>", // serverScriptURL
				titleText, // titleToValidate
				postText //postBodyToValidate
			);
		}
	}
	
	function togglePreviewMode()
	{
		inPreviewMode = !inPreviewMode;
		
		if (inPreviewMode)
		{
			fieldContainer.style.display = "none";
			previewContainer.style.display = "block";
			btnPreviewToggle.innerHTML = "edit";
		}
		else
		{
			fieldContainer.style.display = "block";
			previewContainer.style.display = "none";
			btnPreviewToggle.innerHTML = "preview";
		}
	}
</script>

<?} else {?>
	<p>You need to be logged in to make a post.</p>
<? } ?>