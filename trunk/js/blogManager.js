function blogManager(){
	var blogArray = new Array();
	
	this.loadMorePosts = function(serverScriptURL, requestSize, startFrom, userID, blogContainer){
		var content = blogContainer;
		var url = serverScriptURL.toString();
		var parameters = "requestSize=" + requestSize.toString();
		parameters += "&startFrom=" + startFrom.toString();
		parameters += "&userID=" + userID.toString();
		parameters += "&sid=" + Math.random();
		
		var adapter = new ajaxAdapter(url, parameters, this);
		
		this.eventHandler = function(){
			if (adapter.xhr.readyState == 4)
			{
				//alert(arguments[0]);
				content.innerHTML = (adapter.xhr.responseText);
				var blogList = document.getElementById("blogList");
				
				blogArray = blogList.value.split("_");
				blogArray.pop();
				content.removeChild(blogList);
			}
		};
		
		this.getBlogIDArray = function(){
			return blogArray;
		};
		
		adapter.send(adapter.xhr, this.eventHandler);
	};
}