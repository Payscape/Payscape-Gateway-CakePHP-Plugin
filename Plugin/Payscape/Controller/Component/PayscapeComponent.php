<?php 
class PayscapeComponent extends Component
{
	/*
	 * Payscape Direct Post API CakePHP Plugin v3.0
	 * 
	 * Edit userid: replace with your User ID from your Payscape account
	 * Edit userpass: replace with your Password from your Payscape account
	 * 
	 * Place this Plugin in your app/Plugin directory.
	 * Load the Plugin in your Config/bootstrap file. 
	 * 
	 * CakePlugin::load('Payscape');
	 * 
	 * Include the Payscape Component in your Controller 
	 * public $components = array('Paginator', 'Session', 'Payscape.Payscape');
	 * 
	 *  /webroot/crt/cacert.pem is included so that you may use cURL. 
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

	const url 		= 'https://secure.payscapegateway.com/api/transact.php';

	const userid 	= 'demo'; 					//Replace with your UserID from Payscape.com
	const userpass	= 'password';				//Replace with your Password from Payscape.com

	

	/* send using the Cake HTTPSocket */	
	protected function sendHTTPSocket($trans){
		$query['ipaddress'] = $_SERVER["REMOTE_ADDR"];
		$query['username'] = self::userid;
		$query['password'] = self::userpass;
		
		App::uses('HttpSocket', 'Network/Http');
		$HttpSocket = new HttpSocket();
		return $HttpSocket->post(self::url, $trans);
	}// sendHTTPSocket
	
	/* send using cURL */
	protected function sendCURL($trans){
	
		$trans['username'] = self::userid;
		$trans['password'] = self::userpass;
	
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $trans);
		curl_setopt($ch, CURLOPT_REFERER, "");
	
		/* gateway SSL certificate options for Apache on Windows */
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			
		curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/crt/cacert.pem");
	
		$outcome = curl_exec($ch);
	
		/* display cURL errors */
		if(curl_errno($ch)){
			die('Could not send request: ' .curl_error($ch));
			exit();
		}
	
		curl_close($ch);
		unset($ch);
			
