<?php class Ubp_dal_helper extends Model {

    function Ubp_dal_helper()
    {
        parent::Model();
        
        $this->load->model('UBP_DAL', '', TRUE);
    }
    
	function formatBlogs($postArray, $userID) // STRING
	{		
		$returnString = "";
		$blogList = "";
		
		// Construct and echo out the formatted blog data.  If user is logged in, create a blacklist button
		if ($postArray)
		{
			foreach($postArray as $post)
			{
				$returnString = $returnString . "<div id=\"postID_" . $post['blogID'] . "\" class=\"postContainer\">\n";
				$returnString = $returnString . "<h1 class=\"articleHeader\"><a href=\"" . base_url() . "index.php/ubp/index/blogID/" . $post['blogID'] . "\">" . htmlentities(urldecode($post['title'])) . "</a></h1>\n";
				$returnString = $returnString . "<p>" . htmlentities(urldecode($post['post'])) . "</p>\n";
				
				if ($userID != "0")
					$returnString = $returnString . "<button type=\"submit\" name=\"blacklistButton\" value=\"" . $post['blogID'] . "\" onclick=\"blacklist(" . $post['blogID'] . ")\">Blacklist this post</button>\n";
				
				$returnString = $returnString . "</div>\n";
					
				$blogList = $blogList . $post['blogID'] . "_";			
			}
		}
		
		$returnString = $returnString . "<input id=\"blogList\" type=\"hidden\" value=\"" . $blogList . "\"></input>";
		
		if ($blogList)
		{
			$lastPostIDLoaded = explode("_", $blogList);
			$lastPostIDLoaded = $lastPostIDLoaded[count($lastPostIDLoaded) - 2];
			
			if ($this->UBP_DAL->postsRemain($lastPostIDLoaded))
				$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"TRUE\"></input>";
			else
				$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"FALSE\"></input>";
		}
		else
			$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"FALSE\"></input>";
		
		return $returnString;
	}
    
    function logUserIn($username, $password) // BOOLEAN
    {
    	$user = $this->UBP_DAL->getUserDataArray($username, $password);
    	
    	if ($user)
		{
			$sessionData = array(
				'username' 		=> $user["username"],
				'userID'   		=> $user["userID"],
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