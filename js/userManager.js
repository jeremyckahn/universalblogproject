function userManager(){
	this.userID
	this.changePasswordCompleteEventHandler;
	this.serverJSONResponse;
	
	this.resetPasswordRequest = function(serverScriptURL, username, email){
		this.url = serverScriptURL;
		this.parameters = "username=" + encodeURIComponent(username.toString());
		this.parameters += "&email=" + encodeURIComponent(email.toString());
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.serverJSONResponse = JSON.parse(managerObj.adapter.xhr.responseText);
				
				if (managerObj.changePasswordCompleteEventHandler != null)
					managerObj.changePasswordCompleteEventHandler();
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.setChangePasswordCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.changePasswordCompleteEventHandler = eventHandlerFunc;
	};
}