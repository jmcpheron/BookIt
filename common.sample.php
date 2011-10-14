<?php

//Specify database type here?
include_once("pgsql_dao.php");


include_once("functions.php");

//Full web address
$site_root = "http://example.com/bookit/";
$site_title = "Book It - Scheduling System";

//Adding something random here will increse security. I recommend https://www.grc.com/passwords.htm
$salt = "";

$jquery_path = "/bookit/extermal_libs/fullcalendar-1.5.1/jquery/jquery-1.5.2.min.js";
$jquery_ui_path = "/bookit/extermal_libs/fullcalendar-1.5.1/jquery/jquery-ui-1.8.11.custom.min.js";

$jquery_timePicker_path = "/bookit/extermal_libs/timePicker/jquery.timePicker.min.js";
$jquery_timePicker_css = "/bookit/extermal_libs/timePicker/timePicker.css";

$full_cal_path = "/bookit/extermal_libs/fullcalendar-1.5.1/fullcalendar";

$full_calendar_css = "<link rel='stylesheet' type='text/css' href='$full_cal_path/fullcalendar.css' />";

$full_calendar_links = "
<script type='text/javascript' src='$full_cal_path/fullcalendar.min.js'></script>
<script type='text/javascript' src='$full_cal_path/gcal.js'></script>
";

//Controls specific to the fullcalendar project 
//http://arshaw.com/fullcalendar/docs/views/Available_Views/
/*
FullCalendar has a number of different "views", or ways of displaying days and events. The following 5 views are all built in to FullCalendar:

month - see example
basicWeek - see example (available since version 1.3)
basicDay - see example (available since version 1.3)
agendaWeek - see example (available since version 1.4)
agendaDay - see example (available since version 1.4)
You can define header buttons to allow the user to switch between them. You can set the initial view of the calendar with the defaultView option.
*/
$view_array = array('month', 'agendaWeek', 'agendaDay');

$common_css = "
<link rel='stylesheet' type='text/css' href='css/jquery-ui-1.7.2.custom.css' />
<link rel='stylesheet' href='http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css'>
";
?>
