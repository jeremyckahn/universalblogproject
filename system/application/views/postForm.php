<? if($this->session->userdata("loggedIn")){ 
	
	$formAttributes = array(
					"id" => "postForm",
					"class" => "formContainer");
?>

<?= form_open('ubp/post', $formAttributes); ?>
	<div class ="label">Title:</div>

	<input id="txtTitle" class="txtTitle" type="text" name="title" value="<?= set_value('title'); ?>"/>
			
	<div class ="label">Post:</div>

	<textarea id = "postInput" class="txtPost" name="post" rows="10" cols="30"><?= set_value('post'); ?></textarea>

	<div style="color: #f00;"><?= validation_errors(); ?></div>
	
	<input type="submit" value="Submit" />
	
	<!--<div class="customUIButtonFrame">
		
		<span class="button rollover" onclick="preview();">preview</span>
	
	</div>-->
	
</form>

<script type=text/javascript">
	function preview()
	{
		
	}
</script>

<?} else {?>
	<p>You need to be logged in to make a post.</p>
<? } ?>