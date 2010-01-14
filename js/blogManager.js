function blogManager(){
	this.blogArray = new Array();
	
	this.loadMorePosts = function(serverScriptURL, requestSize, startFrom, userID, blogContainer){
		var content = blogContainer;
		var url = serverScriptURL.toString();
		var parameters = "requestSize=" + requestSize.toString();
		parameters += "&startFrom=" + startFrom.toString();
		parameters += "&userID=" + userID.toString();
		parameters += "&sid=" + Math.random();
		this.loadEventHandler;
		
		var adapter = new ajaxAdapter(url, parameters, this);
		
		this.eventHandler = function(managerObj){
			if (adapter.xhr.readyState == 4)
			{
				content.innerHTML = (adapter.xhr.responseText);
				var blogList = document.getElementById("blogList");
				
				managerObj.blogArray = blogList.value.split("_");
				managerObj.blogArray.pop();
				content.removeChild(blogList);
				
				
				if (managerObj.loadEventHandler != null)
				{
					managerObj.loadEventHandler();
				}
			}
		};
		
		adapter.send(adapter.xhr, this.eventHandler);
	};
	
	this.setLoadEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.loadEventHandler = eventHandlerFunc;
	};
}