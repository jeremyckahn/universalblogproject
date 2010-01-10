<?php class Ubp_dal_helper extends Model {

    function Ubp_dal_helper()
    {
        parent::Model();
        
        $this->load->model('UBP_DAL', '', TRUE);
    }
    
    function logUserIn($username, $password)
    {
    	$user = $this->UBP_DAL->getUserDataArray($username, $password);
    	
    	if ($user)
		{
			$sessionData = array(
				'username' => $user["username"],
				'userID'   => $user["userID"],
				'loggedIn' => TRUE
			);

			$this->session->set_userdata($sessionData);
			return TRUE;
		}
		else
			return FALSE;
   }
}?>