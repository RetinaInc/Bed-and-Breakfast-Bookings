<?php
date_default_timezone_set('Asia/Tokyo');

//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());


// get post data

$startdate = mysql_real_escape_string($_POST['startdate']);
$enddate = mysql_real_escape_string($_POST['enddate']);
$newprice = mysql_real_escape_string($_POST['newprice']);
$days = $_POST['days'];

// find all the fri/sat/suns between the dates. also set year

$year = date("Y");

$start = strtotime($startdate);
$end = strtotime($enddate);

$monday = strtotime('monday', $start);
$tuesday = strtotime('tuesday', $start);
$wednesday = strtotime('wednesday', $start);
$thursday = strtotime('thursday', $start);
$friday = strtotime('friday', $start);
$saturday = strtotime('saturday', $start);
$sunday = strtotime('sunday', $start);

$multi_non_default_price_days = Array();

// if statements handling all possible combinations of days

if (in_array('mon', $days)) {
	while ($monday <= $end) {
			$multi_non_default_price_days[] = date("z", $monday);
			$monday = strtotime("+1 weeks", $monday);
	}
}
if (in_array('tue', $days)) {
	while ($tuesday <= $end) {
		$multi_non_default_price_days[] = date("z", $tuesday);
		$tuesday = strtotime("+1 weeks", $tuesday);
	}
}

if (in_array('wed', $days)) {
	while ($wednesday <= $end) {
		$multi_non_default_price_days[] = date("z", $wednesday);
		$wednesday = strtotime("+1 weeks", $wednesday);
	}
}


if (in_array('thu', $days)) {
	while ($thursday <= $end) {
			$multi_non_default_price_days[] = date("z", $thursday);
			$thursday = strtotime("+1 weeks", $thursday);
	}
}

if (in_array('fri', $days)) {
	while ($friday <= $end) {
			$multi_non_default_price_days[] = date("z", $friday);
			$friday = strtotime("+1 weeks", $friday);
	}
}

if (in_array('sat', $days)) {
	while ($saturday <= $end) {
		$multi_non_default_price_days[] = date("z", $saturday);
		$saturday = strtotime("+1 weeks", $saturday);
	}	
}

if (in_array('sun', $days)) {
	while ($sunday <= $end) {
		$multi_non_default_price_days[] = date("z", $sunday);
		$sunday = strtotime("+1 weeks", $sunday);
	}
}

// make neato string full of values for db entry

$str = '';
foreach ($multi_non_default_price_days as $day) {
	$str .= "('', $year, $day, $newprice),";
}
$str = substr($str, 0, -1);

$query = "insert into non_default_price_days (id, year, day_of_year, price) values " . $str . " on duplicate key update price = $newprice";
$result = mysql_query($query) or die(mysql_error());

if (mysql_affected_rows() >= 1) {
	echo "Added values to non default price table<br />";
} else {
	echo "Something went wrong adding the prices to the db";
}

?>

