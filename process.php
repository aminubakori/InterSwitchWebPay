<!DOCTYPE html>
<html>
	<head>
		<title>Test Invoice</title>
	</head>	
	
	<body>	
		<?php
			require 'InterSwitchPayment.php';
			
			$product_id = '4220';
			$pay_item_id = '101';
			$mac_key = '199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB';
			$testmode = true;
			
			$InterSwitch = new InterSwitchWebPay($product_id, $pay_item_id, $mac_key, $testmode);
			
			if(isset($_GET['order_id'])) {
				echo "<h1>Redirecting to Payment Page</h1>";
				$order_id = $_GET['order_id'];
				$order_total = '160000';
				$customer_fname = 'Aminu';
				$customer_lname = 'Bakori';
				$redirect_url = 'http://localhost:8888/InterSwitchPayment/process.php?order_no='.$_GET['order_id'];
				$InterSwitch->make_webpay_payment($redirect_url, $order_id, $order_total, $customer_fname, $customer_lname);
			}elseif(isset($_GET['order_no'])) {
				echo "<h1>Payment Check</h1>";
				$order_id = $_GET['order_no'];
				$order_total = '160000';
				$response = $InterSwitch->check_webpay_response( $order_id,  $order_total);
				
				var_dump($response);
			}
		?>
	</body>
</html>