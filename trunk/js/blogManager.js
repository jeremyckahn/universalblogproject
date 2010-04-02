function blogManager(){
	this.blogArray;
	this.blogTemplate
	this.loadCompleteEventHandler;
	this.blacklistCompleteEventHandler;
	this.loadWaitEventHandler;
	this.postValidationCompleteEventHandler;
	this.postSubmitCompleteEventHandler;
	this.blogsRemain;
	this.postValidationJSON;
	
	this.blacklist = function(managerObj, serverScriptURL, postID, userID){
		this.url = serverScriptURL.toString();
		this.parameters = "postID=" + postID.toString();
		this.parameters += "&userID=" + userID.toString();
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
	
	this.createPost = function(serverScriptURL, title, post, userID){
		this.url = serverScriptURL.toString();
		this.parameters = "title=" + encodeURIComponent(title.toString());
		this.parameters += "&post=" + encodeURIComponent(post.toString());
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				if (managerObj.postSubmitCompleteEventHandler != null)
				{
					managerObj.postSubmitCompleteEventHandler();
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
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 1)
			{
				if (managerObj.loadWaitEventHandler != null)
				{
					managerObj.loadWaitEventHandler();
				}
			}
		
			if (managerObj.adapter.xhr.readyState == 4)
			{
				// If no blogs are returned, set the managerObj accordingly.
				if (managerObj.adapter.xhr.responseText.toString().toLowerCase() == "false"){
					managerObj.blogsRemain = false;
					return;
				}
	
				// Using this to try to debug a bizarre Chrome bug.
				/*try {
					JSON.parse(managerObj.adapter.xhr.responseText)
				}
				catch (ex){
					alert(ex);
					document.write(managerObj.adapter.xhr.responseText);				
				}/**/
				
				this.serverResponse = JSON.parse(managerObj.adapter.xhr.responseText);
				this.postData = serverResponse.postData;
				
				if (!managerObj.blogArray)
					managerObj.blogArray = new Array();
				
				for (i = 0; i < postData.length; i++){
					
					// Swap out all of the placeholders with data
					this.output = manager.blogTemplate.replace(/0postID0/g, this.postData[i].postID)
					this.output = this.output.replace(/0postTitle0/g, this.postData[i].postTitle);
					this.output = this.output.replace(/0postBody0/g, this.postData[i].postBody);
					this.output = this.output.replace(/0postDate0/g, this.postData[i].postDate);
					
					// Spit out the data to the container
					managerObj.content.innerHTML += this.output;
					
					// Update the blogArray
					managerObj.blogArray.push(this.postData[i].postID);
				}
				
				managerObj.blogsRemain = this.serverResponse.postsRemain;
							
				if (managerObj.loadCompleteEventHandler != null)
				{
					managerObj.loadCompleteEventHandler();
				}
			}
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.formatPost = function(managerObj){
		
	};
	
	this.setPostTemplate = function(managerObj, template){
		manager.blogTemplate = template.innerHTML;
	};
	
	this.setloadCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.loadCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setBlacklistCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.blacklistCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setLoadWaitEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.loadWaitEventHandler = eventHandlerFunc;
	};
	
	this.setPostSubmitCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.postSubmitCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setPostValidationCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.postValidationCompleteEventHandler = eventHandlerFunc;
	};
	
	this.validatePost = function(serverScriptURL, titleToValidate, postBodyToValidate){
		this.url = serverScriptURL.toString();
		this.parameters = "title=" + encodeURIComponent(titleToValidate.toString());
		this.parameters += "&post=" + encodeURIComponent(postBodyToValidate.toString());
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