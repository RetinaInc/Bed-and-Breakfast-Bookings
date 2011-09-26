<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// if doing a default price update

if (isset($_POST['newprice'])) {
	
// get post data

	$newprice = mysql_real_escape_string($_POST['newprice']);

// update db
	if (is_numeric($newprice)) {
		$query = "update settings set value = '$newprice' where setting = 'default_price'";
		$result = mysql_query($query) or die(mysql_error());

		if (mysql_affected_rows() >= 1){
			echo "<p>New price set: $newprice</p>";
		} else {
			echo "<p>Could not update database</p>";
		}
	} else {
		echo "Input data is not a number";
	}
// else if unavailable days are to be set

} 

if (isset($_POST['startdate']) && isset($_POST['enddate']) && isset($_POST['nondefaultprice']) ) {
	$startdate = mysql_real_escape_string($_POST['startdate']);
	$enddate = mysql_real_escape_string($_POST['enddate']);
	$nondefaultprice = mysql_real_escape_string($_POST['nondefaultprice']);	
	
//	echo $startdate;
//	echo $enddate;
//	echo $nondefaultprice;
	
	//convert start and end dates to day of year

	$sd = strtotime($startdate);
	$start_day_of_year = date('z', $sd);
	
	$ed = strtotime($enddate);
	$end_day_of_year = date('z', $ed);
	
	$non_default_price_days = Array();

	for ($i = $start_day_of_year; $i < ($end_day_of_year + 1); $i++) {
		$non_default_price_days[] = $i;
		echo "added $i to array<br />";
	}

	foreach ($non_default_price_days as $day) {
		if ($day > 364) {
			$year = (date('Y')) + 1;
			$day = ($day - 365);
		} else {
			$year = date('Y');
		}

		$query = "insert into non_default_price_days (id, year, day_of_year, price) values ('', '$year', '$day', '$nondefaultprice')";
		$result = mysql_query($query) or die(mysql_error());

		if (mysql_affected_rows() >= 1) {
			echo "Added 1 day to non default price table<br />";
		} else {
			echo "Something went wrong adding the prices to the db";
		}	
	}
	
} 
?>