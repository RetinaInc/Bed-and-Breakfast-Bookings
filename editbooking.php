<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());



//get post data
$booking_id = mysql_real_escape_string($_POST['booking_id']);

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
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Booking</title>
	<!-- Date: 2011-06-22 -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function() {
		
			var booking_id = $('#booking_id_hidden').text();
			var booking_created_at = $('#booking_created_at_hidden').text();
			var adults = $('#adults_hidden').text();
			var children = $('#children_hidden').text();
			var arrival_date = $('#arrival_date_hidden').text();
			var length_of_stay = $('#length_of_stay_hidden').text();
			var payment_method = $('#payment_method_hidden').text();
			var firstname = $('#firstname_hidden').text();
			var lastname = $('#lastname_hidden').text();
			var email = $('#email_hidden').text();
			var phone = $('#phone_hidden').text();
			var add1 = $('#add1_hidden').text();
			var add2 = $('#add2_hidden').text();
			var city = $('#city_hidden').text();
			var state = $('#state_hidden').text();
			var postcode = $('#postcode_hidden').text();
			var country = $('#country_hidden').text();
			
			function setpulldowns() {	
				// set Number of Adults pulldown menu to same as booking
				if (adults == "1") {
					$("select#adults option[value='1']").attr("selected","selected");
				} else if (adults == "2") {
					$("select#adults option[value='2']").attr("selected","selected");
				} else if (adults == "3") {
					$("select#adults option[value='3']").attr("selected","selected");
				}
			
					// set Number of Children pulldown menu to same as booking	
				if (children == "0") {
					$("select#children option[value='0']").attr("selected","selected");
				} else if (children == "1") {
					$("select#children option[value='1']").attr("selected","selected");
				} else if (children == "2") {
					$("select#children option[value='2']").attr("selected","selected");
				} else if (children == "3") {
					$("select#children option[value='3']").attr("selected","selected");
				}
				
					// set length_of_stay pulldown menu to same as booking	
				if (length_of_stay == "1") {
					$("select#length_of_stay option[value='1']").attr("selected","selected");
				} else if (length_of_stay == "2") {
					$("select#length_of_stay option[value='2']").attr("selected","selected");
				} else if (length_of_stay == "3") {
					$("select#length_of_stay option[value='3']").attr("selected","selected");
				} else if (length_of_stay == "4") {
					$("select#length_of_stay option[value='4']").attr("selected","selected");
				} else if (length_of_stay == "5") {
					$("select#length_of_stay option[value='5']").attr("selected","selected");
				} else if (length_of_stay == "6") {
					$("select#length_of_stay option[value='6']").attr("selected","selected");
				} else if (length_of_stay == "7") {
					$("select#length_of_stay option[value='7']").attr("selected","selected");
				}
				
					// set payment_method pulldown menu to same as booking	
				if (payment_method == "Visa") {
					$("select#payment_method option[value='Visa']").attr("selected","selected");
				} else if (payment_method == "Mastercard") {
					$("select#payment_method option[value='Mastercard']").attr("selected","selected");
				} else if (payment_method == "EFTPOS") {
					$("select#payment_method option[value='EFTPOS']").attr("selected","selected");
				} else if (payment_method == "DD") {
					$("select#payment_method option[value='DD']").attr("selected","selected");
				}	
				
			}	
			setpulldowns();
			
			// prep data and post
			
			$('#editbookingsubmit').click(function() {
				
				var adults = $('#adults').attr("value");
				var children = $('#children').attr("value");
				var arrivaldate = $('#arrivaldate').attr("value");
				var length_of_stay = $('#length_of_stay').attr("value");
				var payment_method = $('#payment_method').attr("value");
				var firstname = $('#firstname').attr("value");
				var lastname = $('#lastname').attr("value");
				var email = $('#email').attr("value");		
				var phone = $('#phone').attr("value");
				var add1 = $('#add1').attr("value");
				var add2 = $('#add2').attr("value");
				var city = $('#city').attr("value");
				var state = $('#state').attr("value");
				var postcode = $('#postcode').attr("value");
				var country = $('#country').attr("value");

				$.post ('editbookingdb.php',
				{ booking_id: booking_id, booking_created_at: booking_created_at, adults: adults, children: children, arrivaldate: arrivaldate, length_of_stay: length_of_stay, payment_method: payment_method, firstname: firstname, lastname: lastname, email: email, phone: phone, add1: add1, add2: add2, city: city, state: state, postcode: postcode, country: country},
					function(data) {
						$('#container').html(data);
						// setTimeout("location.reload()",3000);
					} 
				);
			});			
			
			// delete booking confirm dialog
			
			$('#deletebookingconfirm').click(function() {
				$('#delconfirmdialog').show();
			});
			
			$('#deletebooking').click(function() {
				$.post ('deletebooking.php',
				{ booking_id: booking_id },
				function(data) {
					$('#container').html(data);
				}
				);
			});
			
			$('#nodeletebooking').click(function() {
				$('#delconfirmdialog').hide();
			});
			
			
		});
			
	</script>
	
