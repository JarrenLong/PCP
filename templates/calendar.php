<?php
/**
 * Name:        Jarren Long
 * Date:        1/08/2012
 * Assignment:  PHP Calendar
 * Class:       CIS 230 PHP
 * Description: Calendar page template for Long Technical, Co. This script
 *          will generate styled calendars using the month/year specified
 *          in the 'm' and 'y' GET variables, respectively. Days from last
 *          and next month are styled differently than days in the current
 *          month, as is the current date on the calendar.
 */

function buildGetString($year, $month) {
  $buf = "";
  
  if($month < 1) {
    //If month < Jan, wrap to Dec of last year
    $month = 12;
    $year --;
  } else if($month > 12) {
    //If month > Dec, wrap to Jan of next year
    $month = 1;
    $year ++;
  }
  
  //Build the return string
  $buf = "y=" . $year . "&amp;m=" . $month;
  
  return $buf;
}


function drawCalendar($month, $year, $big = false) {

  if(isset($_GET['m'])) {
    $month = $_GET['m'];
  }
  if(isset($_GET['y'])) {
    $year = $_GET['y'];
  }

  $time_tm = strtotime($month.'/01/'.$year.' 00:00:00'); //Convert specified month/year to a time 
  $days_lm = date("t", strtotime('-1 second', $time_tm)); //28-31 (31)
  $days_tm = date("t", $time_tm); //28-31 (29)
  $tm_starts_on = date("w", $time_tm); //0-6 (3)
  $days_lm_to_show = $tm_starts_on;
  $days_nm_to_show = 35 - ( $tm_starts_on + $days_tm );
  
  
  echo "<form name=\"calendar_form\" action=\"calendar.php\" method=\"get\" >\n";
  echo "<table class=\"calendar_" . ($big ? "large" : "small"). "\">\n";
  echo "  <caption>" . date("F Y", $time_tm) . "</caption>\n";
  echo "  <tr>\n";
  echo "   <th class=\"center\">Su</th>\n";
  echo "   <th class=\"center\">Mo</th>\n";
  echo "   <th class=\"center\">Tu</th>\n";
  echo "   <th class=\"center\">We</th>\n";
  echo "   <th class=\"center\">Th</th>\n";
  echo "   <th class=\"center\">Fr</th>\n";
  echo "   <th class=\"center\">Sa</th>\n";
  echo "  </tr>\n";

 
  for($i = 0; $i < 35; $i++) {
    //Start of row
    if($i % 7 == 0) echo "  <tr>\n";
   
    $num = $i + 1;
    $inactive = '';

    if( $days_lm_to_show > 0) {
	  //Build days for last month if necessary
      $num = $days_lm - $days_lm_to_show + 1;
      $inactive = "calendar_inactive";
	 
      $days_lm_to_show -= 1;
    } else if( $days_tm > 0 ) {
	  //Build days for this month
      $num = $i - $tm_starts_on + 1;
	 
	  //Make sure today is highlighted
	  if($num == date('j')) {
	    $inactive = "calendar_today";
	  }
	 
      $days_tm -= 1;
    } else if( $days_nm_to_show >= 0) {
	  //Build days for next month if necessary
      $num = $i - date("t", $time_tm) - $tm_starts_on + 1;
      $inactive = "calendar_inactive";

      $days_nm_to_show -= 1;
    }
   
    //And echo out the table cell with the day info & styling in it
    echo "    <td ";
	 
	if( $inactive == '' || $inactive == "calendar_today" ) {
      echo "onmouseover=\"this.className = 'calendar_hover';\" " . 
        "onmouseout=\"this.className = '" . $inactive . "';\" ";
    }

    if($inactive != '') {
      echo "class=\"" . $inactive . "\">\n      <a href=\"#\">" . $num . "</a>\n    </td>\n";
    } else {
      echo ">\n      <a href=\"#\">" . $num . "</a>\n    </td>\n";
    }

	//End of row
    if($i % 7 == 6) echo "  </tr>\n";
  }
 
 
  echo "  <tr>\n    <td colspan=\"7\" class=\"calendar_footer\">\n      ";
  echo "<a href=\"calendar.php?" . buildGetString($year - 1, $month) .
    "\">&lt;&lt;</a> | <a href=\"calendar.php?" .
	buildGetString($year, $month - 1) .
	"\"><b>&lt;</b></a>\n | \n<a href=\"calendar.php?" .
	buildGetString($year, $month + 1) .
	"\"><b>&gt;</b></a> | <a href=\"calendar.php?" .
	buildGetString($year + 1, $month) .
	"\">&gt;&gt;</a>\n    </td>\n  </tr>\n</table>\n</form>\n\n";
}
?>
