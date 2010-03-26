<?php class Ubp_dal_helper extends Model {

    function Ubp_dal_helper()
    {
        parent::Model();
        
        $this->load->model('UBP_DAL', '', TRUE);
    }
    
	function formatBlogs($postArray, $userID) // ARRAY
	{		
		$returnValue = array();
		$returnValue["postData"] = array();
		$lastPostIDLoaded;
		
		foreach ($postArray as $post){
			$postData = array();
		
			$postData["postID"] = $post['blogID'];
			$lastPostIDLoaded = $post['blogID'];
			
			$postData["postTitle"] = htmlentities(urldecode($post['title']));
			$postData["postBody"] = htmlentities(urldecode($post['post']));
			
			$datePosted = explode(" ", $post['datePosted']);
			$datePosted = explode("-", $datePosted[0]);
			$postData["datePosted"] = $datePosted[1];
			
			$returnValue["postData"][] = $postData;
		}
		
		$returnValue["postsRemain"] = $this->UBP_DAL->postsRemain($lastPostIDLoaded, $userID);
		
		return JSONifyAssocArr($returnValue);
	}
    
    function logUserIn($username, $password) // BOOLEAN
    {
    	$user = $this->UBP_DAL->getUserDataArray($username, $password);
    	
    	if ($user)
		{
			$sessionData = array(
				'username' 		=> $user["username"],
				'userID'   		=> $user["userID"],
				'email'  		=> $user["email"],
				'feedPageSize'  => $user["feedPageSize"],
				'loggedIn' 		=> TRUE
			);

			$this->session->set_userdata($sessionData);
			return TRUE;
		}
		else
			return FALSE;
   }
}?>