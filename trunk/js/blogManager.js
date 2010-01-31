function blogManager(){
	this.blogArray;
	this.loadCompleteEventHandler;
	this.blacklistCompleteEventHandler;
	this.postValidationCompleteEventHandler;
	this.blogsRemain;
	this.postValidationJSON;
	
	this.blacklist = function(managerObj, serverScriptURL, postID, userID){
		this.url = serverScriptURL.toString();
		this.parameters = "postID=" + postID.toString();
		this.parameters += "&userID=" + userID.toString();
		this.parameters += "&sid=" + Math.random();
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				var idToCut = parseInt(managerObj.adapter.xhr.responseText);
				var indexToCut;
				
				for (var i = 0; i < managerObj.blogArray.length; i++)
				{
					if (managerObj.blogArray[i] == idToCut)
					{
						indexToCut = managerObj.blogArray[i];
						break;
					}
				}
				
				managerObj.blogArray.splice(indexToCut, 1);
				
				if (managerObj.blacklistCompleteEventHandler != null)
				{
					managerObj.blacklistCompleteEventHandler();
				}
			}
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.getLastPost = function(managerObj){		
		if (managerObj.blogArray)
		{
			if (managerObj.blogArray.length > 0)
				return managerObj.blogArray[managerObj.blogArray.length - 1];
			else
				return -1;
		}
		else
			return 0;
	};
	
	this.loadMorePosts = function(serverScriptURL, requestSize, startFrom, userID, blogContainer){
		this.content = blogContainer;
		this.url = serverScriptURL.toString();
		this.parameters = "requestSize=" + requestSize.toString();
		this.parameters += "&startFrom=" + startFrom.toString();
		this.parameters += "&userID=" + userID.toString();
		this.parameters += "&sid=" + Math.random();
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.content.innerHTML += (managerObj.adapter.xhr.responseText);
				
				var blogList = document.getElementById("blogList");
				managerObj.blogArray = blogList.value.split("_");
				managerObj.blogArray.pop();
				managerObj.content.removeChild(blogList);
				
				var blogsRemain = document.getElementById("blogsRemain");
				managerObj.blogsRemain = (blogsRemain.value == "TRUE") ? true : false;
				managerObj.content.removeChild(blogsRemain);
				
				if (managerObj.loadCompleteEventHandler != null)
				{
					managerObj.loadCompleteEventHandler();
				}
			}
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.setloadCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.loadCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setBlacklistCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.blacklistCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setPostValidationCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.postValidationCompleteEventHandler = eventHandlerFunc;
	};
	
	this.validatePost = function(serverScriptURL, titleToValidate, postBodyToValidate){
		this.url = serverScriptURL.toString();
		this.parameters = "title=" + titleToValidate.toString();
		this.parameters += "&post=" + postBodyToValidate.toString();
		this.parameters += "&sid=" + Math.random();
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.postValidationJSON = JSON.parse(managerObj.adapter.xhr.responseText);
				
				if (managerObj.postValidationCompleteEventHandler != null)
				{
					managerObj.postValidationCompleteEventHandler();
				}
			}
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	}
}