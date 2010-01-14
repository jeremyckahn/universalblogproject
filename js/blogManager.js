var content, blogXHR, blogArray;

function blogManager(blogDiv, xhrObject){
	blogXHR = xhrObject;
	content = blogDiv;
	blogArray = new Array();
	
	this.loadMorePosts = function(serverScriptURL, requestSize, startFrom, userID){
		if (blogXHR == null)
		{
			alert("Uh oh!  Unable to access the server.");
			return;	
		}
		
		var url = serverScriptURL.toString();
		var parameters = "requestSize=" + requestSize.toString();
		parameters += "&startFrom=" + startFrom.toString();
		parameters += "&userID=" + userID.toString();
		parameters += "&sid=" + Math.random();
		
		blogXHR.onreadystatechange = function(){
			if (blogXHR.readyState == 4)
			{
				content.innerHTML = (blogXHR.responseText);
				var blogList = document.getElementById("blogList");
				
				blogArray = blogList.value.split("_");
				blogArray.pop();
				content.removeChild(blogList);
			}
		};
		
		blogXHR.open("POST", url, true);
		blogXHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		blogXHR.send(parameters);
	};
}