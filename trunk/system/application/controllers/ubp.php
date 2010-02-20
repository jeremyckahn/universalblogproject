<?php

class UBP extends Controller {
	function UBP()
	{
		parent::Controller();
		
		$this->MIN_USERNAME_LENGTH = 3;
		$this->MAX_USERNAME_LENGTH = 20;
		$this->MIN_PASSWORD_LENGTH = 5;
		$this->MAX_PASSWORD_LENGTH = 25;
		$this->MAX_TITLE_LENGTH = 150;
		$this->MAX_POST_LENGTH = 5000;
		$this->MAX_BLACKLIST_LIMIT = 20;
		$this->MAX_DEFAULT_FEED_PAGE_SIZE = 5;
		
		$this->GET_ARRAY = $this->uri->uri_to_assoc();
		
		$this->load->helper(array('form', 'url', 'ubpstring', 'json', 'validation', 'string'));
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('UBP_DAL', '', TRUE);
		$this->load->model('UBP_DAL_HELPER', '', TRUE);
	}
	
	function index()
	{
		$this->load->view("templateBegin");
		$data = array();
		
		if (isset($this->GET_ARRAY["blogID"]))
		{
			$postArray = $this->UBP_DAL->getPosts(0, 1, $this->GET_ARRAY["blogID"] + 1);
			$data["singlePost"] = $this->UBP_DAL_HELPER->formatBlogs($postArray, 0, "SINGLEVIEW");
		}
		$this->load->view("blogView", $data);
			
		$this->load->view("templateEnd");
	}
	
	/***************************************
	*	User management functions - BEGIN
	****************************************/
	function forgotPassword()
	{
		$this->load->view('templateBegin');
		$this->load->view('forgotPassword');
		$this->load->view('templateEnd');
	}
	
	function login()
	{		
		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		
		if ($this->form_validation->run())
		{
			/* We have verified that both username and password have been filled out,
			 * so now we can first pass off the data to the DAL to see if it is valid.
			 * Declare the $user variable and set it to FALSE.  If the input is valid,
			 * the $user variable will be a filled array (and therefore not FALSE).
			 * It is also here where we must define a new validation rule and
			 * revalidate to make sure that the error messages appear properly.
			 */
			$user = FALSE;
			
			$this->form_validation->set_rules('username', 'username', 'callback_userIsValid');
			
			if ($this->form_validation->run())
				$user = $this->UBP_DAL->getUserDataArray($this->input->post("username", TRUE), $this->input->post("password", TRUE));
			
			// If user is valid, log them in.  If not, show the login form again with error.
			if ($user)
			{	
				$this->UBP_DAL_HELPER->logUserIn($this->input->post("username", TRUE), $this->input->post("password", TRUE));
			}
		}
		
		$this->load->view('templateBegin');
		$this->load->view('loginForm');
		$this->load->view('templateEnd');
	}
	
	function logout()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('loggedIn');
		$this->session->sess_destroy();
		
		$this->load->view('templateBegin');
		$this->load->view('logout');
		$this->load->view('templateEnd');
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
		mail($to,$subject,$message,$headers);
		// THIS NEEDS TO BE TESTED ON A SERVER
	

		$returnVal["requestComplete"] = TRUE;
		$returnVal["requestMessage"] = '<p class=\"blockNarrow blockCenter\">Instructions on how to reset the password for \\"' . $username . '\\" have been sent to \\"' . $email . '.\\"  Please note that this reset request will only be active for 20 minutes, after that you will have to make another request with this form.</p>';
		
