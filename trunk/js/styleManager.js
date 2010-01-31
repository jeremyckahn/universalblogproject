function addClass(element, classToAdd)
{
	element.className += " " + classToAdd;
}

function removeClass(element, classToRemove)
{
	// If the class is present, remove it
	if (element.className.search(classToRemove) != -1)
	{
		element.className = element.className.replace(classToRemove, "");
	}
}