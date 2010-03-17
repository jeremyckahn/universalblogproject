<?php

class UBP_AJAX extends Controller {
	function UBP_AJAX(){
		parent::Controller();
		
		$this->load->helper(array('form', 'url', 'ubpstring', 'json', 'validation', 'string'));
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('UBP_DAL', '', TRUE);
		$this->load->model('UBP_DAL_HELPER', '', TRUE);
	}
	
	function index(){
		//print("Ahoy!");
	}
	
	function blacklistManager()
	{
		$userID = $this->input->post("userID");
		$postID = $this->input->post("postID");
		
		echo $this->UBP_DAL->createBlacklist($userID, $postID, MAX_BLACKLIST_LIMIT);
	}
	
	function blogLoader()
	{	
		$userID = $this->input->post("userID");
		$requestSize = $this->input->post("requestSize");
		$startFrom = $this->input->post("startFrom");
		
		// Get user-specific blogs
		$postArray = $this->UBP_DAL->getPosts($userID, $requestSize, $startFrom);
		
		// Output the blogs.
		echo $this->UBP_DAL_HELPER->formatBlogs($postArray, $userID);
	}
	
	function changeEmail()
	{
		$userID = $this->session->userdata("userID");
		$password = $this->input->post("password");
		$newEmail = $this->input->post("newEmail");
		
		$returnVal = array(
			"emailChanged" => FALSE,
			"messages" => array(),
			"currentEmail" => ""
		);
		
		$this->form_validation->set_rules('newEmail', 'newEmail', 'required|valid_email');
		
		if ($this->form_validation->run())
		{
			if ($this->UBP_DAL->isValidPasswordUserIDCombo($userID, $password))
			{
				if ($this->UBP_DAL->setEmailByUserID($userID, $newEmail))
				{
					$returnVal["emailChanged"] = TRUE;
					$returnVal["messages"][] = "Your email has been changed!";
					
					if ($currentEmail = $this->UBP_DAL->getEmailByUserID($userID))
					{
						$returnVal["currentEmail"] = $currentEmail;
						$this->session->set_userdata('email', $currentEmail);
					}
				}
				else
				{
					$returnVal["messages"][] = SERVER_ERROR_MESSAGE;
				}
			}
			else
			{
				$returnVal["messages"][] = "The password was incorrect.  Please try again.";
			}
		}
		else
		{
			$returnVal["messages"][] = 'The email \"' . $this->input->post("newEmail") . '\" is not valid.  Please re-check and try again.';
		}
		
		echo JSONifyAssocArr($returnVal);
	}
	
	function changeFeedSize()
	{
		$userID = $this->session->userdata("userID");
		$password = $this->input->post("password");
		$feedSize = $this->input->post("feedSize");
		
		$returnVal = array(
			"feedSizeChanged" => FALSE,
			"messages" => array()
		);
		
		if ($feedSize > 0 && $feedSize <= MAX_FEED_PAGE_SIZE)
		{
			if ($this->UBP_DAL->setFeedSizeByUserID($userID, $feedSize))
			{
				$returnVal["feedSizeChanged"] = TRUE;
				$returnVal["messages"][] = "Feed size changed!";
				$this->session->set_userdata('feedPageSize', $feedSize);
				
			}
			else
			{
				$returnVal["messages"][] = SERVER_ERROR_MESSAGE;
			}
		}
		else
		{
			$returnVal["messages"][] = "Invalid input.  Feed size must be between 0 and " . MAX_FEED_PAGE_SIZE . ".";
		}
		
		echo JSONifyAssocArr($returnVal);
	}
	
	function changePassword()
	{
		$userID = $this->session->userdata("userID");
		$username = $this->session->userdata("username");
		$currentPassword = $this->input->post("currentPassword");
		$newPassword = $this->input->post("newPassword");
		
		$returnVal = array(
			"passwordChanged" => FALSE,
			"messages" => array()
		);
		
		if ($this->UBP_DAL->isValidPasswordUserIDCombo($userID, $currentPassword))
		{	
			if ((strlen($newPassword) >= MIN_PASSWORD_LENGTH) && (strlen($newPassword) <= MAX_PASSWORD_LENGTH))
			{
				if ($this->UBP_DAL->setPasswordByUserID($userID, $newPassword))
				{
					$returnVal["passwordChanged"] = TRUE;
					$returnVal["messages"][] = "Password successfully changed!";
				}
				else
				{
					$returnVal["messages"][] = SERVER_ERROR_MESSAGE;
				}
			}else if (strlen($newPassword) < MIN_PASSWORD_LENGTH)
			{
				$returnVal["messages"][] = "Your password must be at least " . MIN_PASSWORD_LENGTH . " characters long.";
			}
			else if (strlen($newPassword) > MAX_PASSWORD_LENGTH)
			{
				$returnVal["messages"][] = "Your password may be no longer than " . MAX_PASSWORD_LENGTH . " characters long.";
			}
			
		}
		else
		{
			$returnVal["messages"][] = 'The \"current password\" supplied is not the correct one for this account.  Please try again.';
		}
		
		echo JSONifyAssocArr($returnVal);
	}
	
