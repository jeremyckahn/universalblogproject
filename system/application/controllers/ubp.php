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
		
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->load->model('UBP_DAL', '', TRUE);
		$this->load->model('UBP_DAL_HELPER', '', TRUE);
	}
	
	function index()
	{
		if($this->session->userdata("loggedIn"))
		{
			if ($this->input->post("blacklistButton"))
				$this->UBP_DAL->createBlacklist($this->session->userdata("userID"), $this->input->post("blacklistButton"), $this->MAX_BLACKLIST_LIMIT = 20);
			
			$postArray = $this->UBP_DAL->getPosts($this->session->userdata("userID"), $this->session->userdata("feedPageSize"), 0); // LAST VARIABLE IS STUB
		}
		else
		{
			$postArray = $this->UBP_DAL->getPosts(FALSE, $this->MAX_DEFAULT_FEED_PAGE_SIZE, 0); // LAST VARIABLE IS STUB
		}
	
		$this->load->view("templateBegin");
		$this->load->view('blogView', Array("postArray" => $postArray));
		$this->load->view("templateEnd");
	}
	
	/***************************************
	*	User management functions - BEGIN
	****************************************/
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
	
	function signup()
	{
		$this->form_validation->set_rules('username', 'username', 'required|min_length[' . $this->MIN_USERNAME_LENGTH . ']|required|max_length[' . $this->MAX_USERNAME_LENGTH . ']');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->MIN_PASSWORD_LENGTH . ']|required|max_length[' . $this->MAX_PASSWORD_LENGTH . ']');
		$this->form_validation->set_rules('passwordConfirm', 'password confirmation', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		
		if ($this->form_validation->run())
		{
			// Set a new validation rule to call the custom callback, and revalidate.
			$this->form_validation->set_rules('username', 'username', 'callback_canCreateUser');
			$this->form_validation->run();
			
			$userWasSuccessfullyCreated = $this->UBP_DAL->createUser($this->input->post("username", TRUE), $this->input->post("password", TRUE), $this->input->post("email", TRUE));
			
			if ($userWasSuccessfullyCreated)
				$this->UBP_DAL_HELPER->logUserIn($this->input->post("username", TRUE), $this->input->post("password", TRUE));
		}
		
		$this->load->view("templateBegin");
		$this->load->view("signupForm");
		$this->load->view("templateEnd");
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
	
	/***************************************
	*	User management functions - END
	****************************************/
	
	/***************************************
	*	Blog management functions - BEGIN
	****************************************/
	
	// Not done yet.
	function blogLoader()
	{	
		//echo ($this->input->post("userID"));
	
		if($this->session->userdata("loggedIn"))
		{
			if ($this->input->post("blacklistButton"))
				$this->UBP_DAL->createBlacklist($this->session->userdata("userID"), $this->input->post("blacklistButton"), $this->MAX_BLACKLIST_LIMIT = 20);
			
			$postArray = $this->UBP_DAL->getPosts($this->session->userdata("userID"), $this->session->userdata("feedPageSize"), 0); // LAST VARIABLE IS STUB
		}
		else
		{
			$postArray = $this->UBP_DAL->getPosts(FALSE, $this->MAX_DEFAULT_FEED_PAGE_SIZE, 0); // LAST VARIABLE IS STUB
		}
		
		if (isset($postArray)) 
		{
			foreach ($postArray as $post)
			{
				echo "<h1 class=\"articleHeader\">" . htmlentities(urldecode($post['title'])) . "</h1>";
				echo "<p>" . htmlentities(urldecode($post['post'])) . "</p>";
			}
		}
	}
	
	function post()
	{
		$postSubmittedSuccessfully = FALSE;
		
		if ($this->session->userdata("username"))
		{	
			$this->form_validation->set_rules('title', 'title', 'required|max_length[' . $this->MAX_TITLE_LENGTH . ']');
			$this->form_validation->set_rules('post', 'post', 'required|max_length[' . $this->MAX_POST_LENGTH . ']');
			
			if ($this->form_validation->run())
			{
				$postSubmittedSuccessfully = $this->UBP_DAL->createPost($this->input->post("title", TRUE), $this->input->post("post", TRUE), $this->session->userdata("userID"));
			}
		}
		
		$this->load->view('templateBegin');
		$this->load->view($postSubmittedSuccessfully ? 'postSubmitted' : 'postForm');
		$this->load->view('templateEnd');	
	}
	
	/***************************************
	*	Blog management functions - END
	****************************************/
	
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
}?>