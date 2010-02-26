function addClass(element, classToAdd)
{
	// If classToAdd is not already present, add it
	if (element.className.search(classToAdd) == -1){
		element.className += " " + classToAdd;
		element.className = removeTrailingSpacesFrom(element.className);
	}
}

function removeClass(element, classToRemove)
{
	// If the class is present, remove it
	if (element.className.search(classToRemove) != -1)
	{
		element.className = element.className.replace(classToRemove, "");
		element.className = removeTrailingSpacesFrom(element.className);
	}
}

function getElementStyle(element, style)
{
	if (getComputedStyle)
		return getComputedStyle(element, null)[style];
	else
		return element.currentStyle[style]; // Test this on IE
}