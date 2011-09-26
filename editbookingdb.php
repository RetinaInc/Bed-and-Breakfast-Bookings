<?php

# This script first gets all the POST data, then DELETES the booking (found by the booking_id) from the 'bookings' table, THEN it removes all entries from the 'unavailable' table that have that booking_id. It then uses code similar to newbooking.php to add the booking with the new information as if it were a new booking, but with the same booking_id.


// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');

mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// get post data

// $user_id = $_POST['user_id'];
$booking_id = mysql_real_escape_string($_POST['booking_id']);
$booking_created_at = mysql_real_escape_string($_POST['booking_created_at']);
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
 
// if ($length_of_stay > 7 OR $length_of_stay < 1) {
// 	exit("Length is invalid. Exiting script...");
// }

// Delete old booking from bookings table... 

$query = "delete from bookings where id={$booking_id} limit 1";
$result = mysql_query($query) or die(mysql_error());

if (mysql_affected_rows() == 1) {
	echo "Booking " . $booking_id . " has been deleted.<br />";
} else {
	echo "Something went wrong :( </br>";
}
// ... and all entries with that booking id from unavailable table

$query = "delete from unavailable where booking_id={$booking_id}";
$result = mysql_query($query) or die(mysql_error());

if (mysql_affected_rows() >= 1) {
	echo "All days booked under Booking " . $booking_id . " are now available.<br />";
} else {
	echo "Something went wrong while deleting the booked days.<br />";
}

 
// now add booking with edited data

$query = "insert into bookings (id, adults, children, arrivaldate, length_of_stay, payment_method, firstname, lastname, email, phone, add1, add2, city, state, postcode, country, booking_created_at, booking_edited_at) values ('$booking_id', '$adults', '$children', '$arrivaldate', '$length_of_stay', '$payment_method', '$firstname', '$lastname', '$email', '$phone', '$add1', '$add2', '$city', '$state', '$postcode', '$country', '$booking_created_at', NOW() )";
$result = mysql_query($query) or die(mysql_error());
$booking_id = mysql_insert_id();

if (mysql_affected_rows() >= 1){
	echo "<p>Booking made for " . $firstname . " " . $lastname . " arriving on ". $arrivaldate . " and staying for " . $length_of_stay. " nights!</p>";
}

//convert arrival date to day of year

$dt = strtotime($arrivaldate);
$arrival_day_of_year = date('z', $dt);

$unavailable_days_of_year = Array();

for ($i = $arrival_day_of_year; $i < ($arrival_day_of_year + $length_of_stay); $i++) {
	$unavailable_days_of_year[] = $i;
}

// print_r($unavailable_days_of_year);
echo "<br />";

foreach ($unavailable_days_of_year as $day) {
	if ($day > 364) {
		$year = (date('Y')) + 1;
		$day = ($day - 365);
	} else {
		$year = date('Y');
	}
		
	$query = "insert into unavailable (id, booking_id, year, day_of_year) values ('', '$booking_id', '$year', '$day')";
	$result = mysql_query($query) or die(mysql_error());

	if (mysql_affected_rows() >= 1) {
		echo "Added 1 day to unavailable table<br />";
	}	
}

?>
