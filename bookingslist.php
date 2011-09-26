<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>bookingslist</title>
<style type="text/css">

	#allcontent {width: 1000px; margin: 0px auto 0px auto; padding: 10px; border: solid black 1px;}

	th, td {text-align: left; width: 150px;}
	.booking_entry {padding:0px;}
	table {border-collapse: collapse; margin: 10px 0px 0px 0px;}
	.detailed_info {margin-top: -11px;}
	table, th, td {margin: 5px; border: solid #aaa 1px;}
	th, td {padding: 5px;}
	form {display: inline; margin: 10px;}
</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function() {
			
			$('.detailed_info_button').click(function() {
			
				var booking_id = $(this).attr("id");
				$('#details_for_'+ booking_id).toggle();
			
			});
			
			// $('.editbooking').click(function() {
			// 	
			// 	var booking_id = $(this).attr("id").replace('edit', '');
			// 	var adults = $('#adults_' + booking_id).text();
			// 	var children = $('#children_' + booking_id).text();
			// 	var arrival_date = $('#arrival_date_' + booking_id).text();
			// 	var length_of_stay = $('#length_of_stay_' + booking_id).text();
			// 	var payment_method = $('#payment_method_' + booking_id).text();
			// 	var firstname = $('#firstname_' + booking_id).text();
			// 	var lastname = $('#lastname_' + booking_id).text();
			// 	var email = $('#email_' + booking_id).text();
			// 	var phone = $('#phone_' + booking_id).text();
			// 	var add1 = $('#add1_' + booking_id).text();
			// 	var add2 = $('#add2_' + booking_id).text();
			// 	var city = $('#city_' + booking_id).text();
			// 	var state = $('#state_' + booking_id).text();
			// 	var postcode = $('#postcode_' + booking_id).text();
			// 	var country = $('#country_' + booking_id).text();
			// 
			// 	$.post ('editbooking.php',
			// 	 	{ booking_id: booking_id, adults: adults, children: children, arrival_date: arrival_date, length_of_stay: length_of_stay, payment_method: payment_method, firstname: firstname, lastname: lastname, email: email, phone: phone, add1: add1, add2: add2, city: city, state: state, postcode: postcode, country: country},
			// 		function() {
			// 		window.location = "editbooking.php";
			// 		} 
			// 	);
				
				
				
		
			
					

		});
</script>

</head>
<body>
<div id="allcontent">
<a href="admin.php">Back to Admin</a>
<center><h1>Bookings</h1></center>
<hr>

<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');

mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());


// get bookings

$query = "select * from bookings order by booking_created_at asc";
$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) > 0) {
	
	while ($row = mysql_fetch_array($result)) {
		$booking_id = $row['id'];
		$adults = $row['adults'];
		$children = $row['children'];
		$arrival_date = $row['arrivaldate'];
		$length_of_stay = $row['length_of_stay'];
		$payment_method = $row['payment_method'];
		$booking_created_at = $row['booking_created_at'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$email = $row['email'];
		$phone = $row['phone'];
		$add1 = $row['add1'];
		$add2 = $row['add2'];
		$city = $row['city'];
		$state = $row['state'];
		$postcode = $row['postcode'];
		$country = $row['country'];

		$output = <<<EOF
			<div class="booking_entry" id="$booking_id">
			<h3>Booking ID: $booking_id  -   made at $booking_created_at</h3>
			<span><input type="button" class="detailed_info_button" id="$booking_id" value="Show/Hide details"><form action="editbooking.php" method="post"><input type="hidden" name="booking_id" value="$booking_id"><input type="submit" value="Edit/Delete Booking"></form></span>
				<div class="simple_info">
					<table>
						<tr>
							<th>First Name:</th><td id="firstname_$booking_id">$firstname</td>
						</tr><tr>
						 	<th>Last Name:</th><td id="lastname_$booking_id">$lastname</td>
						</tr><tr>
							<th>Adults:</th><td id="adults_$booking_id">$adults</td>
						</tr><tr>
							<th>Children:</th><td id="children_$booking_id">$children</td>
						</tr><tr>
							<th>Arriving on:</th><td id="arrival_date_$booking_id">$arrival_date</td>
						</tr><tr>
							<th>Staying for:</th><td id="length_of_stay_$booking_id">$length_of_stay nights</td>
						</tr><tr>
							<th>Payment method:</th><td id="payment_method_$booking_id">$payment_method</td>
						</tr>
					</table>
				</div>
				<div class="detailed_info" id="details_for_$booking_id" style="display:none;">	
					<table>
						<tr>
							<th>Email:</th><td id="email_$booking_id">$email</td>
						</tr><tr>
							<th>Phone:</th><td id="phone_$booking_id">$phone</td>
						</tr><tr>
							<th>Address 1:</th><td id="add1_$booking_id">$add1</td>
						</tr><tr>
							<th>Address 2:</th><td id="add2_$booking_id">$add2</td>
						</tr><tr>
							<th>City:</th><td id="city_$booking_id">$city</td>
						</tr><tr>
							<th>State:</th><td id="state_$booking_id">$state</td>
						</tr><tr>
							<th>Postcode</th><td id="postcode_$booking_id">$postcode</td>
						</tr><tr>
							<th>Country</th><td id="country_$booking_id">$country</td>
						</tr>
					</table>
				</div>	
			<hr>
			</div>
EOF;

		echo $output;

	}
} else {
	$output = "No bookings!";
	echo $output;
}


?>



</div>
</body>
</html>
