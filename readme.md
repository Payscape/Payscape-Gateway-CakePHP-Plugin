
	

	/*
	 * Payscape Direct Post API CakePHP Plugin v3.0
	 * 
	 * Place this Plugin in your app/Plugin directory. 
	 * 
	 * Configuration: Payscape/Controller/PayscapeComponent.php
	 * Edit userid: replace with your User ID from your Payscape account
	 * Edit userpass: replace with your Password from your Payscape account
	 * 
	 * 
	 * Load the Plugin in your Config/bootstrap file. 
	 * 
	 * CakePlugin::load('Payscape');
	 * 
	 * Include the Payscape Component in your Controller 
	 * public $components = array('Paginator', 'Session', 'Payscape.Payscape');
	 * 
	 *  /webroot/crt/cacert.pem is included so that you may use cURL. 
	 * You may also download this file at the cURL website:
	 *  http://curl.haxx.se/ca/cacert.pem 
	 *
	 * 
	 * You may use either cURL or Cake's HTTPSocket for your send() function.
	 * Both are included here. 
	 * 
	 * Sale() detects if your transaction is Credit Card or eCheck and sends the correct params 
	 * Two send() methods are included, one that uses Cake's HTTPSocket, as well as one that uses cURL.
	 * To use the Cake HTTPSocket version, simply rename sendHTTPSocket() to send(), and the current send() to sendcURL(). 
	 * 
	 * 
	 * Add 'Payscape' to your array of components in your Controller, or AppController 
	 * to make the Class available for all of your Controllers
	 * 
	 * Payscape Direct Post API CakePHP Plugin exposes all of the methods of the Payscape NMI API
	 * 
	 * See Payscape Direct Post API Documentation for complete notes on variables:
	 * 
	 * Direct Post API / Documentation / Transaction Variables
	 * http://payscape.com/developers/direct-post-api.php
	 * 
	 * See the Payscape CakePHP Developers Suite for examples of each of the methods.
	 * 
	 * 1/14/2014
	 * 
	 * */