		return $outcome;
	
	}// sendCURL
	
	
	protected function send($trans){
		
		$trans['username'] = self::userid;
		$trans['password'] = self::userpass;
		
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $trans);
		curl_setopt($ch, CURLOPT_REFERER, "");
		
			/* gateway SSL certificate options for Apache on Windows */
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			
		curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/crt/cacert.pem"); 
		
		$outcome = curl_exec($ch);
				
				/* for testing cURL */			
				if(curl_errno($ch)){
					die('Could not send request: ' .curl_error($ch));
					exit();
				}
				

		curl_close($ch);
		unset($ch);
			
		return $outcome;
		
	}
	
	public function Sale($incoming=null){
			
		$time = gmdate('YmdHis');
		$type = 'sale';
		
		$amount = (isset($incoming['amount']) ? $incoming['amount'] : '');
		
		$order_id = (isset($incoming['order_id']) ? $incoming['order_id'] : '');
		
		$hash = md5($order_id|$amount|$time|self::key);
		$payment = (isset($incoming['payment']) ? $incoming['payment'] : '');
		
		if($payment=='check'){
			$required = array('checkname', 'checkaba', 'checkaccount', 'account_holder_type', 'account_type', 'sec_code', 'amount');
		} else {
			$required = array('ccnumber', 'ccexp', 'amount');
		}	
		
	if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
	
		
		$transactiondata = array();
		$transactiondata['type'] = $type;
		$transactiondata['time'] = $time;


		if($payment=='check'){
			$transactiondata['checkname'] = (isset($incoming['checkname']) ? $incoming['checkname'] : '');
			$transactiondata['checkaba'] = (isset($incoming['checkaba']) ? $incoming['checkaba'] : '');
			$transactiondata['checkaccount'] = (isset($incoming['checkaccount']) ? $incoming['checkaccount'] : '');				
			$transactiondata['account_holder_type'] = (isset($incoming['account_holder_type']) ? $incoming['account_holder_type'] : '');
			$transactiondata['account_type'] = (isset($incoming['account_type']) ? $incoming['account_type'] : '');
			$transactiondata['sec_code'] = 'WEB';
			$transactiondata['payment'] = 'check';
		} else {
			$transactiondata['ccnumber'] = (isset($incoming['ccnumber']) ? $incoming['ccnumber'] : '');
			$transactiondata['ccexp'] = (isset($incoming['ccexp']) ? $incoming['ccexp'] : '');
			$transactiondata['cvv'] = (isset($incoming['cvv']) ? $incoming['cvv'] : '');
		}
		
		/* user supplied required data */
		
		$transactiondata['amount'] = (isset($incoming['amount']) ? $incoming['amount'] : '');
		
		/* user supplied optional data */
		
		$transactiondata['firstname'] = (isset($incoming['firstname']) ? $incoming['firstname'] : '');
		$transactiondata['lastname'] = (isset($incoming['lastname']) ? $incoming['lastname'] : '');
		$transactiondata['company'] = (isset($incoming['company']) ? $incoming['company'] : '');
		$transactiondata['address1'] = (isset($incoming['address1']) ? $incoming['address1'] : '');
		$transactiondata['city'] = (isset($incoming['city']) ? $incoming['city'] : '');
		$transactiondata['state'] = (isset($incoming['state']) ? $incoming['state'] : '');
		$transactiondata['zip'] = (isset($incoming['zip']) ? $incoming['zip'] : '');
		$transactiondata['country'] = (isset($incoming['country']) ? $incoming['country'] : '');
		$transactiondata['phone'] = (isset($incoming['phone']) ? $incoming['phone'] : '');
		$transactiondata['fax'] = (isset($incoming['fax']) ? $incoming['fax'] : '');
		$transactiondata['email'] = (isset($incoming['email']) ? $incoming['email'] : '');
		$transactiondata['cvv'] = (isset($incoming['cvv']) ? $incoming['cvv'] : '');
		$transactiondata['ipaddress'] = $_SERVER["REMOTE_ADDR"];
		

		$response = self::send($transactiondata);
		
		parse_str($response, $result_array);
		
		return $result_array();


			} else {
				
		    $response['Message'] = 'Required Values Are Missing';
		    $response['error'] = 1;
			return $response;
		}// count array
		
		
		
	}// Sale
	
	
	public function Auth($incoming=null){

		$time = gmdate('YmdHis');
		$type = 'auth';
		
		$amount = (isset($incoming['amount']) ? $incoming['amount'] : '');
		
		$order_id = (isset($incoming['order_id']) ? $incoming['order_id'] : '');
		
		$hash = md5($order_id|$amount|$time|self::key);
		$payment = 'creditcard';
		
			$required = array('ccnumber', 'ccexp', 'amount');
		
		if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
		
		
			$transactiondata = array();
			$transactiondata['type'] = $type;
			$transactiondata['time'] = $time;
	
		
			/* user supplied required data */
			$transactiondata['ccnumber'] = (isset($incoming['ccnumber']) ? $incoming['ccnumber'] : '');
			$transactiondata['ccexp'] = (isset($incoming['ccexp']) ? $incoming['ccexp'] : '');				
			$transactiondata['amount'] = (isset($incoming['amount']) ? $incoming['amount'] : '');
		
			/* user supplied optional data */
			$transactiondata['firstname'] = (isset($incoming['firstname']) ? $incoming['firstname'] : '');
			$transactiondata['lastname'] = (isset($incoming['lastname']) ? $incoming['lastname'] : '');
			$transactiondata['company'] = (isset($incoming['company']) ? $incoming['company'] : '');
			$transactiondata['address1'] = (isset($incoming['address1']) ? $incoming['address1'] : '');
			$transactiondata['city'] = (isset($incoming['city']) ? $incoming['city'] : '');
			$transactiondata['state'] = (isset($incoming['state']) ? $incoming['state'] : '');
			$transactiondata['zip'] = (isset($incoming['zip']) ? $incoming['zip'] : '');
			$transactiondata['country'] = (isset($incoming['country']) ? $incoming['country'] : '');
			$transactiondata['phone'] = (isset($incoming['phone']) ? $incoming['phone'] : '');
			$transactiondata['fax'] = (isset($incoming['fax']) ? $incoming['fax'] : '');
			$transactiondata['email'] = (isset($incoming['email']) ? $incoming['email'] : '');
			$transactiondata['cvv'] = (isset($incoming['cvv']) ? $incoming['cvv'] : '');
			$transactiondata['ipaddress'] = $_SERVER["REMOTE_ADDR"];
				
		$response = self::send($transactiondata);
		
		parse_str($response, $result_array);
		
		return $result_array();
		
			
		
		} else {
		
			$response['Message'] = 'Required Values Are Missing';
			$response['error'] = 1;
			return $response;
		}// count array
	}// Auth
	
	public function Credit($incoming=null){

		$time = gmdate('YmdHis');
		$type = 'credit';
		
		$amount = (isset($incoming['amount']) ? $incoming['amount'] : '');
		$order_id = (isset($incoming['order_id']) ? $incoming['order_id'] : '');
		$payment = (isset($incoming['payment']) ? $incoming['payment'] : '');

			$required = array('type', 'transactionid');
		
		if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
			

				

			$transactiondata = array();
			// required fields
			$transactiondata['type'] = 'credit';
			$transactiondata['amount'] = $amount;
			$transactiondata['transactionid'] = (isset($incoming['transactionid']) ? $incoming['transactionid'] : '');
			$transactiondata['time'] = $time;
			
			// optional fields for database record

			$transactiondata['firstname'] = (isset($incoming['firstname']) ? $incoming['firstname'] : '');
			$transactiondata['lastname'] = (isset($incoming['lastname']) ? $incoming['lastname'] : '');
			$transactiondata['company'] = (isset($incoming['company']) ? $incoming['company'] : '');
			$transactiondata['address1'] = (isset($incoming['address1']) ? $incoming['address1'] : '');
			$transactiondata['city'] = (isset($incoming['city']) ? $incoming['city'] : '');
			$transactiondata['state'] = (isset($incoming['state']) ? $incoming['state'] : '');
			$transactiondata['zip'] = (isset($incoming['zip']) ? $incoming['zip'] : '');
			$transactiondata['country'] = (isset($incoming['country']) ? $incoming['country'] : '');
			$transactiondata['phone'] = (isset($incoming['phone']) ? $incoming['phone'] : '');
			$transactiondata['fax'] = (isset($incoming['fax']) ? $incoming['fax'] : '');
			$transactiondata['email'] = (isset($incoming['email']) ? $incoming['email'] : '');
			$transactiondata['ipaddress'] = $_SERVER["REMOTE_ADDR"];
		
			return self::send($transactiondata);
		} else {		
			$response['Message'] = 'Required Values Are Missing';
			$response['error'] = 1;
			return $response;
		}// count array
	}// Credit
	
