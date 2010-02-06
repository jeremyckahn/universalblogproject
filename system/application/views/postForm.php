<? if($this->session->userdata("loggedIn")){ 
	
	$formAttributes = array(
					"id" => "fieldContainer",
					"class" => "formContainer");
?>

<h2 id="pageTitle">Write a post.</h2>

<div id="postFormContainer">

	<div id="fieldContainer" class="formContainer">
	
		<span class ="label">Title:</span>
	
		<span id="titleError" class="errorText"></span>
	
		<input id="txtTitle" class="txtStandard" type="text" name="title" value="" onfocus="resetErrorStyle(this, titleError);"/>
				
		<span class ="label">Post:</span>
	
		<span id="postError" class="errorText"></span>
	
		<textarea id = "txtPost" class="txtPost" name="post" rows="10" cols="30" onfocus="resetErrorStyle(this, postError);"></textarea>
	
		<div style="color: #f00;"><?= validation_errors(); ?></div>
		
	</div>
	
	<div id="previewContainer" class="postContainer">
		
		<h1 id="previewTitle" class="articleHeader"></h1>
		
		<p id="previewPost"></p>
		
	</div>
	
	<div id="editingControls" class="customUIButtonFrame">
			
		<span id="btnPreviewToggle" class="button rollover left" onclick="preview();">preview</span>
		
		<span id="btnSubmit" class="button rollover right" onclick="submitPost();">submit</span>
	
	</div>
	
</div>

<script type="text/javascript" src="<?= base_url() . "js/blogManager.js" ?>"></script>
<script type="text/javascript" src="<?= base_url() . "js/styleManager.js" ?>"></script>

<script type="text/javascript">
/* <![CDATA[ */
	var manager = new blogManager();

	var postFormContainer = document.getElementById("postFormContainer");
	var fieldContainer = document.getElementById("fieldContainer");
	var previewContainer = document.getElementById("previewContainer");
	var btnPreviewToggle = document.getElementById("btnPreviewToggle");
	var btnSubmit = document.getElementById("btnSubmit");
	var txtTitle = document.getElementById("txtTitle");
	var txtPost = document.getElementById("txtPost");
	var previewTitle = document.getElementById("previewTitle");
	var previewPost = document.getElementById("previewPost");
	var editingControls = document.getElementById("editingControls");
	var titleError = document.getElementById("titleError");
	var postError = document.getElementById("postError");
	var pageTitle = document.getElementById("pageTitle");
	
	var titleText, postText;
	var inPreviewMode = false;
	
	previewContainer.style.display = "none";
	btnSubmit.style.display = "none";
	titleError.style.display = "none";
	postError.style.display = "none";
	

	function submitPost()
	{	
		manager.setPostSubmitCompleteEventHandler(manager, function(){
			var serverResponse = manager.adapter.xhr.responseText;
			
			postFormContainer.innerHTML = serverResponse;
		});
		
		manager.createPost(
			"<?= base_url() . "index.php/ubp/createPost"; ?>", // serverScriptURL
			titleText, // title
			postText.replace(/\n/g, "<br/>") // post
		);
	}

	function preview()
	{			
		titleText = txtTitle.value;
		postText = txtPost.value;
		previewTitle.innerHTML = titleText;
		previewPost.innerHTML = postText.replace(/\n/g, "<br/>");
		
		manager.setPostValidationCompleteEventHandler(manager, function(){
			var validationJSON = manager.postValidationJSON;
			var numberOfErrors = JSONLength(validationJSON.errorList);
			var errorArray = JSONToArray(validationJSON.errorList);
			
			if (numberOfErrors == 0)
				togglePreviewMode();
			
			// Would have used a "for...in" but they don't play nicely with multidimensional arrays.  Therefore, a standard for loop is used.
			for (var i = 0; i < numberOfErrors; i++)
			{					
				if (errorArray[i][0] == "title")
				{
					titleError.style.display = "inline";
					titleError.innerHTML = errorArray[i][1];
					addClass(txtTitle, "errorHighlight");
				}
					
				if (errorArray[i][0] == "post")
				{
					postError.style.display = "inline";
					postError.innerHTML = errorArray[i][1];
					addClass(txtPost, "errorHighlight");
				}
			}
		});
		
		manager.validatePost(
			"<?= base_url() . "index.php/ubp/validatePost"; ?>", // serverScriptURL
			titleText, // titleToValidate
			postText //postBodyToValidate
		);
	}
	
	function togglePreviewMode()
	{
		inPreviewMode = !inPreviewMode;
		
		if (inPreviewMode)
		{
			pageTitle.style.display = "none";
			fieldContainer.style.display = "none";
			previewContainer.style.display = "block";
			btnPreviewToggle.innerHTML = "edit";
			btnSubmit.style.display = "block";
		}
		else
		{
			pageTitle.style.display = "block";
			fieldContainer.style.display = "block";
			previewContainer.style.display = "none";
			btnPreviewToggle.innerHTML = "preview";
			btnSubmit.style.display = "none";
		}
	}
/* ]]> */
</script>

<?} else {?>
	<p>You need to be logged in to make a post.</p>
<? } ?>