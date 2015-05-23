<?php
	$order_id = rand(0, 1000);
?>
<!DOCTYPE html>
<html>
	<head>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
		<title>Test Invoice</title>
	</head>	
	
	<body>
		<h1>Invoice Preview Page - <?php echo $order_id; ?></h1>	
		<table border="1" class="table">
			<thead>
				<th>S/N</th>
				<th>Item</th>
				<th>Qty</th>
				<th>Unit Price</th>
				<th>Total</th>
			</thead>
			
			<tbody>
				<tr>
					<td>1.</td>
					<td>HP Laptop</td>
					<td>2</td>
					<td>70,000</td>
					<td>140,000</td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Microsoft Mouse</td>
					<td>3</td>
					<td>5000</td>
					<td>15,000</td>
				</tr>
				<tr>
					<td>3.</td>
					<td>HP Keyboard</td>
					<td>2</td>
					<td>2,500</td>
					<td>5,000</td>
				</tr>
				<tr>
					<td> </td>
					<td> </td>
					<td> </td>
					<td><b>Total</b></td>
					<td>160,000</td>
				</tr>
				<tr>
					<td> </td>
					<td> </td>
					<td> </td>
					<td> </td>
					<td><a href="process.php?order_id=<?php echo $order_id; ?>"><button class="btn">Pay with InterSwitch</button></a></td>
				</tr>
			</tbody>
		</table>
		
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>