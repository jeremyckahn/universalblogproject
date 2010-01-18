<?php class Ubp_dal extends Model {
    function Ubp_dal()
    {
        parent::Model();
    }
    
    function createBlacklist($userID, $postID, $blacklistLimit) // INTEGER
    {
    	// Creat the blacklist entry
    	$sql = "INSERT INTO blacklists(userID, blogID) VALUES(" . $userID . ", " . $postID . ")";
    	$this->db->query($sql);
    	
    	// Get and update the post's blacklistCount
		$sql = "SELECT * FROM blogs WHERE blogs.blogID = " . $postID;
		$query = $this->db->query($sql);
		$postArray = $query->result_array();
		$postArray = $postArray[0];
		$blacklistCount = $postArray['blacklistCount'];
		$blacklistCount += 1;
		
		// if the post has been blacklisted enough times, completely blacklist it so that nobody can see it.
		if ($blacklistCount >= $blacklistLimit)
		{
			$sql = "UPDATE blogs SET blacklistCount = " . $blacklistCount . ", isBlacklisted = 1 WHERE blogs.blogID = " . $postID;
		}
		else
			$sql = "UPDATE blogs SET blacklistCount = " . $blacklistCount . " WHERE blogs.blogID = " . $postID;
			
		$this->db->query($sql);
		return $this->db->affected_rows() ? $postID : -1;
    }
    
    function createUser($username, $password, $email) // BOOLEAN
    {
    	if ($this->userExists($username))
    		return FALSE;
    	
    	$sql = "INSERT INTO users(username, password, email) VALUES(\"" . $username . "\",\"" . md5($password) . "\",\"" . $email . "\")";
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
    }
    
    function createPost($title, $post, $userID) // BOOLEAN
    {
		$sql = "INSERT INTO blogs(title, post, userID) VALUES(\"". $this->sanitizeString($title) ."\", \"" . $this->sanitizeString($post) . "\"," . $userID .")";
		$this->db->query($sql);
		return $this->db->affected_rows() ? TRUE : FALSE;
    }
    
    function getPosts($userID, $requestSize, $startFrom) // ARRAY
    {
    	if ($userID)
		{
			if ($startFrom == 0)
			{        
		        $sql = '(SELECT bigList.blogID, bigList.title, bigList.post, bigList.userID, bigList.blacklistCount, bigList.isBlacklisted, bigList.cannotBeBlacklisted, bigList.datePosted from blogs AS bigList'
			   . ' LEFT JOIN '
			   . ' (SELECT innerBlogs.blogID FROM blogs AS innerBlogs'
			   . ' RIGHT JOIN blacklists ON innerBlogs.blogID = blacklists.blogID'
			   . ' WHERE blacklists.userID = ' . $userID . ') AS blacklistedBlogs'
			   . ' ON bigList.blogID = blacklistedBlogs.blogID'
			   . ' WHERE blacklistedBlogs.blogID IS NULL'
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT ' . ($requestSize) . ')';
			}
			else
			{
			    $sql = '(SELECT bigList.blogID, bigList.title, bigList.post, bigList.userID, bigList.blacklistCount, bigList.isBlacklisted, bigList.cannotBeBlacklisted, bigList.datePosted from blogs AS bigList'
			   . ' LEFT JOIN '
			   . ' (SELECT innerBlogs.blogID FROM blogs AS innerBlogs'
			   . ' RIGHT JOIN blacklists ON innerBlogs.blogID = blacklists.blogID'
			   . ' WHERE blacklists.userID = ' . $userID . ') AS blacklistedBlogs'
			   . ' ON bigList.blogID = blacklistedBlogs.blogID'
			   . ' WHERE blacklistedBlogs.blogID IS NULL'
			   . ' AND bigList.blogID < ' . $startFrom
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT ' . ($requestSize) . ')';
			}
		}
		else
		{
			if ($startFrom == 0)
				$sql = "SELECT * FROM blogs WHERE isBlacklisted = 0 ORDER BY blogID DESC LIMIT " . ($requestSize);	
			else
				$sql = "SELECT * FROM blogs WHERE (blogID < " . $startFrom . ") AND isBlacklisted = 0 ORDER BY `blogID` DESC LIMIT ". ($requestSize);
		}
		$query = $this->db->query($sql);
		$results = $query->result_array();
		return $results ? $results : FALSE;
    }
    
    function getUserDataArray($username, $password) // ARRAY
    {    	
    	$query = $this->db->query("SELECT DISTINCT * FROM users WHERE username = \"" . $username . "\" AND password = \"" . 
		md5($password) . "\"");
		
		$results = $query->result_array();
		return $results ? $results[0] : FALSE;
    }
    
    function postsRemain($lastPostIDLoaded) // BOOLEAN
    {
    	$query = $this->db->query("SELECT * FROM `blogs` WHERE blogs.blogID < " . $lastPostIDLoaded . " LIMIT 1");
    	$results = $query->result_array();
		return count($results) == 1 ? TRUE : FALSE;
    }
    
    function userExists($username) // BOOLEAN
    {
		$query = $this->db->query("SELECT DISTINCT * FROM users WHERE username = \"" . $username . "\"");
		
		// If the query finds anything, return TRUE
		return $query->result_array() ? TRUE : FALSE;
    }
    
    function sanitizeString($string) // STRING
    {
    	return urlencode($string);
    }
}?>