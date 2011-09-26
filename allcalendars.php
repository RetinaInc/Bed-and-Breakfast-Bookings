<?php

// set stupid default date shit
date_default_timezone_set('Asia/Tokyo');
 
//connect to mysql and select db
mysql_connect('localhost', 'bandb', 'bandb') or die(mysql_error());
mysql_select_db('bandb') or die(mysql_error());



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
			
			$('.allcalendars:not(.earlymonth)').hover(function () { $(this).css('opacity', '1'); }, function() { $(this).css('opacity', '0.8'); } );

			$('.allcalendars:not(.earlymonth)').click(function() {
				var year = $('#year').html();
				var month = $(this).attr("id").replace('allcalendars', '');
				var monthurl = 'calendar.php?month=' + month + '&year=' + year;
				window.location = monthurl;
								
				
				// var phpself = "<?php echo $_SERVER['PHP_SELF']; ?>";
				// alert(phpself);
								// 
								// 	if (month == 1) {
								// 		var prevmonth = 12;
								// 		year = (year-1);
								// 	} else {
								// 		var prevmonth = (month-1);
								// 	}
				
					// var prevmonthurl = phpself + "?month=" + prevmonth + "&year=" + year;
					// 				window.location = prevmonthurl; 
				});
			// function showsmallcalendars() {
			// 			var current_year = parseInt($('#current_year').html());
			// 			var current_month = parseInt($('#current_month').html());
			// 			var now = new Date();
			// 			var nowmonth = $.datepicker.formatDate('m', now);
			// 			var nowyear = $.datepicker.formatDate('yy', now);
			// 	
			// 			// if ( current_month == nowmonth && current_year == nowyear) {
			// 			// 				$('#smallcalendar1').hide();
			// 			// 			}
			// 		
			// 		};
			// 		showsmallcalendars();
						

			
			//	allcalendars').click(function() {
				
				// var year = parseInt($('#current_year').html());
				// var month = parseInt($('#current_month').html());
				// var phpself = "<?php echo $_SERVER['PHP_SELF']; ?>";
				// 
				// if (month == 1) {
				// 	var prevmonth = 12;
				// 	year = (year-1);
				// } else {
				// 	var prevmonth = (month-1);
				// }
				// 
				// var prevmonthurl = phpself + "?month=" + prevmonth + "&year=" + year;
				// window.location = prevmonthurl; 
			// });
			
			// $('#smallcalendar2').click(function() {
			// 			var year = parseInt($('#current_year').html());
			// 			var month = parseInt($('#current_month').html());
			// 			var phpself = "<?php echo $_SERVER['PHP_SELF']; ?>";
			// 			
			// 			if (month == 12) {
			// 				var nextmonth = 1;
			// 				year = (year+1);
			// 			} else {
			// 				var nextmonth = (month+1);
			// 			}
			// 			
			// 			var nextmonthurl = phpself + "?month=" + nextmonth + "&year=" + year;
			// 			window.location = nextmonthurl; 
			// 		});
			
	
			// });
			
		
		});
		
	</script>


</head>
<body>
	<div id="allcontent">
		<p><a style="text-decoration: underline;" href="admin.php">Admin Page</a></p>
		<p><a style="text-decoration: underline;" href="admincalendar.php">Admin Calendar</a></p>
		<p><a style="text-decoration: underline;" href="calendar.php">Normal Calendar</a></p>
<?php 

// RIDICULOUSLY REDUNDANT REPEATING OF CODE BECAUSE I CBF

// $month = (int)($_GET['month'] ? $_GET['month'] : date("n"));

$year = (int)($_GET['year'] ? $_GET['year'] : date("Y"));

?>

<center>
<?php 

echo '<h2 id="year">'. $year . '</h2>';
echo '<h4><a href="allcalendars.php?year=' . ($year - 1) . '">' . ($year - 1) . '</a>&nbsp&nbsp&nbsp<a href="allcalendars.php?year=' . ($year + 1) . '">' . ($year + 1) . '</a>';
?>
</center>