public function ValidateCreditCard($incoming=null){

	$key = self::key;
	$time = gmdate('YmdHis');
	$type = 'validate';
	
	$response = array();

	$required = array('type', 'ccnumber', 'ccexp');
	

	if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
		$transactiondata = array();
		$transactiondata['type'] = $type;

		/* user supplied required data */
		
		$transactiondata['ccexp'] = (isset($incoming['ccexp']) ? $incoming['ccexp'] : '');
		$transactiondata['ccnumber'] = (isset($incoming['ccnumber']) ? $incoming['ccnumber'] : '');

		/* user supplied optional data */
		$transactiondata['cvv'] = (isset($incoming['cvv']) ? $incoming['cvv'] : '');
		
		$transactiondata['firstname'] = (isset($incoming['firstname']) ? $incoming['firstname'] : '');
		$transactiondata['lastname'] = (isset($incoming['lastname']) ? $incoming['lastname'] : '');
		$transactiondata['company'] = (isset($incoming['company']) ? $incoming['company'] : '');
		$transactiondata['address1'] = (isset($incoming['address1']) ? $incoming['address1'] : '');
		$transactiondata['city'] = (isset($incoming['city']) ? $incoming['city'] : '');
		$transactiondata['state'] = (isset($incoming['state']) ? $incoming['state'] : '');
		$transactiondata['zip'] = (isset($incoming['zip']) ? $incoming['zip'] : '');
		$transactiondata['country'] = (isset($incoming['country']) ? $incoming['country'] : '');
		$transactiondata['phone'] = (isset($incoming['phone']) ? $incoming['phone'] : '');
		$transactiondata['fax'] = (isset($incoming['fax']) ? $incoming['fax'] : '');
		$transactiondata['email'] = (isset($incoming['email']) ? $incoming['email'] : '');
		$transactiondata['orderid'] = (isset($incoming['orderid']) ? $incoming['orderid'] : '');

		$response = self::send($transactiondata);
		
		parse_str($response, $result_array);
		
		return $result_array();

	} else {
		$response['Message'] = 'Required Values Are Missing';
		$response['error'] = 1;
		return $response;
	}
}// end ValidateCreditCard()
	
	
	public function Capture($incoming=null){
		
			$type = 'capture';
		
		
			$required = array('type', 'transactionid');
		
			if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
				$transactiondata = array();
				$transactiondata['type'] = 'capture';
				$transactiondata['transactionid'] = (isset($incoming['transactionid']) ? $incoming['transactionid'] : '');
		
					$response = self::send($transactiondata);
					
					parse_str($response, $result_array);
					
					return $result_array();
		
			} else {
				$response['Message'] = 'Required Values <strong>type or transactionid</strong> Are Missing';
				$response['error'] = 1;
				return $response;
			}	
}// Capture
			
	public function Void($incoming=null){
		
		$type = 'void';
		
		$required = array('type', 'transactionid');
		
		if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
			$transactiondata = array();
			$transactiondata['type'] = 'void';
			$transactiondata['transactionid'] = (isset($incoming['transactionid']) ? $incoming['transactionid'] : '');
		
				$response = self::send($transactiondata);
				
				parse_str($response, $result_array);
				
				return $result_array();
		
		} else {
			$response['Message'] = $response['Message'] = 'Required Values <strong>type or transactionid</strong> Are Missing';
			$response['error'] = 1;
			return $response;
		}
		
}// Void
	
	public function Refund($incoming=null){
		
		$type = 'refund';

		$required = array('type', 'transactionid');
		
		if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {
			$transactiondata = array();
			
			$transactiondata['type'] = 'refund';
			$transactiondata['transactionid'] = (isset($incoming['transactionid']) ? $incoming['transactionid'] : '');
				
			// Optional, used only if you are making a partial refund.
			if(isset($incoming['amount'])){
				$transactiondata['amount'] = (isset($incoming['amount']) ? $incoming['amount'] : '');
			}
		
		
				$response = self::send($transactiondata);
				
				parse_str($response, $result_array);
				
				return $result_array();
		
		} else {
			$response['Message'] = 'Required Values <strong>type or transactionid</strong> Are Missing';
			$response['error'] = 1;
			return $response;
		}
		
}// Refund
	
	
	public function Update($incoming=null){
		$type = 'update';
		
		$required = array('type', 'transactionid');
		
		if(count(array_intersect_key(array_flip($required), $incoming)) === count($required)) {

			$transactiondata = array();				
			$transactiondata['type'] = $type;
			$transactiondata['transactionid'] = (isset($incoming['transactionid']) ? $incoming['transactionid'] : '');
				
			/* optional fields */
			$transactiondata['tracking_number'] = (isset($incoming['tracking_number']) ? $incoming['tracking_number'] : '');
			$transactiondata['shipping_carrier'] = (isset($incoming['shipping_carrier']) ? $incoming['shipping_carrier'] : '');
			$transactiondata['orderid'] = (isset($incoming['orderid']) ? $incoming['orderid'] : '');
		
				$response = self::send($transactiondata);
				
				parse_str($response, $result_array);
				
				return $result_array();
			
		} else {
			$response['Message'] = 'Required Values <strong>type or transactionid</strong> Are Missing';
			$response['error'] = 1;
			return $response;
		}
		
}// Update
	
	
}// end PayscapeComponent