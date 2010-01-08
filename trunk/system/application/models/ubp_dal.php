<?php class Ubp_dal extends Model {

	var $username = "";
	var $password = "";
	var $email = "";
	var $feedPageSize = "";

    function Ubp_dal()
    {
        parent::Model();
    }
    
    function getUserDataArray($username, $password)
    {    	
    	$query = $this->db->query("SELECT DISTINCT * FROM users WHERE username = \"" . 
		$username . "\" AND password = \"" . 
		md5($password) . "\"");
		
		$results = $query->result_array();
			
		return $results ? $results[0] : FALSE;
    }
    
    function userExists($username)
    {
		$query = $this->db->query("SELECT DISTINCT * FROM users WHERE username = \"" . $username . "\"");
		
		// If the query finds anything, return TRUE
		return $query->result_array() ? TRUE : FALSE;
    }
    
    function createUser($username, $password, $email)
    {
    	if ($this->userExists($username))
    		return FALSE;
    	
    	$sql = "INSERT INTO users(username, password, email) VALUES('" 
		. $username . "','" . md5($password) . "','" . $email . "')";
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
    }

}?>