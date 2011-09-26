<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());


// draw calendar function
    function draw_calendar($month,$year) {

		// set up some necessary array variables
		$unavailables = Array();
		$non_default_price_days = Array();
		$special_days = Array();
		
		// query DB for default price
		
		$query = "select setting, value from settings where setting = 'default_price'";
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_row($result);
		$default_price = $row[1];
				
	// find all unavailable days in the selected month. First...
	// find 1st of month as day of year
		$string = $year . "-" . $month . "-01";
		$dt = strtotime($string);
		$current_month_start_as_day = date('z', $dt);
		
	// find 1st of next month as day of year by making yy-mm-dd format and doing strtotime(). If month is December, find last day of December instead.
		 
		if ($month == 12) {
			$next_month = 1;
			$string = $year . "-" . $month . "-31";
		} else {
			$next_month = ($month + 1);
			$string = $year . "-" . ($next_month) . "-01";
		}
		 	$dt = strtotime($string);
		 	$next_month_start_as_day = date('z', $dt);
		 	
	// select all entries between those two days of year
		 	
		 if ($month == 12 ) {
			$query = "select * from unavailable where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < 366";
		 	$result = mysql_query($query) or die(mysql_error());
		} else {
			$query = "select * from unavailable where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < $next_month_start_as_day";
		 	$result = mysql_query($query) or die(mysql_error());
		}
	 	 
		while ($row = mysql_fetch_array($result)) {
			$unavailable_day_of_year = $row['day_of_year'];
		 	 
			$offset = intval($unavailable_day_of_year) * 86400;
			$date = mktime( 0, 0, 0, 1, 1, date('Y') )+$offset;
			$formatted_day = date( "d", $date );
			
		 	$unavailables[] = $formatted_day;
		}
		
	// now check for non default prices
	
		 if ($month == 12 ) {
			$query = "select * from non_default_price_days where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < 366";
		 	$result = mysql_query($query) or die(mysql_error());
		} else {
			$query = "select * from non_default_price_days where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < $next_month_start_as_day";
		 	$result = mysql_query($query) or die(mysql_error());
		}
	 	 
		while ($row = mysql_fetch_array($result)) {
			$non_default_price_day = $row['day_of_year'];
		 	$price = $row['price'];
		 
			$offset = intval($non_default_price_day) * 86400;
			$date = mktime( 0, 0, 0, 1, 1, date('Y') )+$offset;
			$formatted_day = date( "j", $date );
			
		 	$non_default_price_days[$formatted_day] = $price;
		}
		// print_r($non_default_price_days);
		
	// now check for special days
			
		if ($month == 12 ) {
			$query = "select * from special_days where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < 366";
		 	$result = mysql_query($query) or die(mysql_error());
		} else {
			$query = "select * from special_days where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < $next_month_start_as_day";
		 	$result = mysql_query($query) or die(mysql_error());
		}

		while ($row = mysql_fetch_array($result)) {
			$special_day = $row['day_of_year'];
		 	$price = $row['price'];

			$offset = intval($special_day) * 86400;
			$date = mktime( 0, 0, 0, 1, 1, date('Y') )+$offset;
			$formatted_day = date( "j", $date );

		 	$special_days[$formatted_day] = $price;
		}
		
	
		 	
		 //	print_r($unavailables);
		 	
        /* draw table */

        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table heading */

        $heading = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");                

        $calendar .= '<tr class="calendar_row-head"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$heading).'</td></tr>';

		$calendar .= '<tr class="calendar_row">';

        $running_day = date('w',mktime(0,0,0,$month,1,$year));

        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));

        $days_in_this_week = 1;

        $day_counter = 0;

        $dates_array = array();   


         /* print "blank" days until the first of the current week */

         for($x = 0; $x < $running_day; $x++) {

                $calendar.= '<td class="calendar-day-np">&nbsp;</td>';

                $days_in_this_week++;

         }//endfor

        /* keep going with days */

        for ($list_day=1;$list_day<=$days_in_month;$list_day++) {

            if ( in_array($list_day, $unavailables) && $list_day == date("j",mktime(0,0,0,$month)) && $month == date("n") && $year == date("Y")) {
	
				$calendar .= '<td class="calendar-day booked-day calendar-day-current">';
				
			} elseif ($list_day == date("j",mktime(0,0,0,$month)) && $month == date("n") && $year == date("Y")) {

              $calendar .= '<td class="calendar-day available-day calendar-day-current">'; 

            } elseif (in_array($list_day, $unavailables)) {
	
				$calendar .= '<td class="calendar-day booked-day">';
				
    		} elseif (array_key_exists($list_day, $special_days)) {
	
				$calendar .= '<td class="calendar-day special-day available-day">';
			
			} else {
  				
				$calendar .= '<td class="calendar-day available-day">'; 

            }

            /* add in the day number */

               $calendar .= '<div class="day-number">'.$list_day.'</div>';
				
				$calendar .= '<div class="divpricetext">';

           /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
				if (in_array($list_day, $unavailables)) {
               		$calendar .= '<span class="bookedtext">Booked!</span>';
				} else {
			   		
					if (array_key_exists($list_day, $non_default_price_days)) {
				
						$calendar .= '<span class="availabletext">Available!</span><br /><br /><span class="pricetext">$' . $non_default_price_days[$list_day] . '</span>';
					} else {
						$calendar .= '<span class="availabletext">Available!</span><br /><br /><span class="pricetext">$' . $default_price . '</span>';
					}
				}
				
				$calendar .= '</div>';
				
               $calendar .= '</td>';

               if($running_day == 6) {

                      $calendar .= '</tr>';               

                  if(($day_counter+1) != $days_in_month) {$calendar .= '<tr class="calendar_row">';}

                      $running_day = -1;

                      $days_in_this_week = 0; 
        
               }//endif

              $days_in_this_week++; $running_day++; $day_counter++;

        }//endfor 


        /* finish the rest of the days in the week */

        if($days_in_this_week < 8) {

            for($x=1;$x <= (8-$days_in_this_week);$x++) {

              $calendar.= '<td class="calendar-day-np">&nbsp;</td>';

            }

        }//endif

        /* final row */

           $calendar.= '</tr>';

        /* end the table */

           $calendar.= '</table>';

       /* all done, return result */

      return $calendar;  

    } 

    function draw_small_calendar($month,$year) {

		// set up some necessary array variables
		$unavailables = Array();
		$non_default_price_days = Array();
		
		
	// find all unavailable days in the selected month. First...

	// find 1st of month as day of year
		$string = $year . "-" . $month . "-01";
		$dt = strtotime($string);
		$current_month_start_as_day = date('z', $dt);
		
	// find 1st of next month as day of year by making yy-mm-dd format and doing strtotime(). If month is December, find last day of December instead.
		 
		if ($month == 12) {
			$next_month = 1;
			$string = $year . "-" . $month . "-31";
		} else {
			$next_month = ($month + 1);
			$string = $year . "-" . ($next_month) . "-01";
		}
		 	$dt = strtotime($string);
		 	$next_month_start_as_day = date('z', $dt);
		 	
	// select all entries between those two days of year
		 	
		 if ($month == 12 ) {
			$query = "select * from unavailable where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < 366";
		 	$result = mysql_query($query) or die(mysql_error());
		} else {
			$query = "select * from unavailable where year = $year and day_of_year >= $current_month_start_as_day and day_of_year < $next_month_start_as_day";
		 	$result = mysql_query($query) or die(mysql_error());
		}
	 	 
		while ($row = mysql_fetch_array($result)) {
			$unavailable_day_of_year = $row['day_of_year'];
		 	 
			$offset = intval($unavailable_day_of_year) * 86400;
			$date = mktime( 0, 0, 0, 1, 1, date('Y') )+$offset;
			$formatted_day = date( "d", $date );
			
		 	$unavailables[] = $formatted_day;
		}
		
        /* draw table */

        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table heading */

        $heading = array("S","M","T","W","T","F","S");                

        $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$heading).'</td></tr>';

        $running_day = date('w',mktime(0,0,0,$month,1,$year));

        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));

        $days_in_this_week = 1;

        $day_counter = 0;

        $dates_array = array();   


         /* print "blank" days until the first of the current week */

         for($x = 0; $x < $running_day; $x++) {

                $calendar.= '<td class="calendar-day-np">&nbsp;</td>';

                $days_in_this_week++;

         }//endfor

        /* keep going with days */

        for ($list_day=1;$list_day<=$days_in_month;$list_day++) {

            if ( in_array($list_day, $unavailables) && $list_day == date("j",mktime(0,0,0,$month)) && $month == date("n") && $year == date("Y")) {
	
				$calendar .= '<td class="calendar-day booked-day calendar-day-current">';
				
			} elseif ($list_day == date("j",mktime(0,0,0,$month)) && $month == date("n") && $year == date("Y")) {

              $calendar .= '<td class="calendar-day available-day calendar-day-current">'; 

            } elseif (in_array($list_day, $unavailables)) {
	
				$calendar .= '<td class="calendar-day booked-day">';
				
    		} else {
  				
				$calendar .= '<td class="calendar-day available-day">'; 

            }

          /* add in the day number */

			$calendar .= '<div class="small-day-number">'.$list_day.'</div>';

           // /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
           // 				if (in_array($list_day, $unavailables)) {
           //               		$calendar .= '<span class="bookedtext">Booked!</span>';
           // 				} else {
           // 			   		
           // 					if (array_key_exists($list_day, $non_default_price_days)) {
           // 				
           // 						$calendar .= '<p class="availabletext">Available!</p><p class="pricetext">$' . $non_default_price_days[$list_day] . '</p>';
           // 					} else {
           // 						$calendar .= '<p class="availabletext">Available!</p><p class="pricetext">$' . $default_price . '</p>';
           // 					}
           // 				}
           // 				
           // 				
               $calendar .= '</td>';

               if($running_day == 6) {

                      $calendar .= '</tr>';               

                  if(($day_counter+1) != $days_in_month) {$calendar .= '<tr class="calendar_row">';}

                      $running_day = -1;

                      $days_in_this_week = 0; 
        
               }//endif

              $days_in_this_week++; $running_day++; $day_counter++;

        }//endfor 


        /* finish the rest of the days in the week */

        if($days_in_this_week < 8) {

            for($x=1;$x <= (8-$days_in_this_week);$x++) {

              $calendar.= '<td class="calendar-day-np">&nbsp;</td>';

            }

        }//endif

        /* final row */

           $calendar.= '</tr>';

        /* end the table */

           $calendar.= '</table>';

       /* all done, return result */

      return $calendar;  

    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Bookings Calendar</title>
	<link type="text/css" href="css/bookings.css" rel="Stylesheet" />
	<link type="text/css" href="css/start/jquery-ui-1.8.13.custom.css" rel="Stylesheet" />	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript">
	
		$(document).ready(function() {
			
			$('.calendar-day, .booked-day').each(function (i) {
				var daynumber = $(this).find("div.day-number").text();
				$(this).attr('id', daynumber);
			});
			
			
			$('.date').datepicker({ dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) {$('.date').val(dateText); }	});
			
			$('#maincalendar .available-day').hover(function() {$(this).find("p.availabletext").html("Book now!");} , function() {$(this).find("p.availabletext").html("Available!");});

			var length_of_stay = '';
			
			$('#maincalendar .available-day').click(function() {
							
				$('#clickcalendartext').hide();
				$('#makebooking').show();
								
				$(".calendar-day.selected").removeClass("selected first_day");			
				$(this).addClass("selected first_day");
				
				var current_year = $('#current_year').html();
				var current_month = $('#current_month').html();
				var current_day = $('.day-number', this).html();
				var datestring = current_year + "-" + current_month + "-" + current_day;
				
				$('#arrivaldate').attr("value", datestring); 
				
				// set price in booking form to that of the clicked day
				
				var oneprice = $(this).find("p.pricetext").text();
				oneprice = parseInt(oneprice.replace('$', ''));
				$('#totalprice').html("<span>$" + oneprice + "</span>");
								
				function adjust_length(day) {
				
					$('.length_option').removeAttr("disabled");
				
					var dayplusone = ".calendar-day#" + (parseInt(day) + 1);
					var dayplustwo = ".calendar-day#" + (parseInt(day) + 2);
					var dayplusthree = ".calendar-day#" + (parseInt(day) + 3);
					var dayplusfour = ".calendar-day#" + (parseInt(day) + 4);
					var dayplusfive = ".calendar-day#" + (parseInt(day) + 5);
					var dayplussix = ".calendar-day#" + (parseInt(day) + 6);
			
			 		if ($(dayplusone).hasClass("booked-day")) {
			 				 						
			 			$('#length_option2, #length_option3, #length_option4, #length_option5, #length_option6, #length_option7').attr("disabled", "disabled");
			
					} else if ($(dayplustwo).hasClass("booked-day")) {
						
						$('#length_option3, #length_option4, #length_option5, #length_option6, #length_option7').attr("disabled", "disabled");
						
					} else if ($(dayplusthree).hasClass("booked-day")) {
						
						$('#length_option4, #length_option5, #length_option6, #length_option7').attr("disabled", "disabled");
						
					} else if ($(dayplusfour).hasClass("booked-day")) {
						
						$('#length_option5, #length_option6, #length_option7').attr("disabled", "disabled");
						
					} else if ($(dayplusfive).hasClass("booked-day")) {
						
						$('#length_option6, #length_option7').attr("disabled", "disabled");
						
					} else if ($(dayplussix).hasClass("booked-day")) {
						
						$('#length_option7').attr("disabled", "disabled");
					}
					
				};
											
				adjust_length($(this).attr("id"));
				
				var select = $('select');
				$('select').val($('options:first', select).val());
				
				$('select').change(function () {
					$('.selected').removeClass("selected");
					var length_of_stay = parseInt($('#length_of_stay option:selected').text());
					var selected_day = parseInt($('.first_day').attr("id"));
									
					for (var i=selected_day; i <= (selected_day + length_of_stay -1); i++) {
						var j = ".calendar-day#" + i; 					
						$(j).addClass("selected");
					}
					
					// update price when length select is changed
					
					var totalprice = 0;
					$('td.selected').find("p.pricetext").each(function () {
						var sum = $(this).text();
						sum = parseInt(sum.replace('$', ''));
						totalprice += sum;
					});
					$('#totalprice').html("<span>$" + totalprice + "</span>");
				});					
					
			});

			$('#maincalendar .booked-day').click(function() {
				$('#arrivaldate').attr("value", "Already booked!");
			});
		
			$('#displaysecondinfo').click(function() {
				$('#secondinfo').show();
			});
	
			
			function showsmallcalendars() {
				var current_year = parseInt($('#current_year').html());
				var current_month = parseInt($('#current_month').html());
				var now = new Date();
				var nowmonth = $.datepicker.formatDate('m', now);
				var nowyear = $.datepicker.formatDate('yy', now);
		
				if ( current_month == nowmonth && current_year == nowyear) {
					$('#smallcalendar1').hide();
				}
			
			};
			showsmallcalendars();
						
			$('#smallcalendar1, #smallcalendar2').hover( function () { $(this).css('opacity', '1'); }, function() { $(this).css('opacity', '0.75'); } );
			
			$('#smallcalendar1').click(function() {
				var year = parseInt($('#current_year').html());
				var month = parseInt($('#current_month').html());
				var phpself = "<?php echo $_SERVER['PHP_SELF']; ?>";
				
				if (month == 1) {
					var prevmonth = 12;
					year = (year-1);
				} else {
					var prevmonth = (month-1);
				}
				
				var prevmonthurl = phpself + "?month=" + prevmonth + "&year=" + year;
				window.location = prevmonthurl; 
			});
			
			$('#smallcalendar2').click(function() {
				var year = parseInt($('#current_year').html());
				var month = parseInt($('#current_month').html());
				var phpself = "<?php echo $_SERVER['PHP_SELF']; ?>";
				
				if (month == 12) {
					var nextmonth = 1;
					year = (year+1);
				} else {
					var nextmonth = (month+1);
				}
				
				var nextmonthurl = phpself + "?month=" + nextmonth + "&year=" + year;
				window.location = nextmonthurl; 
			});
			
			$('#secondinfoform').validate();
			
			$('#newbookingsubmit').click(function(){
				
				if ($('#secondinfoform').valid() == true) {
								
					var adults = $('#adults').attr("value");
					var children = $('#children').attr("value");
					var arrivaldate = $('#arrivaldate').attr("value");
					var length_of_stay = $('#length_of_stay').attr("value");
					var payment_method = $('#payment_method').attr("value");
					//	var user_id = $('#user_id').attr("value");
					var firstname = $('#firstname').attr("value");
					var lastname = $('#lastname').attr("value");
					var email = $('#email').attr("value");		
					var phone = $('#phone').attr("value");
					var add1 = $('#add1').attr("value");
					var add2 = $('#add2').attr("value");
					var city = $('#city').attr("value");
					var	state = $('#state').attr("value");
					var postcode = $('#postcode').attr("value");
					var country = $('#country').attr("value");

					$.post ('newbooking.php',
					{ adults: adults, children: children, arrivaldate: arrivaldate, length_of_stay: length_of_stay, payment_method: payment_method, firstname: firstname, lastname: lastname, email: email, phone: phone, add1: add1, add2: add2, city: city, state: state, postcode: postcode, country: country},
						function(data) {
							$('#container').html(data);
							setTimeout("location.reload()",3000);
						} 
					);
				} else {
					alert("The form is invalid");
				}
			});
			
		
		});
		
	</script>


