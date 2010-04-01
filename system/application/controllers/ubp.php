<?php

class UBP extends Controller {
	function UBP()
	{
		parent::Controller();
				
		$this->GET_ARRAY = $this->uri->uri_to_assoc();
		
		$this->load->helper(array('form', 'url', 'ubpstring', 'json', 'validation', 'string', 'file'));
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('UBP_DAL', '', TRUE);
		$this->load->model('UBP_DAL_HELPER', '', TRUE);
	}
	
	function index()
	{	
		$this->load->view("template_begin_view");
		$data = array();
		
		if (isset($this->GET_ARRAY["blogID"]))
		{
			$data["post"] = $this->UBP_DAL->getPosts(0, 1, $this->GET_ARRAY["blogID"] + 1);
			$data["post"] = $data["post"][0];
			
			$this->load->view("single_post_view", $data);
		}
		else
		{
			$this->load->view("blog_view", $data);
		}
			
		$this->load->view("template_end_view");
	}
	
	/***************************************
	*	User management functions - BEGIN
	****************************************/
	function forgotPassword()
	{
		$this->load->view('template_begin_view');
		$this->load->view('forgot_password_view');
		$this->load->view('template_end_view');
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
		
		$this->load->view('template_begin_view');
		$this->load->view('login_view');
		$this->load->view('template_end_view');
	}
	
	function logout()
	{
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('loggedIn');
		$this->session->sess_destroy();
		
		$this->load->view('template_begin_view');
		$this->load->view('logout_view');
		$this->load->view('template_end_view');
	}
			
	function resetPassword()
	{
		$this->form_validation->set_rules('password', 'password', 'requiredmin_length[' . MIN_PASSWORD_LENGTH . ']|required|max_length[' . MAX_PASSWORD_LENGTH . ']');
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
		
		$this->load->view('template_begin_view');
		$this->load->view('password_reset_view', $data);
		$this->load->view('template_end_view');
	}
	
	function settings()
	{			
		$data = array(
			"maxFeedSize" => MAX_FEED_PAGE_SIZE,
			"feedSizeIncrement" => FEED_PAGE_SIZE_INCREMENT
		);
		
		$this->load->view('template_begin_view');
		$this->load->view('settings_view', $data);
		$this->load->view('template_end_view');
	}
	
	function signup()
	{
		$userWasSuccessfullyCreated = FALSE;
		
		$this->form_validation->set_rules('username', 'username', 'required|min_length[' . MIN_USERNAME_LENGTH . ']|required|max_length[' . MAX_USERNAME_LENGTH . ']');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[' . MIN_PASSWORD_LENGTH . ']|required|max_length[' . MAX_PASSWORD_LENGTH . ']');
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
		
		$this->load->view("template_begin_view");
		$this->load->view("signup_view");
		$this->load->view("template_end_view");
	}
	
	/***************************************
	*	User management functions - END
	****************************************/
	//-------------------------------------------------------------------------------
	/***************************************
	*	Blog management functions - BEGIN
	****************************************/
	
	function atom()
	{
		$data = array(
			"blogData" => $this->UBP_DAL->getAtomData(ATOM_FEED_SIZE)
		);
		
		$this->load->view("atom_view", $data);
	}
	
	function info()
	{
		$this->load->view("template_begin_view");
		$this->load->view("info_view");
		$this->load->view("template_end_view");	
	}
	
	function post()
	{
		$this->load->view('template_begin_view');
		$this->load->view('post_view');
		$this->load->view('template_end_view');
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
	
	function convert(){
	
		/*$data = $this->UBP_DAL->getOldData("users");
		$this->UBP_DAL->convertUserTable($data, "ubp2");  /**/
		
		/*$data = $this->UBP_DAL->getOldData("blogs");
		$this->UBP_DAL->convertBlogTable($data, "ubp2", $this->UBP_DAL_HELPER); /**/
		
		echo "Done.";
		
	}
	
}?>