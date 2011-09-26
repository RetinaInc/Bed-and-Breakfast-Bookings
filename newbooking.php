<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');

mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// check for existence of required input data

// if (empty($_POST['firstname'])) {
// 	echo "Please enter your first name.";
// 	exit;
// }

// get post data


// if it's an Admin disabling reservations, only take 4 pieces of info and make the booking. Set a variable to show that an admin has done it to avoid repeating code

if ($_POST['firstname'] == "ADMIN" && $_POST['lastname'] == "ADMIN") {
	$isadmin = TRUE;
	$arrivaldate = mysql_real_escape_string($_POST['arrivaldate']);
	$length_of_stay = mysql_real_escape_string($_POST['length_of_stay']);
	$firstname = mysql_real_escape_string($_POST['firstname']);
	$lastname = mysql_real_escape_string($_POST['lastname']);

} else {
	
	if (strtotime($_POST['arrivaldate']) < date('U')) {
		echo "Can't book a day earlier than today!";
		exit;
	}
	
	$isadmin = FALSE;
	$adults = mysql_real_escape_string($_POST['adults']);
	$children = mysql_real_escape_string($_POST['children']);
	$arrivaldate = mysql_real_escape_string($_POST['arrivaldate']);
	$length_of_stay = mysql_real_escape_string($_POST['length_of_stay']);
	$payment_method = mysql_real_escape_string($_POST['payment_method']);
	$firstname = mysql_real_escape_string($_POST['firstname']);
	$lastname = mysql_real_escape_string($_POST['lastname']);
	$email = mysql_real_escape_string($_POST['email']);
	$phone = mysql_real_escape_string($_POST['phone']);
	$add1 = mysql_real_escape_string($_POST['add1']);
	$add2 = mysql_real_escape_string($_POST['add2']);
	$city = mysql_real_escape_string($_POST['city']);
	$state = mysql_real_escape_string($_POST['state']);
	$postcode = mysql_real_escape_string($_POST['postcode']);
	$country = mysql_real_escape_string($_POST['country']);
}

if ($length_of_stay > 7 OR $length_of_stay < 1) {
	exit("Length is invalid. Exiting script...");
}

 // make booking

if ($isadmin == TRUE) {
	
	$query = "insert into bookings (id, arrivaldate, length_of_stay, firstname, lastname, booking_created_at) values ('', '$arrivaldate', '$length_of_stay', '$firstname', '$lastname', NOW() )";
	$result = mysql_query($query) or die(mysql_error());
	$booking_id = mysql_insert_id();

	if (mysql_affected_rows() >= 1) {
		echo "<p>Disabled reservations for " . $arrivaldate . " and the following " . $length_of_stay. " nights!</p>";
	}
} else if ($isadmin == FALSE) {

	$query = "insert into bookings (id, adults, children, arrivaldate, length_of_stay, payment_method, firstname, lastname, email, phone, add1, add2, city, state, postcode, country, booking_created_at) values ('', '$adults', '$children', '$arrivaldate', '$length_of_stay', '$payment_method', '$firstname', '$lastname', '$email', '$phone', '$add1', '$add2', '$city', '$state', '$postcode', '$country', NOW() )";
$result = mysql_query($query) or die(mysql_error());
$booking_id = mysql_insert_id();

	if (mysql_affected_rows() >= 1) {
		echo "<p>Booking made for " . $firstname . " " . $lastname . " arriving on ". $arrivaldate . " and staying for " . $length_of_stay. " nights!</p>";
	}
}

//convert arrival date to day of year

$dt = strtotime($arrivaldate);
$arrival_day_of_year = date('z', $dt);
$year = date('Y', $dt);

$unavailable_days_of_year = Array();

for ($i = $arrival_day_of_year; $i < ($arrival_day_of_year + $length_of_stay); $i++) {
	$unavailable_days_of_year[] = $i;
}

// print_r($unavailable_days_of_year);
echo "<br />";




foreach ($unavailable_days_of_year as $day) {
	if ($day > 364) {
		$year = ($year + 1);
		$day = ($day - 365);
	} 
		
	$query = "insert into unavailable (id, booking_id, year, day_of_year) values ('', '$booking_id', '$year', '$day')";
	$result = mysql_query($query) or die(mysql_error());

	if (mysql_affected_rows() >= 1) {
		echo "Added 1 day to unavailable table<br />";
	}	
}

echo "<p>Page will refresh in 3 seconds!</p>";
?>
