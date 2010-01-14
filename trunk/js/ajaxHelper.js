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
	
	if (this.xhr == null)
	{
		alert("Uh oh!  Unable to access the server.");
		return;	
	}
	
	this.send = function(xhr, stateChangeHandlerFunc){
		xhr.onreadystatechange = stateChangeHandlerFunc;
		xhr.open("POST", url, true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(parameters);
	};
}