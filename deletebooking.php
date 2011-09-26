<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');

mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// get post data

$booking_id = $_POST['booking_id'];

// Delete booking from bookings table... 

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
	echo "All days booked under Booking " . $booking_id . " have been deleted and are now available for booking.<br />";
} else {
	echo "Something went wrong while deleting the booked days.<br />";
}

?>