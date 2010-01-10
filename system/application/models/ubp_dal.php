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
    
    function getBlogs($userID, $numberOfPosts) // ARRAY
    {
    	
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