</head>
<body>
	<div id="allcontent">
		<p><a style="text-decoration: underline;" href="admin.php">Admin Page</a></p>
		<p><a style="text-decoration: underline;" href="admincalendar.php">Admin Calendar</a></p>
		<p><a style="text-decoration: underline;" href="allcalendars.php">Yearly Calendar</a></p>
	
<?php 

// RIDICULOUSLY REDUNDANT REPEATING OF CODE BECAUSE I CBF

$month = (int)($_GET['month'] ? $_GET['month'] : date("n"));

$year = (int)($_GET['year'] ? $_GET['year'] : date("Y"));

echo '<div id="maincalendar">'; 

if ($month <= date("n") && $year == date("Y")) {
	$month = date("n");
	echo '<center><div id="header"><h2><div id="invisible_button" class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</div><span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';

} elseif ($month > 12) {
	$month = 12;
	echo '<center><div id="header"><h2><a class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</a> <span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';

} else {
	echo '<center><div id="header"><h2><a class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</a> <span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';	
}


echo draw_calendar($month, $year); 
echo '</div>';
echo '<div id="smallcalendar1" title="Previous month">';




if ($month == 1) {
	$prevmonth = 12;
	$prevyear = ($year-1);
	echo "<div class=\"smallcalendarheading\">" . date('F',mktime(0,0,0,$prevmonth,1,$prevyear)) . " " . $prevyear . "</div>";
	echo draw_small_calendar($prevmonth, $prevyear);
} else {
	$prevmonth = ($month-1);
	$prevyear = $year;
	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$prevmonth,1,$prevyear)) . ' ' . $prevyear . '</div>';
	echo draw_small_calendar($prevmonth, $prevyear);
}