<?php
// if ($month <= date("n") && $year == date("Y")) {
// 	$month = date("n");
// 	echo '<center><div id="header"><h2><div id="invisible_button" class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</div><span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';
// 
// } elseif ($month > 12) {
// 	$month = 12;
// 	echo '<center><div id="header"><h2><a class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</a> <span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';
// 
// } else {
// 	echo '<center><div id="header"><h2><a class="control" href="calendar.php?month='. ( $month != 1 ? $month - 1 : 12) .'&year='. ( $month != 1 ? $year : $year - 1 ) .'">&laquo; Prev month</a> <span style="display:none;" id="current_month">' . $month . '</span> <span id="monthname" style="margin-left: 10px;">' . date('F',mktime(0,0,0,$month,1,$year)).'</span>' . ' <span id="current_year" style="margin-right: 10px;">' . $year . '</span> <a class="control" href="calendar.php?month='. ( $month != 12 ? $month + 1 : 1) .'&year='. ( $month != 12 ? $year : $year + 1 ) .'">Next month &raquo;</a> <div class="today_button"><a class="control" href="calendar.php?month='. date("n").'&year='. date("Y") .'">Today</a></div></h2></div></center>';	
// }

// echo '<div id="maincalendar">'; 
// echo draw_calendar($month, $year); 
// echo '</div>';
//echo '<div id="smallcalendar1" title="Previous month">';

// 
// 
// 
// if ($month == 1) {
// 	$prevmonth = 12;
// 	$prevyear = ($year-1);
// 	echo "<div class=\"smallcalendarheading\">" . date('F',mktime(0,0,0,$prevmonth,1,$prevyear)) . " " . $prevyear . "</div>";
// 	echo draw_small_calendar($prevmonth, $prevyear);
// } else {
// 	$prevmonth = ($month-1);
// 	$prevyear = $year;
// 	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$prevmonth,1,$prevyear)) . ' ' . $prevyear . '</div>';
// 	echo draw_small_calendar($prevmonth, $prevyear);
// }
// 
// echo '</div>';
// 
// echo '<div id="smallcalendar2" title="Next month">';
// 
// if ($month == 12) {
// 	$nextmonth = 1;
// 	$nextyear = ($year+1);
// 	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$nextmonth,1,$nextyear)) . ' ' . $nextyear . '</div>';
// 	echo draw_small_calendar($nextmonth, $nextyear);
// } else {
// 	$nextmonth = ($month+1);
// 	$nextyear = $year;
// 	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$nextmonth,1,$nextyear)) . ' ' . $nextyear . '</div>';
// 	echo draw_small_calendar($nextmonth, $nextyear);
// }
// 
// 
// 
// echo '</div>';





echo '<div id="allcalendarswrapper">';

for ($i=1; $i<5; $i++) {
	if (($year == date('Y') && $i < date('n')) OR $year < date('Y')) {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group1 earlymonth">';
	} else {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group1">';
	}
	
	echo '<div class="allcalendarsheading">' . date('F',mktime(0,0,0,$i,1,$year)) . ' ' . $year . '</div>';
	echo '<div id="allcalendarsmonth' . $i .'" class="allcalendarsmonth">';
	echo draw_small_calendar($i, $year);
	echo '</div></div>';
}

for ($i=5; $i<9; $i++) {

	if (($year == date('Y') && $i < date('n')) OR $year < date('Y')) {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group2 earlymonth">';
	} else {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group2">';
	}
	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$i,1,$year)) . ' ' . $year . '</div>';
	echo '<div id="allcalendarsmonth' . $i .'" class="allcalendarsmonth">';
	echo draw_small_calendar($i, $year);
	echo '</div></div>';
}

for ($i=9; $i<13; $i++) {

	if (($year == date('Y') && $i < date('n')) OR $year < date('Y')) {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group3 earlymonth">';
	} else {
		echo '<div id="allcalendars' . $i . '"class="allcalendars group3">';
	}
	echo '<div class="smallcalendarheading">' . date('F',mktime(0,0,0,$i,1,$year)) . ' ' . $year . '</div>';
	echo '<div id="allcalendarsmonth' . $i .'" class="allcalendarsmonth">';
	echo draw_small_calendar($i, $year);
	echo '</div></div>';
}

echo '</div>';

?>


		<div id="container">
		</div>

	</div>
</body>
</html>