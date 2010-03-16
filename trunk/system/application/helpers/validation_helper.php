<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('validatePostText'))
{
	function validatePostText($title, $post) // STRING
	{
		$validationJSON = array(
			"isValid" => false,
			"errorList" => array()
		);
			
		if (strlen($title) >= MAX_TITLE_LENGTH)
			array_push($validationJSON["errorList"], array("title", "The title is too long."));
			
		if (strlen($title) == 0)
			array_push($validationJSON["errorList"], array("title", "There is no title."));
			
		if (strlen($post) >= MAX_POST_LENGTH)
			array_push($validationJSON["errorList"], array("post", "The post is too long."));
			
		if (strlen($post) == 0)
			array_push($validationJSON["errorList"], array("post", "There is no post body text."));
			
		if (count($validationJSON["errorList"]) == 0)
			$validationJSON["isValid"] = true;
			
		return $validationJSON;
	}	
}  

?>