<?
include("common.php");
include_once("session.php");
$selected_date = fixString($_GET['date']);
if($selected_date == ""){
 $selected_date = date('Y-m-d');
}

$view = fixString($_GET['view']);
if($view == ""){
  $view = getUserSettingValue($id, 'default_view');
  if($view == ""){
  $view = 'agendaWeek';
  }
}

$slot_size = getUserSettingValue($id, 'slot_size');
if($slot_size == ""){
  //Default Slot Size
  $slot_size = 15;
}

$start_hour = getUserSettingValue($id, 'start_hour');
if($start_hour == ""){
  //Default Start Hour
  $start_hour = 8;
}

function parseDateForJS($date){
  $return = array(
    'year' => substr($date, 0, 4),
    'month' => (date('m', strtotime($date)) - 1),//Stupid javascript month format
    'day' => date('d', strtotime($date))
  );
return $return;
}
$date = parseDateForJS($selected_date);

//Set up some date limits so we don't get events for all time
if($view == 'month'){
  $start_day = date('Y-m-d', strtotime(date("Y-m-d", strtotime($selected_date)) . " -5 week") );
  $end_day = date('Y-m-d', strtotime(date("Y-m-d", strtotime($selected_date)) . " +5 week") );
}

if($view == 'agendaWeek'){
  $start_day = date('Y-m-d', strtotime(date("Y-m-d", strtotime($selected_date)) . " -1 week") );
  $end_day = date('Y-m-d', strtotime(date("Y-m-d", strtotime($selected_date)) . " +1 week") );
}

if($view == 'agendaDay'){
  $start_day = $selected_date;
  $end_day = $selected_date;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?
echo $common_js;
echo $common_css;
echo $full_calendar_css;
echo $full_calendar_links;
?>
<!--<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.js'></script>-->
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>

  $.strPad = function(i,l,s) {
	var o = i.toString();
	if (!s) { s = '0'; }
	while (o.length < l) {
		o = s + o;
	}
	return o;
};
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		

	$('#calendar').fullCalendar(
{
			header: {
				left: null,
				center: 'title',
				right: null
			},
			editable: false,
dayClick: function(date, allDay, jsEvent, view) {
  var curr_date = date.getDate();
  var curr_month = date.getMonth();
  curr_month++;
  var curr_year = date.getFullYear();
  
  var curr_hour = date.getHours();
  var curr_min = date.getMinutes();
 
  curr_date = $.strPad(curr_date, 2);
  curr_month = $.strPad(curr_month, 2);
  var string_date = curr_year + "-" + curr_month + "-" + curr_date;
  
  var string_time = curr_hour + ":" + $.strPad(curr_min, 2);

        if (allDay) {
            window.location.href = "?date=" + string_date + "&view=agendaDay";
        }else{
            window.location.href = "new_appt.php?date=" + string_date + "&time=" + string_time;
        }


    },
defaultView: '<?echo $view;?>',
slotMinutes: <?echo $slot_size;?>,
firstHour: <?echo $start_hour;?>,
  events: [
<?

$events_string = "";
$appointments = getRangeOfAppointments($id, $start_day, $end_day);
if($appointments){
  foreach($appointments as $item){
    $events_string .= drawBlockByBid($item['bid'], "block.php?bid=".$item['bid'], $id);
  }
}
//Trim last comma
$events_string = substr($events_string, 0, -5);
echo $events_string;
echo "\n\r";
?>
			]
		}

);
$('#calendar').fullCalendar('gotoDate', <?echo $date['year'];?>, <?echo $date['month'];?>, <?echo $date['day'];?>);

		
	});

</script>
<style type='text/css'>

	body {
font-size: 14px;
font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

.main {float:left;}
#controls {
  height: 100px;
  width: 18%;
  margin: 0 auto;
  }
#calendar {
  width: 80%;
  height: 100%;
  margin: 0 auto;
  float:right;
  }
</style>
</head>
<body>
<div class="container-fluid">
<?

drawHeader($id);
drawCalControld($selected_date, $view);
?>

<? $q =  $_SERVER['QUERY_STRING'];
?>
<div class="row">
<hr />
  <div id="controls" class="main sidebar span3">
    <!--TODO remove demo-->
    <div class="alert alert-message">
      <a href="place.php?sid=00776162&sou=bookit&srole=student&ou=bookit&role=charter">Jason McPheron</a> is looking for an appointment<br />
    </div>
  <h2>Departments</h2>
  <br /><br /><br />
  </div>
  
  <div id='calendar' class="main span9 hidden-phone"></div>
</div><!--row-->

</div>
</body>
