/**
 * Code provides the iframe Javascript functionality and watchers.
 *
 * iframes are generated by passing the current domain for CORS-allowed postMessage passing.
 * The code below listens for postMessages from admail. Messages include the current height of the form
 * and allow the iframe to be resized to display the full height.
 *
 * Note: Putting pauses in the browser on the code below (for debugging) will cause the messages to expire
 * and the code not to work.  Use console.log instead.
 * @author     Michael Parisi <mgparisicpu@gmail.com>
 * @link       http://Admail.net
 * @since      1.0.0
 * @package    Admail
 * @subpackage Admail/includes
 * @copyright  2019 Admail.net
 */
//Check to see if we receive a message.
if ("onmessage" in window) {
	//Event Method is the Label of the Event.
	var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	//Event message is a custom message based on the eventMethod.
	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

	//Eventer is the Windows array with the EventMethod that has the messageEvent within.
	var eventer = window[eventMethod];

	//Call the message event function on the event we need.
	eventer(messageEvent, function (e) {
		//Check to make sure the event comes from Admail.net.
	        if ((e.origin == 'https://www.admail.net') || (e.origin == 'http://www.admail.net')) {
			//get an array of all valid admail iframes.  They have the class .admail-form.
			var frames = document.getElementsByClassName('admail-form');
			//Loop through the frames array.
			for (var i = 0; i < frames.length; i++) {
				//look for the frame with the admail.net source.
				//Todo: compare more then source to handle multiple forms on a page.
				if (frames[i].contentWindow === e.source) {
					//Get the height from the data, and make it into the pixles value.
					var height = e.data + 'px';
					//Set the height of the frame to the message received.
					frames[i].style['height'] = height;

				}
			}
			//if the jQuery is loaded and the colorbox is loaded (modal only), then resize the iframe to the new size!
			if (window.jQuery && window.jQuery.colorbox) {
				jQuery.colorbox.resize();
			}
		}
	});
}

/**
 * Gets the cookie.
 * @param name
 * @returns {string}
 */
function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}