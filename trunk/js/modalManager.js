function modalManager(modalContents){
	this.backgroundColor = "#000";
	this.opacity = "0.4";
	this.filter = "alpha(opacity=40)";
	this.height = "500px";
	this.width = "650px";
	this.modalContents = modalContents;
	this.modalBackgroundName = "modalTransparentBackground";
	
	this.backgroundDiv = document.createElement("div");
	this.backgroundDiv.setAttributeNode(document.createAttribute("id"));
	this.backgroundDiv.id = this.modalBackgroundName;
	
	this.contentsDiv = document.createElement("div");
	this.contentsDiv.innerHTML = this.modalContents;
	this.backgroundDiv.appendChild(this.contentsDiv);
	
	this.backgroundDiv.style.height = "100%";
	this.backgroundDiv.style.width = "100%";
	this.backgroundDiv.style.background = this.backgroundColor;
	this.backgroundDiv.style.opacity = this.opacity;
	this.backgroundDiv.style.filter = this.filter;
	this.backgroundDiv.style.position = "fixed";
	this.backgroundDiv.style.top = "0px";
	this.backgroundDiv.style.left = "0px";
	
	// TODO:  This isn't working.  Find another method.
	this.contentsDiv.style.background = getElementStyle(document.body, "background");
	
	this.showModal = function(managerObj){
		
		// Some test crap
//		managerObj.backgroundDiv.style.background = "#f0f";
		
		document.body.appendChild(managerObj.backgroundDiv);
	};
	
	this.killModal = function(){
		
	};
	
	this.updateModal = function(){
		
	};
	
}


/*  How is this going to work?

The object should expect some HTML in the constructor.  This will be passed using document.getElementById and innerHTML.
	- Note:  The container tag that holds the HTML should be set to display: none by an inline style.
	
There needs to be two functions to call:  showModal and killModal.

Internally, the modal needs to keep things displayed properly by constantly re-aligning itself.  There will need to be a funtion called realign() that is constantly called by setInterval().  This will be cancelled by killModal();

Functions:

showModal()
killModal()
updateModal()

*/