</head>
<body>
	

	
<div id="allcontent">
	
	<!-- completely hidden div for JS to pick up the variables -->
	
	<div id="hiddeninfo" style="display: none;">
		<table>
	
			<tr><th>Booking ID:</th><td id="booking_id_hidden"><?php echo $booking_id; ?></td>
			</tr>
			<tr><th>Booking Created at:</th><td id="booking_created_at_hidden"><?php echo $booking_created_at; ?></td>
			</tr>
			<tr>
				<th>Number of adults:</th><td id="adults_hidden"><?php echo $adults; ?></td>
			</tr>
			<tr>
				<th>Number of children:</th><td id="children_hidden"><?php echo $children; ?></td>
			</tr>
			<tr>
				<th>Check in date:</th><td id="arrival_date_hidden"><?php echo $arrival_date; ?>"></td>
			</tr><tr>
				<th>Length of Stay:</th><td id="length_of_stay_hidden"><?php echo $length_of_stay; ?></td>
			</tr>
			<tr>
				<th>Payment Method:</th><td id="payment_method_hidden"><?php echo $payment_method; ?></td>
			</tr>
			<tr>
				<th>First Name:</th><td id="firstname_hidden"><?php echo $firstname; ?></td>
			</tr>
			<tr>
				<th>Last Name:</th><td id="lastname_hidden"><?php echo $lastname; ?></td>
			</tr>
			<tr>
				<th>Email:</th><td id="email_hidden"><?php echo $email; ?></td>
			</tr>
			<tr>
				<th>Contact Ph:</th><td id="phone_hidden"><?php echo $phone; ?></td>
			</tr>
			<tr>
				<th>Address Line 1:</th><td id="add1_hidden"><?php echo $add1; ?></td>
			</tr>
			<tr>
				<th>Address Line 2:</th><td id="add2_hidden"><?php echo $add2; ?></td>
			</tr>
			<tr>
				<th>Suburb/City:</th><td id="city_hidden"><?php echo $city; ?></td>
			</tr>
			<tr>
				<th>State:</th><td id="state_hidden"><?php echo $state; ?></td>
			</tr>
			<tr>
				<th>Postcode:</th><td id="postcode_hidden"><?php echo $postcode; ?></td>
			</tr>
			<tr>
				<th>Country:</th><td id="country_hidden"><?php echo $country; ?></td>
			</tr>

		</table>