		echo(JSONifyAssocArr($returnVal));
	}
	
	function resetPassword()
	{
		$this->form_validation->set_rules('password', 'password', 'requiredmin_length[' . $this->MIN_PASSWORD_LENGTH . ']|required|max_length[' . $this->MAX_PASSWORD_LENGTH . ']');
		$this->form_validation->set_rules('confirmPassword', 'password confirmation', 'required|matches[password]');
		
		$data = array(
			"userID" => 0,
			"requestIsValid" => FALSE,
			"passwordSuccessfullyChanged" => FALSE
		);
		
		if (isset($this->GET_ARRAY["resetID"]))
		{
			$resetID = $this->GET_ARRAY["resetID"];
			$resetEntryArray = $this->UBP_DAL->getPasswordResetEntryFromID($resetID);
			$userID = isset($resetEntryArray["userID"]) ? $resetEntryArray["userID"] : 0;
			$dateOfRequest = isset($resetEntryArray["generatedDate"]) ? $resetEntryArray["generatedDate"] : 0;
			$uniqueIdentifier = isset($resetEntryArray["uniqueIdentifier"]) ? $resetEntryArray["uniqueIdentifier"] : 0;
			$resetRequestWasUsed = isset($resetEntryArray["used"]) ? $resetEntryArray["used"] : 0;
			
			// 60 (seconds) * 20 (minutes) = 1200 (seconds)
			if ((time() - strtotime($dateOfRequest)) < 1200 &&
				$userID != 0 &&
				!$resetRequestWasUsed)
			{
				$data["requestIsValid"] = TRUE;
				
				// If the reset expiration timer has not been exceeded, and if the form is valid, create the new password.
				if ($this->form_validation->run())
				{
					$data["passwordSuccessfullyChanged"] = $this->UBP_DAL->setPasswordByUserID($userID, $this->input->post("password"));
					
					// Password has been reset, so the session ID should be reset as well.
					$this->session->sess_destroy();
					
					// If the password was successfully changed, set the reset entry to "used"
					if ($data["passwordSuccessfullyChanged"])
						$this->UBP_DAL->disablePasswordResetEntryByID($resetID);
				}
			}
		
			$data["userID"] = $userID;
			$data["uniqueIdentifier"] = $uniqueIdentifier;
		}
		
		$this->load->view('templateBegin');
		$this->load->view('passwordResetForm', $data);
		$this->load->view('templateEnd');
	}
	
	function settings()
	{
		$this->load->view('templateBegin');
		$this->load->view('settingsview');
		$this->load->view('templateEnd');
	}
	
	function signup()
	{
		$userWasSuccessfullyCreated = FALSE;
		
		$this->form_validation->set_rules('username', 'username', 'required|min_length[' . $this->MIN_USERNAME_LENGTH . ']|required|max_length[' . $this->MAX_USERNAME_LENGTH . ']');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->MIN_PASSWORD_LENGTH . ']|required|max_length[' . $this->MAX_PASSWORD_LENGTH . ']');
		$this->form_validation->set_rules('passwordConfirm', 'password confirmation', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		
		if ($this->form_validation->run())
		{
			// Set a new validation rule to call the custom callback, and revalidate.
			$this->form_validation->set_rules('username', 'username', 'callback_canCreateUser');
			if($this->form_validation->run())
			{
				$userWasSuccessfullyCreated = $this->UBP_DAL->createUser($this->input->post("username", TRUE), $this->input->post("password", TRUE), $this->input->post("email", TRUE));
			}
			
			if ($userWasSuccessfullyCreated)
				$this->UBP_DAL_HELPER->logUserIn($this->input->post("username", TRUE), $this->input->post("password", TRUE));
		}
		
		$this->load->view("templateBegin");
		$this->load->view("signupForm");
		$this->load->view("templateEnd");
	}
	
	/***************************************
	*	User management functions - END
	****************************************/
	//-------------------------------------------------------------------------------
	/***************************************
	*	Blog management functions - BEGIN
	****************************************/
	
	function blacklistManager()
	{
		$userID = $this->input->post("userID");
		$postID = $this->input->post("postID");
		
		echo $this->UBP_DAL->createBlacklist($userID, $postID, $this->MAX_BLACKLIST_LIMIT);
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
	
	function info()
	{
		$this->load->view("templateBegin");
		$this->load->view("info");
		$this->load->view("templateEnd");	
	}
	
	function post()
	{
		$this->load->view('templateBegin');
		$this->load->view('postForm');
		$this->load->view('templateEnd');
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
		
		$postValidation = validatePostText($this, $title, $post);
		
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
		
		$this->load->view('postSubmissionResult', $data);
	}
	
	/***************************************
	*	Blog management functions - END
	****************************************/
	//-------------------------------------------------------------------------------
	/***************************************
	*	Validation callbacks - BEGIN
	****************************************/
	
	function userIsValid()
	{		
		if ($this->UBP_DAL->getUserDataArray($this->input->post("username", TRUE), $this->input->post("password", TRUE)))
			return true;
		else
		{
			$this->form_validation->set_message("userIsValid", "This is not a valid username/password combination.");
			return false;
		}
	}
	
	function canCreateUser()
	{		
		if (!$this->UBP_DAL->userExists($this->input->post("username", TRUE)))
			return TRUE;
		else
		{
			$this->form_validation->set_message("canCreateUser", "Uh oh, this user already exists.  Please try a different username.");
			return FALSE;
		}
	}
	
	/***************************************
	*	Validation callbacks - END
	****************************************/
	//-------------------------------------------------------------------------------
	/***************************************
	*	Data validators - BEGIN
	****************************************/

	function validatePost()
	{
		$title = $this->input->post("title");
		$post = $this->input->post("post");
		
		echo(JSONifyAssocArr(validatePostText($this, $title, $post)));
	}	
	
	/***************************************
	*	Data validators - END
	****************************************/
}?>