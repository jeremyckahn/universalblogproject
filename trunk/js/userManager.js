function userManager(){
	this.userID;
	this.emailChangeCompleteEventHandler;
	this.feedSizeChangeCompleteEventHandler;
	this.passwordResetCompleteEventHandler;
	this.passwordChangeCompleteEventHandler;
	this.serverJSONResponse;
	
	this.changeEmail = function(serverScriptURL, password, newEmail){
		this.url = serverScriptURL;
		this.parameters = "&password=" + encodeURIComponent(password.toString());
		this.parameters += "&newEmail=" + encodeURIComponent(newEmail.toString());
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.serverJSONResponse = JSON.parse(managerObj.adapter.xhr.responseText);
				
				if (managerObj.emailChangeCompleteEventHandler != null)
					managerObj.emailChangeCompleteEventHandler();
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);	
	}
	
	this.changeFeedSize = function(serverScriptURL, password, feedSize){
		this.url = serverScriptURL;
		this.parameters = "&password=" + encodeURIComponent(password.toString());
		this.parameters += "&feedSize=" + encodeURIComponent(feedSize.toString());
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.serverJSONResponse = JSON.parse(managerObj.adapter.xhr.responseText);
				
				if (managerObj.feedSizeChangeCompleteEventHandler != null)
					managerObj.feedSizeChangeCompleteEventHandler();
			}	
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);		
	}
	
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
	
	this.setFeedSizeChangeCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.feedSizeChangeCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setEmailChangeCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.emailChangeCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setPasswordResetCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.passwordResetCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setPasswordChangeCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.passwordChangeCompleteEventHandler = eventHandlerFunc;
	};
}