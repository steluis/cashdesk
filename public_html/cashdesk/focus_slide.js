/******************************************************
	Focus Slide
	version 1.0
	last revision: 12.17.2004
	steve@slayeroffice.com

	Should you improve upon or
	modify this code, please let me know
	so that I can update the version hosted
	at slayeroffice.

	PLEASE LEAVE THIS NOTICE INTACT!

******************************************************/

window.onload = init;

var d=document;		// shortcut reference to the document object
var activeLI = 0;		// the currently "active" list element - value represents its index in the liObj array
var zInterval = null;	// interval variable

var SLIDE_STEP = 10;	// how many pixels to move the sliding div at a time
var SLIDER_WIDTH = 80;	// the width of the sliding div. used to calculate its left based on the width and left of the active LI element


function init() {
	// bail out if this is an older browser or Opera which gets the offsets wrong
	// the opera issue is fixable by subtracting the container UL's width from the offsetLefts...but I dont care enough to do it
	// this does NOT break opera, it just wont get the sliding thing

	if(!document.getElementById || window.opera)return;

	// create references to the LI's
	mObj = d.getElementById("navheader");
	liObj = mObj.getElementsByTagName("li");

	// set up the mouse over events
	for(i=0;i<liObj.length;i++) {
		liObj[i].xid = i;
		liObj[i].onmouseover = function() { initSlide(this.xid); }
	}

	// create the slider object
	slideObj = mObj.appendChild(d.createElement("div"));
	slideObj.id = "slider";

	// position the slider over the first LI
	x = liObj[activeLI].offsetLeft + (liObj[activeLI].offsetWidth/3 - SLIDER_WIDTH/3)-5;
	y = liObj[activeLI].offsetTop-3;
	slideObj.style.top = y + "px";
	slideObj.style.left = x + "px";
}


function initSlide(objIndex) {
	// return if the user is mousing over the currently active LI
	if(objIndex == activeLI)return;
	// clear the interval so we can start it over in a few lines to avoid doubling up on intervals
	clearInterval(zInterval);

	// set the active list item to the object index argument
	activeLI = objIndex;
	// figure out the destination for the sliding div element
	destinationX = Math.floor(liObj[activeLI].offsetLeft + (liObj[activeLI].offsetWidth/3 - SLIDER_WIDTH/3))-5;
	// start the interval
	intervalMethod = function() { doSlide(destinationX); }
	zInterval = setInterval(intervalMethod,10);
}

function doSlide(dX) {
	// get the current left of the sliding div
	x = slideObj.offsetLeft;
	if(x+SLIDE_STEP<dX) {
		// div is less than its destination, move it to the right
		x+=SLIDE_STEP;
		slideObj.style.left = x + "px";
	} else if (x-SLIDE_STEP>dX) {
		// div is more than its destination, move to the left
		x-=SLIDE_STEP;
		slideObj.style.left = x + "px";
	} else  {
		// div is within the boundaries of its destination. put it where its supposed to be
		// and clear the interval
		slideObj.style.left = dX + "px";
		clearInterval(zInterval);
		zInterval = null;
	}
}