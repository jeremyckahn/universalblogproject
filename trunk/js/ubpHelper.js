function attachEnterKeySubmitFunc(element, func){
	element.onkeydown = function(evt){
		if (evt.keyCode == 13){
			func();
		}
	}
}

function focusOnFirstTextBox(){
	var inputs = document.getElementsByTagName("input");
	
	if (typeof inputs[0] != "undefined"){
		for (var i = 0; i < inputs.length; i++){
			if (inputs[i].type == "text" ||
				inputs[i].type == "password"){
				inputs[i].focus();
				break;
			}
		}
	}
}

function isArray(theVar){
	return ((typeof theVar == "object") && !(typeof theVar[1] == "undefined"))
}

function linkTo(url){
	window.location.assign(url);
}

function registerSubmitKey(){
	var inputs = document.getElementsByTagName("input");
	
	if (typeof inputs[0] != "undefined"){
		for (var i = inputs.length - 1; i > 0; i--){
			if (inputs[i].type == "text" ||
				inputs[i].type == "password"){
				inputs[i].onkeydown = function(evt){
					if (evt.keyCode == 13){
						if (document.forms.length == 1){
							document.forms[0].submit();
						}
					}
				};
				break;
			}
		}
	}
}

function removeTrailingSpacesFrom(thatString){
	thisString = thatString;
	
	while (thisString[thisString.length - 1] == " ")
		thisString = thisString.substr(0, thisString.length - 1);
		
	return thisString;
}

function resetErrorStyle(element, errorMessageElement){
	removeClass(element, 'errorHighlight');
	errorMessageElement.style.display = "none";
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

function ubpInit(){
	focusOnFirstTextBox();
	registerSubmitKey();
}