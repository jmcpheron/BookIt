<?php

//Specify database type here?
include_once("pgsql_dao.php");


include_once("functions.php");

//Full web address
$site_root = "http://example.com/bookit/";
$site_title = "Book It - Scheduling System";

//Add something random here will increse security. I recommend https://www.grc.com/passwords.htm
$salt = "";

$jquery_path = "/BookIt/extermal_libs/jquery/jquery-1.7.min.js";
$jquery_ui_path = "/bookit/extermal_libs/fullcalendar-1.5.1/jquery/jquery-ui-1.8.11.custom.min.js";

$jquery_timePicker_path = "/bookit/extermal_libs/timePicker/jquery.timePicker.min.js";
$jquery_timePicker_css = "/bookit/extermal_libs/timePicker/timePicker.css";

$jquery_color_picker_path = "/bookit/extermal_libs/colour-picker/jquery.colourPicker.js";
$jquery_color_picker_css = "/bookit/extermal_libs/colour-picker/jquery.colourPicker.css";
$jquery_color_picker_gif = "/bookit/extermal_libs/colour-picker/jquery.colourPicker.gif";

$jquery_chosen = "/bookit/extermal_libs/chosen/chosen.jquery.min.js";

$bootstrap_js = "/bookit/extermal_libs/bootstrap/js/bootstrap.min.js";
$bootstrap_modal_path = "/bookit/js/bootstrap-modal.js";
$bootstrap_dropdown_path = "/bookit/extermal_libs/bootstrap/js/bootstrap-dropdown.js";
$bootstrap_tabs_path = "/bookit/extermal_libs/bootstrap/js/bootstrap-tab.js";

$full_cal_path = "/bookit/extermal_libs/fullcalendar-1.5.1/fullcalendar";

$full_calendar_css = "<link rel='stylesheet' type='text/css' href='$full_cal_path/fullcalendar.css' />";

$full_calendar_links = "
<script type='text/javascript' src='$full_cal_path/fullcalendar.min.js'></script>
<script type='text/javascript' src='$full_cal_path/gcal.js'></script>
";

$view_array = array('month', 'agendaWeek', 'agendaDay');

//Time Slot Size options
$slot_array = array(5, 10, 15, 30);

$common_css = "
<link rel='stylesheet' type='text/css' href='".$site_root."css/jquery-ui-1.7.2.custom.css' />
<link rel='stylesheet' type='text/css' href='".$site_root."extermal_libs/bootstrap/css/bootstrap.min.css'>
<link rel='stylesheet' type='text/css' href='".$site_root."extermal_libs/bootstrap/css/bootstrap-responsive.min.css'>
<link rel='stylesheet' type='text/css' href='".$site_root."main.css' />
";

$common_jquery = "
$('.dropdown-toggle').dropdown();
";
?>
