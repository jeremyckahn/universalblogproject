function modalManager(instanceName, modalContents){
	this.instanceName = instanceName;
	
	// Values set here for easy modification
	this.backgroundColor = "#000";
	this.opacity = "0.8";
	this.filter = "alpha(opacity=80)";
	this.height = "500px";
	this.width = "650px";
	this.modalPadding = "15px";
	this.modalContents = modalContents;
	this.modalBackgroundName = "modalTransparentBackground";
	this.modalContainerName = "modalContainer";
	this.modalContentsSpacerName = "modalContentsSpacer";
	
	this.opacityThreshold = this.opacity;
	this.opacity = "0.0";
	
	this.documentUpdateAddressor = "document." + this.instanceName + ".updateModal(document." + this.instanceName +");";
	this.documentFadeInAddressor = "document." + this.instanceName + ".fadeIn(document." + this.instanceName +");";
	this.updateHandle;
	
	this.containerDiv = document.createElement("div");
	this.containerDiv.setAttributeNode(document.createAttribute("id"));
	this.containerDiv.id = this.modalContainerName;
	
	this.backgroundDiv = document.createElement("div");
	this.backgroundDiv.setAttributeNode(document.createAttribute("id"));
	this.backgroundDiv.id = this.modalBackgroundName;
	
	this.contentsDiv = document.createElement("div");
	this.contentsDiv.innerHTML = this.modalContents;
	
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
	
	this.contentsSpacer.appendChild(this.contentsDiv);
	this.containerDiv.appendChild(this.backgroundDiv);
	//this.containerDiv.appendChild(this.contentsSpacer);
	
	document[this.instanceName] = this;
	
	this.fadeIn = function(managerObj){
		managerObj.setOpacity(managerObj, managerObj.getOpacity(managerObj) + .075);
		
		if (managerObj.getOpacity(managerObj) >= managerObj.opacityThreshold)
		{
			managerObj.containerDiv.appendChild(managerObj.contentsSpacer);
			managerObj.updateModal(managerObj);
		}
		else
		{
			managerObj.updateHandle = setTimeout(managerObj.documentFadeInAddressor, 25);
		}
	};
	
	this.killModal = function(managerObj){
		document.body.removeChild(managerObj.containerDiv);
		managerObj.setOpacity(managerObj, 0);
		clearTimeout(managerObj.updateHandle);
	};
	
	this.showModal = function(managerObj){
		document.body.appendChild(managerObj.containerDiv);
		eval(managerObj.documentFadeInAddressor);
	};
	
	this.updateModal = function(managerObj){
		this.top = (window.innerHeight - managerObj.contentsDiv.clientHeight) / 2;
		this.left = (document.body.clientWidth - managerObj.contentsDiv.clientWidth) / 2;
		managerObj.contentsSpacer.style.padding = this.top + "px 0px 0px " + this.left + "px";
		managerObj.updateHandle = window.setTimeout(managerObj.documentUpdateAddressor, 50);
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