<!-- end completely hidden div -->
	</div>


	<center>
		<h1>Editing Booking <?php echo $booking_id; ?></h1>
		<h2><?php echo $firstname . " " . $lastname; ?></h2>
	</center>
	<!-- start bookingedit div -->
	<div id="bookingedit">
		<center>
			<table>
				<tr>
					<th>Number of adults:</th><td><select id="adults" name="adults">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													</select></td>
				</tr>
				<tr>
					<th>Number of children:</th><td><select id="children" name="children">
													<option value="0">0</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													</select></td>
				</tr>
				<tr>
					<th>Check in date:</th><td><input type="text" class="date" id="arrivaldate" name="newarrivaldate" value="<?php echo $arrival_date; ?>"></td>
				</tr><tr>
					<th>Length of Stay:</th><td><select id="length_of_stay" name="newlength">				
										<option class="length_option" id="length_option1" value="1">1</option>
										<option class="length_option" id="length_option2" value="2">2</option>
										<option class="length_option" id="length_option3" value="3">3</option>
										<option class="length_option" id="length_option4" value="4">4</option>
										<option class="length_option" id="length_option5" value="5">5</option>
										<option class="length_option" id="length_option6" value="6">6</option>
										<option class="length_option" id="length_option7" value="7">7</option>
										</select> night(s)</td>
				</tr>
				<tr>
					<th>Payment Method:</th><td><select id="payment_method" name="payment_method">
													<option>Please choose</option>
													<option value="Visa">Visa</option>
													<option value="Mastercard">Mastercard</option>
													<option value="EFTPOS">EFTPOS</option>
													<option value="DD">Direct Debit</option>
													</td>
				</tr>
				<!-- <tr>
						<th>Estimated price:</th><td><div id="totalprice">Price here</div></td>
					</tr> -->
				<tr>
					<th>First Name:</th><td><input type="text" id="firstname" size="30" value="<?php echo $firstname; ?>"></td>
				</tr>
				<tr>
					<th>Last Name:</th><td><input type="text" id="lastname" size="30" value="<?php echo $lastname; ?>"></td>
				</tr>
				<tr>
					<th>Email:</th><td><input type="text" id="email" size="30" value="<?php echo $email; ?>"></td>
				</tr>
				<tr>
					<th>Contact Ph:</th><td><input type="text" id="phone" size="30" value="<?php echo $phone; ?>"></td>
				</tr>
				<tr>
					<th>Address Line 1:</th><td><input type="text" id="add1" size="30" value="<?php echo $add1; ?>"></td>
				</tr>
				<tr>
					<th>Address Line 2:</th><td><input type="text" id="add2" size="30" value="<?php echo $add2; ?>"></td>
				</tr>
				<tr>
					<th>Suburb/City:</th><td><input type="text" id="city" size="30" value="<?php echo $city; ?>"></td>
				</tr>
				<tr>
					<th>State:</th><td><input type="text" id="state" size="30" value="<?php echo $state; ?>"></td>
				</tr>
				<tr>
					<th>Postcode:</th><td><input type="text" id="postcode" size="30" value="<?php echo $postcode; ?>"></td>
				</tr>
				<tr>
					<th>Country:</th><td><input type="text" id="country" size="30" value="<?php echo $country; ?>"></td>
				</tr>	
			
				</table>
				<p><input type="button" id="editbookingsubmit" value="Save Changes"></p>
				<p><input type="button" id="deletebookingconfirm" value="Delete booking"></p>
				
				<div style="font-size: 1.5em; width: 500px; margin: 10px auto 10px auto; border: solid black 1px; background: pink; display: none;" id="delconfirmdialog">
					<p>Are you sure?</p>
					<p>This action cannot be undone.</p>
					<p><input type="button" id="deletebooking" value="Delete it"><input type="button" id="nodeletebooking" value="No!"></p>
				</div>
				
			
			<!-- container for JS data -->
			<br />
			<div id="container">
			</div>
		<p><a href="bookingslist.php">Back to Bookings List</a></p>
		<p><a href="calendar.php">Back to Calendar</a></p>	
		</center>
 <!-- end bookingedit div -->
	</div>


</div>


</body>
</html>
