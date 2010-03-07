function userManager(){
	this.userID
	this.passwordResetCompleteEventHandler;
	this.passwordChangeCompleteEventHandler;
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
				
				if (managerObj.passwordResetCompleteEventHandler != null)
					managerObj.passwordResetCompleteEventHandler();
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.resetPassword = function(serverScriptURL, currentPassword, newPassword){
		// TODO:  THIS ISN'T DONE YET
		
		this.url = serverScriptURL;
		this.parameters = "currentPassword=" + encodeURIComponent(currentPassword.toString());
		this.parameters += "&newPassword=" + encodeURIComponent(newPassword.toString());
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.serverJSONResponse = JSON.parse(managerObj.adapter.xhr.responseText);
				
				if (managerObj.passwordChangeCompleteEventHandler != null)
					managerObj.passwordChangeCompleteEventHandler();
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.setPasswordResetCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.passwordResetCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setPasswordChangeCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.passwordChangeCompleteEventHandler = eventHandlerFunc;
	};
}