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
		
		// Gets any special directives from the function call (passed by the invisible 3rd parameter... Ooooh!)
		$args = func_get_args();
		$options = isset($args[2]) ? $args[2] : null;
		
		// Construct and output the formatted blog data.  If user is logged in, create a blacklist button
		if ($postArray)
		{
			foreach($postArray as $post)
			{
				$returnString = $returnString . "<div id=\"postID_" . $post['blogID'] . "\" class=\"postContainer\">\n";
				
				// If a special directive is "SINGLEVIEW," make the post title a link to the single-post view
				if (stripos($options, "SINGLEVIEW") === FALSE)
					$returnString = $returnString . "<h1 class=\"articleHeader\"><a href=\"" . base_url() . "index.php/ubp/index/blogID/" . $post['blogID'] . "\">" . htmlentities(urldecode($post['title'])) . "</a></h1>\n";
				else
				{
					$returnString = $returnString . "<h1 class=\"articleHeader\">" . htmlentities(urldecode($post['title'])) . "</h1>\n";
					
					$postDate = explode(" ", $post['datePosted']);
					$dateSegments = explode("-", $postDate[0]);
					$returnString = $returnString . "<h3>" . $dateSegments[1] . "-" . $dateSegments[2] . "-" . $dateSegments[0] . "</h3>";
				}
				
				$returnString = $returnString . "<p>" . htmlentities(urldecode($post['post'])) . "</p>\n";
				
				if ($userID != "0")
				{
					$returnString = $returnString . "<button type=\"submit\" name=\"blacklistButton\" value=\"" . $post['blogID'] . "\" onclick=\"blacklist(" . $post['blogID'] . ")\">Blacklist this post</button>\n";
				}
				
				$returnString = $returnString . "</div>\n";
				
				$blogList = $blogList . $post['blogID'] . "_";			
			}
		}
		
		$returnString = $returnString . "<input id=\"blogList\" type=\"hidden\" value=\"" . $blogList . "\"/>\n";
		
		if ($blogList)
		{
			$lastPostIDLoaded = explode("_", $blogList);
			$lastPostIDLoaded = $lastPostIDLoaded[count($lastPostIDLoaded) - 2];
			
			if ($this->UBP_DAL->postsRemain($lastPostIDLoaded))
				$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"TRUE\"/>\n";
			else
				$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"FALSE\"/>\n";
		}
		else
			$returnString = $returnString . "<input id=\"blogsRemain\" type=\"hidden\" value=\"FALSE\"\n>";
		
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