function blogManager(){
	this.blogArray;
	
	this.loadMorePosts = function(serverScriptURL, requestSize, startFrom, userID, blogContainer){
		this.content = blogContainer;
		this.url = serverScriptURL.toString();
		this.parameters = "requestSize=" + requestSize.toString();
		this.parameters += "&startFrom=" + startFrom.toString();
		this.parameters += "&userID=" + userID.toString();
		this.parameters += "&sid=" + Math.random();
		this.loadEventHandler;
		
		this.adapter = new ajaxAdapter(this.url, this.parameters, this);
		
		this.eventHandler = function(managerObj){
			if (managerObj.adapter.xhr.readyState == 4)
			{
				managerObj.content.innerHTML += (managerObj.adapter.xhr.responseText);
				var blogList = document.getElementById("blogList");
				
				managerObj.blogArray = blogList.value.split("_");
				managerObj.blogArray.pop();
				managerObj.content.removeChild(blogList);
				
				if (managerObj.loadEventHandler != null)
				{
					managerObj.loadEventHandler();
				}
			}
		};
		
		this.adapter.send(this.adapter.xhr, this.eventHandler);
	};
	
	this.setLoadEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.loadEventHandler = eventHandlerFunc;
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
}