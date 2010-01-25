<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">    

    <head>
    
    	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    	
		<meta name="description" content="The Universal Blog Project - a social experiment." />
		
		<meta name="keywords" content="universal, blog, project, ubp, social, experiment, jeremy, kahn, jerbils" />
		
		<meta name="author" content="Jeremy Kahn" />
		
		<link rel="SHORTCUT ICON" href="<?= base_url() . "/assets/images/favicon.ico" ?>" />


    	<title>the universal blog project</title>
    	
    	<link rel = "stylesheet" type = "text/css" href = "<?= base_url() . "css/style.css" ?>" />
    	
    	<script type="text/javascript" src="<?= base_url() . "js/ajaxHelper.js" ?>"></script>
    </head>
    
    <body class="main">
    
	    <div id="titleAndContentContainer" class="titleAndContentContainer">
		    
		    <div id ="mainTitleContainer" class="mainTitleContainer">
				<span class = "title">the universal blog project</span>
			</div>
	
		    <div id ="navPanel" class="navPanel">
		    	<ul>
			    	<!--<span class = "navPanelElement">-->
			    	<li>
				    	<?= anchor("ubp/index", "home", "class=\"navLink rollover\"") . "\n"; ?>
				    </li>
				    <!--</span>-->
				    <?php
				    	if ($this->session->userdata("loggedIn"))
				    	{
				    		echo "<li>" . anchor("ubp/post", "post", "class=\"navLink rollover\"") . "</li>\n    ";
				    		echo "<li>" . anchor("ubp/logout", "log out", "class=\"navLink rollover\"") . "</li>\n    ";
				    		echo "<li class = \"userNameDisplay\">" . "logged in as: " . $this->session->userdata("username") . "</li>\n";
				    	}
				    	else
				    	{
				    		echo "<li>" . anchor("ubp/login", "log in", "class=\"navLink rollover\"") . "</li>\n    ";
				    		echo "<li>" . anchor("ubp/signup", "sign up", "class=\"navLink rollover\"") . "</li>\n";
				    	} 
				    ?>
				    
			    </ul>
			    
			</div> <!-- /navPanel -->