echo '</div>';

echo '<div id="smallcalendar2" title="Next month">';

if ($month == 12) {
	$nextmonth = 1;
	$nextyear = ($year+1);
	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$nextmonth,1,$nextyear)) . ' ' . $nextyear . '</div>';
	echo draw_small_calendar($nextmonth, $nextyear);
} else {
	$nextmonth = ($month+1);
	$nextyear = $year;
	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$nextmonth,1,$nextyear)) . ' ' . $nextyear . '</div>';
	echo draw_small_calendar($nextmonth, $nextyear);
}



echo '</div>';

?>
		<div id="clickcalendartext">
			<center>
				<p><em>Click an available date to make a booking request for <span style="color: red;"><strong>that night</strong></span> and any following nights.</em></p>
			</center>
		</div>

		<div id="makebooking">
			<center><h3>Make a booking!</h3></center>
			<div id="firstinfo">
				<center>
					<form id="firstinfoform">
						<table>
							<tr>
								<th>Number of adults:</th><td><select id="adults" name="adults">
									<option>1</option>
									<option>2</option>
									<option>3</option>
								</select></td>
							</tr>
							<tr>
								<th>Number of children:</th><td><select id="children" name="children">
									<option>0</option>
									<option>1</option>
									<option>2</option>
									<option>3</option>
								</select></td>
							</tr>
							<tr>
								<th>Check in date:</th><td><input type="text" class="date" id="arrivaldate" name="newarrivaldate" value="Click to select"></td>
							</tr>
							<tr>
								<th>Length of Stay:</th><td><select id="length_of_stay" name="newlength">				
									<option class="length_option" id="length_option1">1</option>
									<option class="length_option" id="length_option2">2</option>
									<option class="length_option" id="length_option3">3</option>
									<option class="length_option" id="length_option4">4</option>
									<option class="length_option" id="length_option5">5</option>
									<option class="length_option" id="length_option6">6</option>
									<option class="length_option" id="length_option7">7</option>
								</select> night(s)</td>
							</tr>
							<tr>
								<th>Payment Method:</th><td><select id="payment_method" name="payment_method">
									<option>Please choose</option>
									<option value="Visa">Visa</option>
									<option value="Mastercard">Mastercard</option>
									<option value="EFTPOS">EFTPOS</option>
									<option value="DD">Direct Debit</option>
								</select></td>
							</tr>
							<tr>
								<th>Estimated price:</th><td><div id="totalprice">Price here</div></td>
							</tr>
							<tr>
								<th></th><td><input type="button" id="displaysecondinfo" value="Book now?"></td>
							</tr>
						</table>
					</form>
				</center>
			</div>	
			<div id="secondinfo" style="display: none;">
				<hr>	
				<center>
					<form id="secondinfoform">
						<h3>Contact Details</h3>
						<p style="color: crimson"> ( * denotes required field )</p>
						<table>
							<tr>
								<th><span class="reqfield">*</span> First Name:</th><td><input type="text" class="required" id="firstname" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Last Name:</th><td><input type="text" class="required" id="lastname" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Email:</th><td><input type="text" class="required email" id="email" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Contact Ph:</th><td><input type="text" class="required" id="phone" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Address Line 1:</th><td><input type="text" class="required" id="add1" size="30"></td>
							</tr>
							<tr>
								<th>Address Line 2:</th><td><input type="text" id="add2" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Suburb/City:</th><td><input type="text" class="required" id="city" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> State:</th><td><input type="text" class="required" id="state" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Postcode:</th><td><input type="text" class="required" id="postcode" size="30"></td>
							</tr>
							<tr>
								<th><span class="reqfield">*</span> Country:</th><td><input type="text" class="required" id="country" size="30"></td>
							</tr>	
							<tr>				
								<th></th><td><input type="button" id="newbookingsubmit" value="Make Reservation"></td>
							</tr>
						</table>
					</form>
				</center>
			</div>
		</div>

		<div id="container">
		</div>

	</div>
</body>
</html>