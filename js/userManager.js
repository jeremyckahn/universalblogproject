function userManager(){
	this.userID
	this.changePasswordCompleteEventHandler;
	
	this.changePassword(serverScriptURL, username, email){
		this.url = serverScriptURL;
		this.parameters = "username=" + username.toString();
		this.parameters += "&email=" + email.toString();
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.setChangePasswordCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.changePasswordCompleteEventHandler = eventHandlerFunc;
	};
}