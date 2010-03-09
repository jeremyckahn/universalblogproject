function isArray(theVar){
	return ((typeof theVar == "object") && !(typeof theVar[1] == "undefined"))
}

function linkTo(url){
	window.location.assign(url);
}

function resetErrorStyle(element, errorMessageElement){
	removeClass(element, 'errorHighlight');
	errorMessageElement.style.display = "none";
}

function removeTrailingSpacesFrom(thatString){
	thisString = thatString;
	
	while (thisString[thisString.length - 1] == " ")
		thisString = thisString.substr(0, thisString.length - 1);
		
	return thisString;
}

function setError(errorOutput, errorField, errorText){
	removeClass(errorOutput, "hidden");
	addClass(errorField, "errorHighlight");
	errorOutput.innerHTML = errorText;
	errorField.onfocus = function(){
		addClass(errorOutput, "hidden");
		removeClass(errorField, "errorHighlight");
		errorOutput.innerHTML = "";	
	};
}

function setRemovableOutput(outputContainer, outputContent){
	removeClass(outputContainer, "hidden");
	outputContainer.innerHTML = outputContent;
	outputContainer.onclick = function(){
		addClass(outputContainer, "hidden");
		outputContainer.innerHTML = "";	
	};
}