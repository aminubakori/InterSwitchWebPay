## InterSwitchWebPay
A PHP implementation of InterSwitch WebPay API

## Documentation
# Initialize
1. Include the InterSwitchWebPay class and Initialize it. You can use the following test credentials they are provided by InterSwitch for testing:
Product ID: 6325
Item ID: 101
MAC/Hash key: D6871058AE068109A74DDBF08AACEFA50EA59C0DE9010CC73B06BEA9ADD07CCA22616428C5D9C56B20683C75C2210CAE1A3147690F4B1E2BAB7FF5672D6AF7F3
Testmode: true
```php
<?php
	require 'InterSwitchWebPay.php';
	$WebPay = new InterSwitchWebPay($product_id, $pay_item_id, $mac_key, $testmode);
?>
```

# Make Payment
2. To Make Payment, provide a redirect/return url, an order id, total amount, customers first name and last name
```php
<?php
	require 'InterSwitchWebPay.php';
	$WebPay = new InterSwitchWebPay($product_id, $pay_item_id, $mac_key, $testmode);
	
	$WebPay->make_webpay_payment($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname);
?>
```

# Handle Redirection and Verify Transaction
3. To verify a transaction when returned from InterSwitch, provide the order id and total
```php
<?php
	require 'InterSwitchWebPay.php';
	$WebPay = new InterSwitchWebPay($product_id, $pay_item_id, $mac_key, $testmode);
	
	$response = $WebPay->check_webpay_response( $order_id,  $order_total);
	var_dump($response);
?>
```

# Verify Transaction
4. To verify a transaction, provide the transaction ref id and total
```php
<?php
	require 'InterSwitchWebPay.php';
	$WebPay = new InterSwitchWebPay($product_id, $pay_item_id, $mac_key, $testmode);
	
	$response = $WebPay->webpay_transaction_details($txnref, $total);
	var_dump($response);
?>
```

## Testing Details
# Merchant details WebPAY (without split)
Goldilocks Inc. - This is the demo merchant’s name and is not required anywhere. FYI only
4220            - Product ID
101             - Item ID
MAC/Hash key    - 199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB

# Merchant details WebPAY (with split)
Goldilocks Inc. - This is the demo merchant’s name and is not required anywhere. FYI only
6325            - Product ID
101             - Item ID
MAC/Hash key    - D6871058AE068109A74DDBF08AACEFA50EA59C0DE9010CC73B06BEA9ADD07CCA22616428C5D9C56B20683C75C2210CAE1A3147690F4B1E2BAB7FF5672D6AF7F3

# Demo Card details
1. Success 
Card No:   6280511000000095 
Exp. Date: Dec 2026
PIN:       0000
CVV2:      123
This is the demo card with which you would always get a successful response

2. Expired Card 
Card No:   5061020000000000003 
Exp. Date: June 2014
PIN:       1111
CVV2:      123
This is the demo card that would decline with ‘Z1′. See the response codes page for more details

3. Insufficient Funds 
Card No:   5061020000000010002 
Exp. Date: Jan 2016
PIN:       1111
CVV2:      123
This is the demo card that would return with insufficient funds

Learn More From https://connect.interswitchng.com/documentation/