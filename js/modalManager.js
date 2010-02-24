function modalManager(instanceName, modalContents){
	this.instanceName = instanceName;
	
	// Values set here for easy modification
	this.fadeInRate = 1;
	this.fadeOutRate = 3;
	this.backgroundColor = "#000";
	this.opacity = "0.7";
	this.filter = "alpha(opacity=70)";
	this.height = "500px";
	this.width = "650px";
	this.modalPadding = "15px";
	this.modalContents = modalContents;
	this.modalBackgroundName = "modalTransparentBackground";
	this.modalContainerName = "modalContainer";
	this.modalContentsSpacerName = "modalContentsSpacer";
	
	// Set the opacity threshold and reset the opacity to 0
	this.opacityThreshold = this.opacity;
	this.opacity = "0.0";
	this.filter = "alpha(opacity=0)";
	
	// Define globally-accessible variable names for object methods
	this.documentUpdateAddressor = "document." + this.instanceName + ".updateModal(document." + this.instanceName +");";
	this.documentFadeInAddressor = "document." + this.instanceName + ".fadeIn(document." + this.instanceName +");";
	this.documentFadeOutAddressor = "document." + this.instanceName + ".fadeOut(document." + this.instanceName +");";
	
	// Handle for setTimeouts
	this.updateHandle;
	
	this.isVisible = false;
	
	// Event handlers
	this.fadeInCompleteEventHandler, this.fadeOutCompleteEventHandler;
	
	// Construct the HTML elements of the modal
	this.containerDiv = document.createElement("div");
	this.containerDiv.setAttributeNode(document.createAttribute("id"));
	this.containerDiv.id = this.modalContainerName;
	
	this.backgroundDiv = document.createElement("div");
	this.backgroundDiv.setAttributeNode(document.createAttribute("id"));
	this.backgroundDiv.id = this.modalBackgroundName;
	
	this.contentsDiv = document.createElement("div");
	this.contentsDiv.innerHTML = this.modalContents.innerHTML;
	
	// Set a pile of styles
	this.backgroundDiv.style.height = "100%";
	this.backgroundDiv.style.width = "100%";
	this.backgroundDiv.style.background = this.backgroundColor;
	this.backgroundDiv.style.opacity = this.opacity;
	this.backgroundDiv.style.filter = this.filter;
	this.backgroundDiv.style.position = "fixed";
	this.backgroundDiv.style.top = "0px";
	this.backgroundDiv.style.left = "0px";
	
	this.contentsSpacer = document.createElement("div");
	this.contentsSpacer.setAttributeNode(document.createAttribute("id"));
	this.contentsSpacer.id = this.modalContentsSpacerName;
	this.contentsSpacer.style.position = "fixed";
	this.contentsSpacer.style.top = "0px";
	this.contentsSpacer.style.left = "0px";
	
	this.contentsDiv.style.background = getElementStyle(document.body, "backgroundColor");
	this.contentsDiv.style.opacity = "1.0";
	this.contentsDiv.style.filter = "alpha(opacity=100)";
	this.contentsDiv.style.padding = this.modalPadding;
	
	// Put everything inside of its' proper container
	this.contentsSpacer.appendChild(this.contentsDiv);
	this.containerDiv.appendChild(this.backgroundDiv);
	
	// Assign this to the document so it can be accessed later
	document[this.instanceName] = this;
	
	this.fadeIn = function(managerObj){
		managerObj.setOpacity(managerObj, managerObj.getOpacity(managerObj) + managerObj.fadeInRate/10);
		
		if (managerObj.getOpacity(managerObj) >= managerObj.opacityThreshold){
			managerObj.containerDiv.appendChild(managerObj.contentsSpacer);
			managerObj.isVisible = true;
			managerObj.updateModal(managerObj);
			
			if (managerObj.fadeInCompleteEventHandler != null)
				managerObj.fadeInCompleteEventHandler();
		}
		else{
			managerObj.updateHandle = setTimeout(managerObj.documentFadeInAddressor, 50);
		}
	};
	
	this.fadeOut = function(managerObj){
		managerObj.setOpacity(managerObj, managerObj.getOpacity(managerObj) - managerObj.fadeOutRate/10);
		
		if (managerObj.getOpacity(managerObj) <= 0){
			clearTimeout(managerObj.updateHandle);
			document.body.removeChild(managerObj.containerDiv);
			managerObj.updateModal(managerObj);
			managerObj.setOpacity(managerObj, 0);
			managerObj.isVisible = false;

			// Take the modal contents and put them back where you found them, so they can be used later.
			managerObj.modalContents.innerHTML = managerObj.contentsDiv.innerHTML;
			
			if (managerObj.fadeOutCompleteEventHandler != null)
				managerObj.fadeOutCompleteEventHandler();
		}
		else{
			managerObj.updateHandle = setTimeout(managerObj.documentFadeOutAddressor, 50);
		}
	};
	
	this.hideModal = function(managerObj){
		managerObj.containerDiv.removeChild(managerObj.contentsSpacer);
		eval(managerObj.documentFadeOutAddressor);
	};
	
	this.showModal = function(managerObj){
		// Clear out the original HTML for the modal contents so it doesn't break the identical modal contents
		managerObj.modalContents.innerHTML = "";
		document.body.appendChild(managerObj.containerDiv);
		eval(managerObj.documentFadeInAddressor);
	};
	
	this.updateModal = function(managerObj){
		this.top = (window.innerHeight - managerObj.contentsDiv.clientHeight) / 2;
		this.left = (document.body.clientWidth - managerObj.contentsDiv.clientWidth) / 2;
		managerObj.contentsSpacer.style.padding = this.top + "px 0px 0px " + this.left + "px";
		
		if (managerObj.isVisible)
			managerObj.updateHandle = window.setTimeout(managerObj.documentUpdateAddressor, 50);
	};
	
	this.setFadeInCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.fadeInCompleteEventHandler = eventHandlerFunc;
	};
	
	this.setFadeOutCompleteEventHandler = function(managerObj, eventHandlerFunc){
		managerObj.fadeOutCompleteEventHandler = eventHandlerFunc;
	};
	
	this.getOpacity = function(managerObj){
		return parseFloat(managerObj.opacity);
	};
	
	this.setOpacity = function(managerObj, opacity){
		managerObj.opacity = opacity.toString();
		managerObj.filter = "alpha(opacity=" + (opacity * 100) + ")";
		managerObj.backgroundDiv.style.opacity = managerObj.opacity;
		managerObj.backgroundDiv.style.filter = managerObj.filter;
	};
}