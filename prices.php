<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());

// get default price from db
$query = "select setting, value from settings where setting = 'default_price'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);
$default_price = $row[1];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Prices</title>
	<!-- Date: 2011-06-22 -->
	<link type="text/css" href="css/start/jquery-ui-1.8.13.custom.css" rel="Stylesheet" />	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function() {
			$('#newpricesubmit').click(function() {
				var newprice = parseInt($('#newpricefield').attr("value"));
				
				$.post ('pricedb.php',
				{ newprice: newprice },
					function(data) {
						$('#returndefaultpricedata').html(data);
						$('#backtoadmin1').show();
					} 
				);
				
			});
			
			$('#startdate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#startdate').val(dateText); } });
			$('#enddate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#enddate').val(dateText); } });
			$('#multistartdate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#multistartdate').val(dateText); } });
			$('#multienddate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#multienddate').val(dateText); } });
			
			$('#nondefaultpricesubmit').click(function() {
				var startdate = $('#startdate').attr("value");
				var enddate = $('#enddate').attr("value");
				var nondefaultprice = parseInt($('#nondefaultprice').attr("value"));
				
				$.post ('pricedb.php',
				{ startdate: startdate, enddate: enddate, nondefaultprice: nondefaultprice},
					function(data) {
						$('#returnnondefaultpricedata').html(data);
						$('#backtoadmin2').show();
					}
				);
			});
			
			$('#multinondefaultpricesubmit').click(function() {
				
				var multistartdate = $('#multistartdate').attr("value");
				var multienddate = $('#multienddate').attr("value");
				var multinewprice = $('#multinondefaultprice').attr("value");
				var days = $(':checked').map(function() {
					return this.id;
				}).get();
				
				
				$.post ('multidate.php',
				{ startdate: multistartdate, enddate: multienddate, newprice: multinewprice, days: days },
					function(data) {
						$('#returnmultipricedata').html(data);
						$('#backtoadmin3').show();
					} 
				);
				
			});
			
			
			
		});
	</script>
</head>
<body>
<div id="allcontent">
	
<div style="width: 800px; border: solid black 1px; margin: 10px auto 10px auto;">
	<center><h1>Prices</h1></center>
</div>

<div style="width: 800px; border: solid black 1px; margin: 10px auto 10px auto;">
	<center>
		<h2>Default price is $<?php echo $default_price; ?></h2>
		<p>Input new default price: <input type="text" id="newpricefield"></p>
		<p><input type="button" id="newpricesubmit" value="Change Price"></p>
		<p id="returndefaultpricedata"></p>
		<p id="backtoadmin" style="display: none;"><a href="admin.php">Back to the admin page</a></p>
	</center>
</div>

<div style="width: 800px; border: solid black 1px; margin: 10px auto 10px auto;">
	<center>
		<h2>Set non-default price days</h2>
		<p>Start date: <input type="text" id="startdate" value="Click to select"> End date: <input type="text" id="enddate" value="Click to select"></p>
		<p>New price: <input type="text" id="nondefaultprice"></p>
		<p><input type="button" id="nondefaultpricesubmit" value="Set non-default prices"></p>
		<p id="returnnondefaultpricedata"></p>
		<p id="backtoadmin2" style="display: none;"><a href="admin.php">Back to the admin page</a></p>
	</center>
</div>	

<div style="width: 800px; border: solid 1px black; margin: 10px auto 10px auto;">
	<center>
	<h2>Set repeating non-default price days</h2>
	<p>Start date: <input type="text" id="multistartdate" value="Click to select"></p>
	<p>End date: <input type="text" id="multienddate" value="Click to select"></p>
	<p>New price: <input type="text" id="multinondefaultprice"></p>
	<table>
		<tr><th>Every:</th><td><input type="checkbox" value="mon" id="mon" name="monday"> <label for="mon">Monday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="tue" id="tue" name="tuesday"> <label for="tue">Tuesday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="wed" id="wed" name="wednesday"> <label for="wed">Wednesday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="thu" id="thu" name="thursday"> <label for="thu">Thursday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="fri" id="fri" name="friday"> <label for="fri">Friday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="sat" id="sat" name="saturday"> <label for="sat">Saturday</label></td></tr>
		<tr><th></th><td><input type="checkbox" value="sun" id="sun" name="sunday"> <label for="sun">Sunday</label></td></tr>
	</table>
	<p><input type="button" id="multinondefaultpricesubmit" value="Change these dates' prices!"></p>
	<p id="returnmultipricedata"></p>
	<p id="backtoadmin3" style="display: none;"><a href="admin.php">Back to the admin page</a></p>
	</center>
</div>
	
</div>	
</body>
</html>
	