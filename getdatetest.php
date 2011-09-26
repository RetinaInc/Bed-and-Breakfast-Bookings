<?php
date_default_timezone_set('Asia/Tokyo');

//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Date testing</title>
	<link type="text/css" href="css/start/jquery-ui-1.8.13.custom.css" rel="Stylesheet" />	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript">
	
		$(document).ready(function() {
		
			$('#startdate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#startdate').val(dateText); } });
			$('#enddate').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { $('#enddate').val(dateText); } });
		
			$('#multinondefaultpricesubmit').click(function() {
				
				var startdate = $('#startdate').attr("value");
				var enddate = $('#enddate').attr("value");
				var newprice = $('#multinondefaultprice').attr("value");
				
				$.post ('multidate.php',
				{ startdate: startdate, enddate: enddate, newprice: newprice },
					function(data) {
						$('#container').html(data);
					} 
				);
				
			});
		
		});
			
	</script>
</head>
<body>
	<div>
<?php 
$today = getdate();
echo "<p>";
print_r($today);
echo "</p><p>";
echo $today['weekday'] . "</p><p>";
echo "This year, Christmas Day will fall on a " . date("l", mktime(0, 0, 0, 12, 25, 2011)) . ".</p><p>";
echo "August the 6th, 2011 is a " . date("l", mktime(0, 0, 0, 8, 6, 2011)) . ".</p><p>";
$christmas = mktime(0,0,0,12,25,2011);
echo "Christmas day is day number " . date("z", $christmas) . " of the year<br />";
echo "In 2011, It will fall on a " . date("l", $christmas) . ".";
echo "</p><p>";

if (strtolower(date("l", $christmas)) == "sunday") {
	echo "Yes, it's a sunday!";
} else {
	echo "Not a sunday";
}

echo "</p>";

?>
</div>

<div style="border: solid 1px black; padding: 10px;">

	<p>Start date: <input type="text" id="startdate" value="Click to select"></p>
	<p>End date: <input type="text" id="enddate" value="Click to select"></p>
	<p>New price: <input type="text" id="multinondefaultprice"></p>
	<input type="button" id="multinondefaultpricesubmit" value="Change every Fri, Sat & Sun between these dates to this price!">

</div>

<div id="container">
</div>

</body>
</html>