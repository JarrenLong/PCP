<?php
/**
 * Name:        Jarren Long
 * Date:        1/19/2012
 * Assignment:  PHP Navigation
 * Class:       CIS 230 PHP
 * Description: Calendar page for Long Technical, Co.
 */
include('./templates/config.inc.php');
include('./templates/calendar.php');

define('PCP_PAGE_TITLE', 'Calendar');

DrawSiteHeader();

$this_month = date('m');
$this_year = date('Y');

//Draw a small calendar
drawCalendar($this_month, $this_year, false);

//Draw a BIG calendar!!!
drawCalendar($this_month, $this_year, true);

DrawSiteFooter();
?>

