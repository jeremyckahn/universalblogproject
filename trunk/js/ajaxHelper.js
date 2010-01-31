// Function taken from W3Schools.  http://www.w3schools.com/ajax/ajax_example_suggest.asp
function getXmlHttpRequestObject()
{
	if (window.XMLHttpRequest)
	{
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
	  // code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}

function ajaxAdapter(url, parameters, objectParam)
{
	this.xhr = getXmlHttpRequestObject();
	var callingObject = objectParam;
	
	if (this.xhr == null)
	{
		alert("Uh oh!  Unable to access the server.");
		return;	
	}
	
	this.send = function(xhr, stateChangeHandlerFunc){
		xhr.onreadystatechange = function(){stateChangeHandlerFunc(callingObject)};
		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(parameters);
	};
}

function JSONLength(JSONObj)
{
	var count = 0;
	
	for (item in JSONObj)
		count++;
		
	return count;
}

function JSONToArray(JSONObj)
{
	var returnArray = new Array();
	
	for (item in JSONObj)
	{
		returnArray.push(JSONObj[item].toString());
	}
		
	return returnArray;
}