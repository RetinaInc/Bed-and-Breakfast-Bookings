<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');

mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// get post data
$current_year = mysql_real_escape_string($_POST['current_year']);
$datestring = mysql_real_escape_string($_POST['datestring']);

//convert date to day of year

$dt = strtotime($datestring);
$day_of_year = date('z', $dt);

$query = "select booking_id from unavailable where year = $current_year and day_of_year = $day_of_year";
$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) > 0) {
	
	$row = mysql_fetch_row($result);
	$booking_id = $row[0];
} else {
	echo "Couldn't fetch the row!";
	exit;
}

$query = "select * from bookings where id = $booking_id";
$result = mysql_query($query) or die(mysql_error());

	while ($row = mysql_fetch_array($result)) {
		$booking_id = $row['id'];
		$booking_created_at = $row['booking_created_at'];
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
				<center>
				<h2>Booking Information</h2>
				<table>
					<tr>
						<th>Booking ID:</th><td id="$booking_id">$booking_id</td>
					</tr><tr>
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
					</tr><tr>
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
				</center>
EOF;

		echo $output;
}

