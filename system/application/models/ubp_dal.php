<?php class Ubp_dal extends Model {
    function Ubp_dal()
    {
        parent::Model();
		
		// Define DB constants
		
		/* 	COMPRESSION_AMOUNT refers to the potential space taken up by urlencode.
		*	As it concerns max field lengths and raw text storage, all text is urlencoded.
		*	This means it potentially takes up 3 times the characters as actually inputted from the user.
		*	Setting this to 3 and dividing raw text max field lengths by COMPRESSION_AMOUNT
		*	ensures there will not be an overflow.
		*/
		define("COMPRESSION_AMOUNT", 3);
		define("MAX_POST_LENGTH", (int)($this->getMaxFieldSize("blogs", "post") / COMPRESSION_AMOUNT));
		define("MAX_USERNAME_LENGTH", (int)($this->getMaxFieldSize("users", "username") / COMPRESSION_AMOUNT));
		define("MAX_PASSWORD_LENGTH", (int)($this->getMaxFieldSize("users", "password") / COMPRESSION_AMOUNT));
		define("MAX_TITLE_LENGTH", (int)($this->getMaxFieldSize("blogs", "title") / COMPRESSION_AMOUNT));
    }
        
    /***************************************
	*	DB create functions - BEGIN
	****************************************/
    
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
    
    function createPasswordResetEntry($userID, $uniqueIdentifier)
    {
    	$sql = "INSERT INTO passwordResets(userID, uniqueIdentifier, used) VALUES(". $userID .", \"" . $uniqueIdentifier ."\", 0)";
    	$this->db->query($sql);
    	
    	return $this->db->affected_rows() ? TRUE : FALSE;
    }
    
    function createPost($title, $post, $userID) // BOOLEAN
    {
		$sql = "INSERT INTO blogs(title, post, userID) VALUES(\"". $this->sanitizeString($title) ."\", \"" . $this->sanitizeString($post) . "\"," . $userID .")";
		$this->db->query($sql);
		return $this->db->affected_rows() ? TRUE : FALSE;
    }
    
    function createUser($username, $password, $email) // BOOLEAN
    {
	    	$username = $this->sanitizeString($username);
	    	$password = $this->sanitizeString($password);
	    	$email = $this->sanitizeString($email);
	    	
	    	if ($this->userExists($username))
	    		return FALSE;
	    	
	    	$sql = "INSERT INTO users(username, password, email) VALUES(\"" . $username . "\",\"" . md5($password) . "\",\"" . $email . "\")";
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
    }
    
    /***************************************
	*	DB create functions - END
	****************************************/
	
	//------------------------------------------------------------
	
	/***************************************
	*	DB get/retrieval functions - BEGIN
	****************************************/
	
	function getAtomData($feedSize)
	{
		$sql = "SELECT * FROM blogs WHERE isBlacklisted = 0 ORDER BY `blogID` DESC LIMIT " . $feedSize;
		$query = $this->db->query($sql);
		$results = $query->result_array();
		return $results ? $results : FALSE;
	}
	
	function getEmailByUserID($userID) // STRING
	{
		$sql = "SELECT email FROM users WHERE userID = \"" . $userID . "\"";
    	$query = $this->db->query($sql);
		$results = $query->result_array();
		
		return $results ? $results[0]["email"] : FALSE;
	}
    
	// An example of usage:  $this->UBP_DAL->getMaxFieldSize("blogs", "post")
	function getMaxFieldSize($table, $column) // INT
	{
		$sql = "DESCRIBE " . $table;
		$query = $this->db->query($sql);
		
		$columns = $query->result_array();
		
		foreach ($columns as $item)
		{
			if (strtolower($item["Field"]) == strtolower($column))
			{
				$resultString = explode("(", $item["Type"]);
				$resultString = explode(")", $resultString[1]);
				$resultString = $resultString[0];
				
				return (int)$resultString;
			}
		}
		
		return FALSE;
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
		$returnVal;
    	$username = $this->sanitizeString($username);
    	$password = $this->sanitizeString($password);
    	
    	$query = $this->db->query("SELECT DISTINCT * FROM users WHERE username = \"" . $username . "\" AND password = \"" . 
		md5($password) . "\"");
		
		$results = $query->result_array();
		
		if ($results)
		{
			// Unencodes user data for proper output
			$results[0]["username"] = $this->unsanitizeString($results[0]["username"]);
			$results[0]["password"] = $this->unsanitizeString($results[0]["password"]);
			$results[0]["email"] = $this->unsanitizeString($results[0]["email"]);
			
			$returnVal = $results[0];
		}
		else
		{
			$returnVal = FALSE;
		}
		
		return $returnVal;
    }
    
    function getUserIDFromName($username) // INTEGER
    {
    	$sql = "SELECT userID FROM users WHERE username = \"" . $username . "\"";
    	$query = $this->db->query($sql);
		$results = $query->result_array();
		
		return $results ? $results[0]["userID"] : FALSE;
    }
    
    function getPasswordResetEntryFromID($resetID) // ARRAY
    {
    	$query = $this->db->query("SELECT * FROM passwordResets WHERE uniqueIdentifier = \"" . $resetID . "\"");
    	
    	$results = $query->result_array();
    	return $results ? $results[0] : array();
    }
    
    /***************************************
	*	DB get/retrieval functions - END
	****************************************/
	
	//------------------------------------------------------------
	
	/***************************************
	*	DB set/update - BEGIN
	****************************************/
	
	function setEmailByUserID($userID, $newEmail) // BOOLEAN
	{	
		$sql = "SELECT * FROM users WHERE users.userID = " . $userID . " AND users.email = \"" . $newEmail . "\"";
		$query = $this->db->query($sql);
		
		// If the new email is the same as the old email, just return true so that the program logic can continue as desired.
		if ($query->result_array())
			return TRUE;
		
		$sql = "UPDATE users SET users.email = \"" . $newEmail . "\" WHERE users.userID = " . $userID;
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	
	function setFeedSizeByUserID($userID, $feedSize) // BOOLEAN
	{	
		$sql = "SELECT * FROM users WHERE users.userID = " . $userID . " AND users.feedPageSize = \"" . $feedSize . "\"";
		$query = $this->db->query($sql);
		
		// If the new feedPageSize is the same as the old feedPageSize, just return true so that the program logic can continue as desired.
		if ($query->result_array())
			return TRUE;
		
		$sql = "UPDATE users SET users.feedPageSize = \"" . $feedSize . "\" WHERE users.userID = " . $userID;
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	
	function setPasswordByUserID($userID, $newPassword) // BOOLEAN
	{
		$sql = "SELECT * FROM users WHERE users.userID = " . $userID . " AND users.password = \"" . md5($newPassword) . "\"";
		$query = $this->db->query($sql);
		
		// If the new password is the same as the old password, just return true so that the reset DB entry is removed.
		if ($query->result_array())
			return TRUE;
		
		$sql = "UPDATE users SET users.password = \"" . md5($newPassword) . "\" WHERE users.userID = " . $userID;
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	
	function disablePasswordResetEntryByID($passwordResetID) // BOOLEAN
	{
		$sql = "UPDATE passwordResets SET passwordResets.used = 1 WHERE passwordResets.uniqueIdentifier = \"" . $passwordResetID . "\"";
		$this->db->query($sql);
		
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	
	/***************************************
	*	DB set/update - END
	****************************************/
	
	//------------------------------------------------------------
	
	/***************************************
	*	DB validation/confirmation functions - BEGIN
	****************************************/
    
    function isValidUsernameEmailCombination($username, $email)
    {
    	$username = $this->sanitizeString($username);
    	$email = $this->sanitizeString($email);
    	
    	$query = $this->db->query("SELECT DISTINCT * FROM users WHERE LCASE(username) = \"" . strtolower($username) . "\" AND LCASE(email) = \"" . strtolower($email) . "\"");
		
		// If the query finds anything, return TRUE
		return $query->result_array() ? TRUE : FALSE;
    }
    
	function isValidPasswordUserIDCombo($userID, $password)
	{
    	$password = $this->sanitizeString($password);
		
		$query = $this->db->query("SELECT DISTINCT * FROM users WHERE userID = " . $userID . " AND password = \"" . md5($password) . "\"");
		
		// If the query finds anything, return TRUE
		return $query->result_array() ? TRUE : FALSE;
	}
	
    function postsRemain($lastPostIDLoaded, $userID) // BOOLEAN
    {
		if ($userID)
		{
			if ($lastPostIDLoaded == 0)
			{   
				// These queries are very similar to those found in getPosts().  It's not Déjà vu.	
		        $sql = '(SELECT bigList.blogID, bigList.title, bigList.post, bigList.userID, bigList.blacklistCount, bigList.isBlacklisted, bigList.cannotBeBlacklisted, bigList.datePosted from blogs AS bigList'
			   . ' LEFT JOIN '
			   . ' (SELECT innerBlogs.blogID FROM blogs AS innerBlogs'
			   . ' RIGHT JOIN blacklists ON innerBlogs.blogID = blacklists.blogID'
			   . ' WHERE blacklists.userID = ' . $userID . ') AS blacklistedBlogs'
			   . ' ON bigList.blogID = blacklistedBlogs.blogID'
			   . ' WHERE blacklistedBlogs.blogID IS NULL'
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT 1)';
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
			   . ' AND bigList.blogID < ' . $lastPostIDLoaded
			   . ' AND bigList.isBlacklisted = 0'
			   . ' ORDER BY bigList.blogID DESC LIMIT 1)';
			}
		}
		else
		{
			$sql = "SELECT * FROM `blogs` WHERE blogs.blogID < " . $lastPostIDLoaded . " LIMIT 1";
		}
		
    	$query = $this->db->query($sql);
    	$results = $query->result_array();
		return count($results) == 1 ? TRUE : FALSE;
    }
    
    function userExists($username) // BOOLEAN
    {
    	$username = $this->sanitizeString($username);
    	
		$query = $this->db->query("SELECT DISTINCT * FROM users WHERE LCASE(username) = \"" . strtolower($username) . "\"");
		
		// If the query finds anything, return TRUE
		return $query->result_array() ? TRUE : FALSE;
    }
    
    /***************************************
	*	DB validation/confirmation functions - END
	****************************************/
    
    function sanitizeString($string) // STRING
    {
    	return urlencode($string);
    }
    
    function unsanitizeString($string) // STRING
    {
    	return urldecode($string);
    }
	
	// TEMPORARY!  DELETE ME!!!
	function getOldData($table){
		$sql = "USE ubp_old";
		$this->db->query($sql);
		$sql = "SELECT * FROM " . $table;
		$result = $this->db->query($sql);
		
		return $result->result_array();
	}
	
	function convertUserTable($userData, $newDB = "ubp"){
		$this->db->query("USE " . $newDB);
		
		// Turn off auto incrementing
		$this->db->query("ALTER TABLE  `users` CHANGE  `userID`  `userID` INT( 11 ) NOT NULL");
		
		// Turn off timestamping
		$this->db->query("ALTER TABLE  `users` CHANGE  `timestamp`  `timestamp` TIMESTAMP NULL");
		
		foreach ($userData as $user){
			$sql = "INSERT INTO users(userID, username, password, email, timestamp, feedPageSize) 
			VALUES(" 
			. $user["userID"] . ", '" 
			. $this->sanitizeString($user["username"]) . "', '"
			. $user["password"] . "', '" 
			. $this->sanitizeString($user["email"]) . "', '" 
			. $user["timestamp"] . "', " 
			. $user["feedPageSize"] . ")";
			
			$this->db->query($sql);
		}
		
		// Turn on auto incrementing
		$this->db->query("ALTER TABLE  `users` CHANGE  `userID`  `userID` INT( 11 ) NOT NULL AUTO_INCREMENT");
		
		// Turn on timestamping
		$this->db->query("ALTER TABLE  `users` CHANGE  `timestamp`  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		
		// We're good!
	}
	
	function convertBlogTable($blogData, $newDB = "ubp", $helper){
		$this->db->query("USE " . $newDB);
		
		// Turn off auto incrementing
		$this->db->query("ALTER TABLE  `blogs` CHANGE  `blogID`  `blogID` INT( 11 ) NOT NULL");
		
		// Turn off timestamping
		$this->db->query("ALTER TABLE  `blogs` CHANGE  `datePosted`  `datePosted` TIMESTAMP NULL");
		
		foreach ($blogData as $blog){
			$sql = "INSERT INTO blogs(blogID, title, post, userID, blacklistCount, isBlacklisted, cannotBeBlacklisted, datePosted)
			VALUES("
			. $blog["blogID"] . ", '"
			. $this->sanitizeString($helper->convertFromOldBlogFormatToPlainText($blog["title"])) . "', '"
			. $this->sanitizeString($helper->convertFromOldBlogFormatToPlainText($blog["post"])) . "', "
			. $blog["userID"] . ", "
			. $blog["blacklistCount"] . ", "
			. $blog["isBlacklisted"] . ", "
			. $blog["cannotBeBlacklisted"] . ", '"
			. $blog["datePosted"] . "')";
			
			$this->db->query($sql);
		}
		
		
		// Turn off auto incrementing
		$this->db->query("ALTER TABLE  `blogs` CHANGE  `blogID`  `blogID` INT( 11 ) NOT NULL AUTO_INCREMENT");
		
		// Turn off timestamping
		$this->db->query("ALTER TABLE  `blogs` CHANGE  `datePosted`  `datePosted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
	}
}?>