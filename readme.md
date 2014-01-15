
#Payscape Gateway CakePHP Plugin v3.0#
	  
##Installation## 
1. Place this Plugin in your app/Plugin directory. 
2. Place Payscape/Config/payscape.php in your /app/Config folder
3.	Edit userid: replace with your User ID from your Payscape account 
4. Edit userpass: replace with your Password from your Payscape account
5. Load the Plugin in your Config/bootstrap file. 
```
CakePlugin::load('Payscape');
```
6. Include the Payscape Component in your Controller 
```
public $components = array('Payscape.Payscape');
```
	  
/webroot/crt/cacert.pem is included so that you may use cURL. 
You may also download this file at the cURL website http://curl.haxx.se/ca/cacert.pem 
	 
	  
You may use either cURL or Cake's HTTPSocket for your send() function.
Both are included here. 
	  
Sale() detects if your transaction is Credit Card or eCheck and sends the correct params 
Two send() methods are included, one that uses Cake's HTTPSocket, as well as one that uses cURL.
To use the Cake HTTPSocket version, simply rename sendHTTPSocket() to send(), and the current send() to sendcURL(). 
	  
Payscape Gateway CakePHP Plugin exposes all of the methods of the Payscape NMI API
	  
See Payscape Direct Post API Documentation for complete notes on variables	  
Direct Post API / Documentation / Transaction Variables
http://payscape.com/developers/direct-post-api.php
	  
See the Payscape CakePHP Developers Suite for examples of each of the methods.
	  
1/15/2014
	  
	