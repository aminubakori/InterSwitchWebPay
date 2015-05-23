<?php
/*******************************************************************************************************
 * Main InterSwitch Payment Class
 * @author David Carr - aminuibakori@live.com - http://www.aminubakori.com
 * @version 1.0
 * @date May 22, 2015
 * @license Open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT)
 * @license The MIT License (MIT)
*			Copyright (c) <2015> <Aminu Ibrahim Bakori>
*			
*			Permission is hereby granted, free of charge, to any person obtaining a copy
*			of this software and associated documentation files (the "Software"), to deal
*			in the Software without restriction, including without limitation the rights
*			to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*			copies of the Software, and to permit persons to whom the Software is
*			furnished to do so, subject to the following conditions:
*			
*			The above copyright notice and this permission notice shall be included in
*			all copies or substantial portions of the Software.
*			
*			THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*			IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*			FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*			AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*			LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*			OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
*			THE SOFTWARE.
 *******************************************************************************************************/ 
	class InterSwitchWebPay{
		private $testurl;
		private $liveurl;
		private $redirect_url;
		private $product_id;
		private $pay_item_id;
		private $mac_key;
		private $testmode;
		private $form_fields;
		
		/*
		 * @param numeric $product_id  InterSwitch Product ID
		 * @param numeric $pay_item_id InterSwitch Pay Item ID
		 * @param string $mac_key      InterSwitch API Mac Key
		 * @param boolean $testmode	   API to use
		 */
		public function __construct($product_id, $pay_item_id, $mac_key, $testmode = true) {
			//Define Default Varibales
			$this->testurl 				= 'https://stageserv.interswitchng.com/test_paydirect/pay';
			$this->liveurl 				= 'https://webpay.interswitchng.com/paydirect/pay';
        	
			//Define InterSwitch Payment Varibales
			$this->product_id			= $product_id;
			$this->pay_item_id			= $pay_item_id;
			$this->mac_key				= $mac_key;
			
			//Define User Set Varibales
			$this->testmode				= $testmode;
			
			// Load the form fields.
			$this->init_form_fields();
		}
	
		/**
	     * Initialise Gateway Settings Form Fields
	    **/
		private function init_form_fields() {
			$this->form_fields = array(
				'product_id' => array(
								'title' 		=> 'Product ID',
								'type' 			=> 'text',
								'description' 	=> 'Product Identifier for PAYDirect.' ,
								'default' 		=> '',
                    			'desc_tip'      => false
							),
				'pay_item_id' => array(
								'title' 		=> 'Pay Item ID',
								'type' 			=> 'text',
								'description' 	=> 'PAYDirect Payment Item ID' ,
								'default' 		=> '',
                    			'desc_tip'      => false
							),
				'mac_key' => array(
								'title' 		=> 'Mac Key',
								'type' 			=> 'text',
								'description' 	=> 'Your MAC Key' ,
								'default' 		=> '',
                    			'desc_tip'      => false
							),
				'testing' => array(
								'title'       	=> 'Gateway Testing',
								'type'        	=> 'title',
								'description' 	=> '',
							),
				'testmode' => array(
							'title'       		=> 'Test Mode',
							'type'        		=> 'checkbox',
							'label'       		=> 'Enable Test Mode',
							'default'     		=> 'no',
							'description' 		=> 'Test mode enables you to test payments before going live. <br />If you ready to start receving payment on your site, kindly uncheck this.',
				)
			);
		}
		
		/**
		 * Get Webpay Args for passing to Interswitch.
		 * @param string $redirect_url   Return URL from InterSwitch
		 * @param numeric $order_id      Order ID
		 * @param numeric $order_total   Amount to be paid
		 * @param string $customer_fname Customer First Name
		 * @param string $customer_lname Customer Last Name
		 * @return array return the webpay args
		**/
		private function get_webpay_args($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname) {
			$order_total    = $order_total * 100;

			$product_id 	= $this->product_id;
			$pay_item_id 	= $this->pay_item_id;
			$product_id 	= $this->product_id;
			$mac_key 		= $this->mac_key;

            $this->redirect_url = $redirect_url;

			$txn_ref 		= uniqid();
			$txn_ref 		= $txn_ref.'_'.$order_id;

        	$customer_name	= $customer_fname. ' ' . $customer_lname;

			$hash 			= $txn_ref.$product_id.$pay_item_id.$order_total.$redirect_url.$mac_key;
			$hash 			= hash("sha512", $hash);

			// webpay Args
			$webpay_args = array(
				'product_id' 			=> $product_id,
				'amount' 				=> $order_total,
				'currency' 				=> 566,
				'site_redirect_url' 	=> $redirect_url,
				'txn_ref' 				=> $txn_ref,
				'hash' 					=> $hash,
				'pay_item_id' 			=> $pay_item_id,
				'cust_name'				=> $customer_name,
				'cust_name_desc'		=> 'Customer Name',
				'cust_id'				=> $txn_ref,
				'cust_id_desc'			=> 'Transaction Reference',
			);

			return $webpay_args;
		}
		
		/**
		 * Generate the Webpay Payment button link.
		 * @param string $redirect_url   Return URL from InterSwitch
		 * @param numeric $order_id      Order ID
		 * @param numeric $order_total   Amount to be paid
		 * @param string $customer_fname Customer First Name
		 * @param string $customer_lname Customer Last Name
		 * @return string return the payment form
	    **/
	    private function generate_webpay_form($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname) {
			if ( $this->testmode = true ) {
        		$webpay_adr = $this->testurl;
			} else {
				$webpay_adr = $this->liveurl;
			}

			$webpay_args = $this->get_webpay_args($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname);
			
			$webpay_args_array = array();

			foreach ($webpay_args as $key => $value) {
				$webpay_args_array[] = '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
			}
			
			return '<form action="' . $webpay_adr . '" method="post" id="webpay_payment_form" target="_top">
					' . implode( '', $webpay_args_array ) . '
					<!-- Button Fallback -->
					<div class="payment_buttons">
						<input type="submit" class="button alt" id="submit_webpay_payment_form" value="Pay via Interswitch Webpay" />
					</div>
					<script type="text/javascript">
						$("document").ready(function () {
							$("#submit_webpay_payment_form").click();
							$(".payment_buttons").hide();
						});
					</script>
				</form>';
		}
		
		/**
	     * Output for the order received page.
		 * @param string $redirect_url   Return URL from InterSwitch
		 * @param numeric $order_id      Order ID
		 * @param numeric $order_total   Amount to be paid
		 * @param string $customer_fname Customer First Name
		 * @param string $customer_lname Customer Last Name
	    **/
		public function make_webpay_payment($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname) {
			echo '<p> Thank you - your order is now pending payment. Click the button below and you should be automatically redirected to Interswitch to make payment.</p>';
			echo $this->generate_webpay_form($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname);
		}	
		
		/**
		 * Verify a successful Payment.
		 * @param numeric $order_id      Order ID
		 * @param numeric $order_total   Amount expected to be paid
		 * @return array return the payment response
		**/
		public function check_webpay_response( $order_no,  $order_total){
			$result = array();
			if( isset( $_POST['txnref'] ) ) {
				$txnref 		= $_POST['txnref'];
				$order_details 	= explode('_', $txnref);
				$txn_ref 		= $order_details[0];
				$order_id 		= $order_details[1];
				
				if($order_no == $order_id) {
					$order_id 		= (int) $order_id;
			        $total          = $order_total * 100;
	
			        $response       = $this->webpay_transaction_details( $txnref, $total);
					$response = json_decode($response, true);
					$response_code 	= $response['ResponseCode'];
					$amount_paid    = $response['Amount'] / 100;
					$response_desc  = $response['ResponseDescription'];
					$result['response'] = $response;
					//process a successful transaction
					if( $response_code == '00' ){
						$payment_ref = $response['PaymentReference'];
	
						// check if the amount paid is equal to the order amount.
						if($order_total != $amount_paid) {
							//Error Note
							$message = 'Thank you for shopping with us.<br />Your payment transaction was successful, but the amount paid is not the same as the total order amount.<br />Your order is currently on-hold.<br />Kindly contact us for more information regarding your order and payment status.<br />Transaction Reference: '.$txnref.'<br />Payment Reference: '.$payment_ref;
							$message_type = 'notice';
						}else {
							$message = 'Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.<br />Transaction Reference: '.$txnref.'<br />Payment Reference: '.$payment_ref;
							$message_type = 'success';
		                }
					}else {
						//process a failed transaction
		            	$message = 	'Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.<br />Reason: '. $response_desc.'<br />Transaction Reference: '.$txnref;
						$message_type = 'error';
					}
				}else {
					$message = 	'Invalid Order ID.';
					$message_type = 'error';
				}

			}else{
            	$message = 	'Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.';
				$message_type = 'error';
			}

            $result['message']	= $message;
            $result['message_type'] = $message_type;
			
			return $result;
		}
		
		/**
	 	* Query a transaction details
		 * @param string $txnref       Unique Transaction Ref. ID
		 * @param numeric $order_total Amount expected to be paid
		 * @return JSON return the transaction details
	 	**/
		public function webpay_transaction_details($txnref, $total){
			$product_id 	= $this->product_id;
			$mac_key        = $this->mac_key;

			if ( $this->testmode == true ) {
        		$query_url = 'https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json';
			} else {
				$query_url = 'https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.json';
			}

			$url 	= "$query_url?productid=$product_id&transactionreference=$txnref&amount=$total";

			$hash 	= $product_id.$txnref.$mac_key;
			$hash 	= hash("sha512", $hash);

			$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';

			$request_headers = array();
			$request_headers[] = 'User-Agent: '. $User_Agent;
			$request_headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
			$request_headers[] = 'Hash: '. $hash;
		    
		    // Initialize cURL session
			$ch = curl_init($url);
	
			// Option to Return the Result, rather than just true/false
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
			// Set Request Headers 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	
			// Perform the request, and save content to $result
			$result = curl_exec($ch);
	
			// Close the cURL resource, and free up system resources!
			curl_close($ch);
	
		    return $result;	
		}
	}
?>