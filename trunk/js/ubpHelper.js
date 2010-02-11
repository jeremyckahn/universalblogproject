function isArray(theVar)
{
	return ((typeof theVar == "object") && !(typeof theVar[1] == "undefined"))
}

function linkTo(url)
{
	window.location.assign(url);
}

function resetErrorStyle(element, errorMessageElement)
{
	removeClass(element, 'errorHighlight');
	errorMessageElement.style.display = "none";
}