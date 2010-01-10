<?php class Ubp_dal extends Model {

	var $username = "";
	var $password = "";
	var $email = "";
	var $feedPageSize = "";

    function Ubp_dal()
    {
        parent::Model();
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
    	// Construct the mySQL query.
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
		        $query = '(SELECT bigList.blogID, bigList.title, bigList.post, bigList.userID, bigList.blacklistCount, bigList.isBlacklisted, bigList.cannotBeBlacklisted, bigList.datePosted from blogs AS bigList'
			   . ' LEFT JOIN '
			   . ' (SELECT innerBlogs.blogID FROM blogs AS innerBlogs'
			   . ' RIGHT JOIN blacklists ON innerBlogs.blogID = blacklists.blogID'
			   . ' WHERE blacklists.userID = ' . $userID . ') AS blacklistedBlogs'
			   . ' ON bigList.blogID = blacklistedBlogs.blogID'
			   . ' WHERE blacklistedBlogs.blogID IS NULL'
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT ' . ($requestSize + 1) . ')';
			}
			else
			{
			    $query = '(SELECT bigList.blogID, bigList.title, bigList.post, bigList.userID, bigList.blacklistCount, bigList.isBlacklisted, bigList.cannotBeBlacklisted, bigList.datePosted from blogs AS bigList'
			   . ' LEFT JOIN '
			   . ' (SELECT innerBlogs.blogID FROM blogs AS innerBlogs'
			   . ' RIGHT JOIN blacklists ON innerBlogs.blogID = blacklists.blogID'
			   . ' WHERE blacklists.userID = ' . $userID . ') AS blacklistedBlogs'
			   . ' ON bigList.blogID = blacklistedBlogs.blogID'
			   . ' WHERE blacklistedBlogs.blogID IS NULL'
			   . ' AND bigList.blogID < ' . $startFrom
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT ' . ($requestSize + 1) . ')';
			}
		}
		else
		{
			if ($startFrom == 0)
				$query = "SELECT * FROM blogs WHERE isBlacklisted = 0 ORDER BY blogID DESC LIMIT " . ($requestSize + 1);	
			else
				$query = "SELECT * FROM blogs WHERE (blogID < " . $startFrom . ") AND isBlacklisted = 0 ORDER BY `blogID` DESC LIMIT ". ($requestSize + 1);
		}
		
		$query = $this->db->query($query);
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