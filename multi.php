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
<title><?echo strip_tags($site_title);?> </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?
echo $common_js;
echo $common_css;
echo $full_calendar_css;
echo $full_calendar_links;
?>
<!--<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.js'></script>-->
<script type='text/javascript'>
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
};


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

$show_calendars = array();
$store_show_val = array();

$sql = "
select n.firstname || ' ' || n.lastname as name, r.id
from ou_roles r
left join person n on (r.id = n.id)
left join participants p on (r.id = p.id 
  and r.ou_code= p.ou_code 
  and r.role = p.role)
left join blocks b on (p.bid = b.bid)
where r.ou_code = 'bookit'
and r.role = 'charter'
and b.start_time >= '$start_day'
and b.start_time <= '$end_day'
group by n.firstname, n.lastname, r.id
";
$people = db_query($sql);

$p_colors = array(
'3366FF',
'6633FF',
'CC33FF',
'FF33CC',
'FF6633',
'B88A00',
'CCFF33',
'FFCC33'
);
$p = 0;
foreach($people as $item){
  $this_color[$item['id']] = $p_colors[$p]; 
  $p++;
}

$events_string = "";
//$appointments = getRangeOfAppointments($id, $start_day, $end_day);
$sql = "
select b.bid, p.ou_code, p.role,
b.title || ' ' || p.id as title, 
TO_CHAR(b.start_time,'YYYY, (MM - 1), DD, HH24, MI') AS start_time,
TO_CHAR(b.end_time,'YYYY, (MM - 1), DD, HH24, MI') AS end_time,
--b.start_time, b.end_time, 
p.id
from ou_roles r
left join participants p on (r.id = p.id 
  and r.ou_code= p.ou_code 
  and r.role = p.role)
left join blocks b on (p.bid = b.bid)
where r.ou_code = 'bookit'
and r.role = 'charter'
and b.start_time >= '$start_day'
and b.start_time <= '$end_day'
";
$appointments = db_query($sql);
if($appointments){
  foreach($appointments as $item){
    //echo "AAA".$this_color[$item['id']];
    $events_string .= drawBlockByBid($item['bid'], "block.php?bid=".$item['bid'], $item['id'], $this_color[$item['id']], ' ['.$item['id']."]" );
    if(!in_array($item['ou_code']."/".$item['role'], $store_show_val)){
      $store_show_val[] = $item['ou_code']."/".$item['role'];
      $show_calendars[] = array(
        'name'=>$item['ou_code']."/".$item['role'],
        'class'=>$item['ou_code']."-".$item['role']
      );
    }
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


<?
/*
Schedule sample
*/
if( ($id == 'demo0004') or ($id == 'demo0005') ){
$schedule_array = array(
  array(
    'Title'=>'Math100',
    'start_time'=>strtotime('2012-05-21 09:30')."000",
    'end_time'=>strtotime('2012-05-21 10:25')."000",
  ),
  array(
    'Title'=>'Math100',
    'start_time'=>strtotime('2012-05-23 09:30')."000",
    'end_time'=>strtotime('2012-05-23 10:25')."000",
  ),
  array(
    'Title'=>'Hist120',
    'start_time'=>strtotime('2012-05-21 13:00')."000",
    'end_time'=>strtotime('2012-05-21 14:25')."000",
  ),
  array(
    'Title'=>'Hist120',
    'start_time'=>strtotime('2012-05-23 13:00')."000",
    'end_time'=>strtotime('2012-05-23 14:25')."000",
  ),
  array(
    'Title'=>'Hist120',
    'start_time'=>strtotime('2012-04-20 13:00')."000",
    'end_time'=>strtotime('2012-04-20 14:25')."000",
  ),
);
$schedule_array = json_encode($schedule_array);
//echo $schedule_array;
?>

var schedule_json = <?echo $schedule_array;?>;
 /*
$.each(schedule_json, function(key, value){

  $("#calendar").fullCalendar('renderEvent', {
    title: value.Title,
    start: new Date(parseInt(value.start_time)),
    end: new Date(parseInt(value.end_time)),
    allDay: false,
    color: '#6868D4',
   })
});
*/
<?}?>

var show_days = 10;
var show_hours = 4;
var c_show_days = 0;
var c_show_hours = 0;

$(".cal-list").click( function(){
  while(c_show_days < 2){
    while(c_show_hours < 2){
      $("#calendar").fullCalendar('renderEvent', {
        title: 'Test',
        start: new Date('2012-07-05 ' + show_hours + ':00.000'),
        end: new Date('2012-07-05 ' + (show_hours + 1) + ':00.000'),
        allDay: false,
        color: '#6868D4',
       })
      show_hours = show_hours + 1;
      c_show_hours = c_show_hours + 1;
    }
    show_days = show_days + 1;
    c_show_days = c_show_days + 1;
    c_show_hours = 0; 
    show_hours = 4; 
  }
  

});
		
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
  <div class="sidebar span2">
    <div class="alert">
    <a href="generate_slots.php">Sample Schedule Setup</a>
    </div>
  <h2>Calendars</h2>
<?
  foreach($people as $item){
    $this_cal_list_color = getUserSettingValue($id, 'calendar_color', $item['name']);
    if(!$this_cal_list_color){
      $this_cal_list_color = $this_color[$item['id']];
    }
    echo "<div class=\"cal-list ".$item['class']."\" id=\"cal-".$item['class']."\" style=\"background-color: #$this_cal_list_color; color:white; padding:5px; \">\n";
    echo $item['name'];
    echo "</div>\n";
    echo "<br />";
  }

  if($schedule_array){
    echo "<div class=\"cal-list ".$item['class']."\" id=\"cal-".$item['class']."\" style=\"background-color: #6868D4; color:white; padding:5px; \">\n";
    echo "Class Schedule</div><br />";
  }
?>
  <br /><br /><br />
  </div>
  
  <div id='calendar' class="span8 hidden-phone"></div>
</div><!--row-->

</div>
</body>
