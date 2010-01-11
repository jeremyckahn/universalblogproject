<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">    
    <head>
    	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    	<title></title>
    </head>
    <body>
    <?= "\t" . anchor("ubp/index", "home", "class=\"navLink\"") . "\n"; ?>
    <?php
    	if ($this->session->userdata("loggedIn"))
    	{
    		echo "\t" . anchor("ubp/post", "post") . "\n    ";
    		echo "\t" . anchor("ubp/logout", "log out") . "\n    ";
    		echo "\t" . "logged in as: " . $this->session->userdata("username") . "\n";
    	}
    	else
    	{
    		echo "\t" . anchor("ubp/login", "log in") . "\n    ";
    		echo "\t" . anchor("ubp/signup", "sign up") . "\n";
    	} 
    ?>