	function createPasswordResetrequest()
	{		
		$returnVal = array(
			"requestComplete" => FALSE,
			"responseMessage" => "",
			"errorInfo" => array(
				"invalidArg" => "",
				"message" => ""
			)
		);
		
		$username = $this->input->post("username");
		$email = $this->input->post("email");
		
		if (!$this->UBP_DAL->userExists($username))
		{
			$returnVal["errorInfo"]["invalidArg"] = "username";
			$returnVal["errorInfo"]["message"] = "This is not a valid user.";
			
			exit(JSONifyAssocArr($returnVal));
		}
		
		if (!$this->UBP_DAL->isValidUsernameEmailCombination($username, $email))
		{
			$returnVal["errorInfo"]["invalidArg"] = "email";
			$returnVal["errorInfo"]["message"] = 'The email address specified does not match what is on record for \\"' . $username . '\\"';  // Mind the escape javascript quote escapes!
			
			exit(JSONifyAssocArr($returnVal));
		}
		
		$userID = $this->UBP_DAL->getUserIDFromName($username);
		$uniqueIdentifier = $this->session->userdata("session_id");
		$this->UBP_DAL->createPasswordResetEntry($userID, $uniqueIdentifier);
		
		// Send the email here
		// Email/username is valid, send the email
		$to = $email;
		$subject = "Your password reset link for the Universal Blog Project";
		$message = "Hello, you requested a password reset for universalblogproject.com.  Please visit "
		. base_url() . "index.php/ubp/resetPassword/resetID/" . $uniqueIdentifier
		. " to reset your password.  This link will only remain active for 20 minutes.  Thanks for using the site!";
		$from = "jeremyckahn@gmail.com";
		$headers = "From: $from";
		
		try{
			// Warning is surpressed because it breaks client program logic.
			@mail($to,$subject,$message,$headers);
		}
		catch(Exception $e){
			/*  TODO: There's no mail server set up if this is reached.
			*	Set up some error handling.
			*/
		}

		$returnVal["requestComplete"] = TRUE;
		$returnVal["requestMessage"] = '<p class=\"serverResponseOutput\">Instructions on how to reset the password for \\"' . $username . '\\" have been sent to \\"' . $email . '.\\"  Please note that this reset request will only be active for 20 minutes, after that you will have to make another request with this form.</p>';
		
		echo(JSONifyAssocArr($returnVal));
	}

	function createPost()
	{
		$title = $this->input->post("title");
		$post = $this->input->post("post");
		$data = array(
			"postSubmittedSuccessfully" => FALSE,
			"title" => "",
			"post" => "",
			"errorMessages" => array()
		);
		$postSubmittedSuccessfully = FALSE;
		
		$postValidation = validatePostText($title, $post);
		
		if ($postValidation["isValid"])
		{
			// strip_tags is called on the title and post to sanitize for DB entry.
			$postSubmittedSuccessfully = $this->UBP_DAL->createPost(
				strip_tags($title), 
				strip_tags($post, "<br/>"), 
				$this->session->userdata("userID")
			);
			
			if (!$postSubmittedSuccessfully)
			{
				array_push($data["errorMessages"], "There was an error submitting your post to the database.  Please copy your blog post, refresh the page and try again.  We apologize for the inconvenience.");
			}
		}
		else
		{
			array_push($data["errorMessages"], "The post you submitted was not valid.");
		}
		
		if (!$postSubmittedSuccessfully)
		{
			$data["title"] = $title;
			$data["post"] = $post;
		}
		
		$data["postSubmittedSuccessfully"] = $postSubmittedSuccessfully;
		
		$this->load->view('post_result_view', $data);
	}
	
	function validatePost()
	{
		$title = $this->input->post("title");
		$post = $this->input->post("post");
		
		echo(JSONifyAssocArr(validatePostText($title, $post)));
	}
}	