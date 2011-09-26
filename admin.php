<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Admin Page</title>
	<!-- Date: 2011-06-22 -->
	<style type="text/css">
	
	#body {width: 1000px; border: solid black 1px; margin: 0px auto 0px auto;}
	ul, li {margin: 10px; padding: 10px; list-style-type: none;}
	
	</style>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	
	$(document).ready(function() {
			


			
	});
	</script>



</head>
<body>
<div id="allcontent">

<div id="body">
	<center>
		<h1>B&B Admin Page</h1>
		<hr>
		<h3>What would you like to do?</h3>
		
		<ul>
			<li><a href="admincalendar.php">View the Calendar in Administrator Mode</a></li>
			<li><a href="bookingslist.php">View and Edit Bookings</a></li>
			<li><a href="prices.php">View and Edit Prices</a></li>
		</ul>
	</center>
</div>



</div>
</body>
</html>
