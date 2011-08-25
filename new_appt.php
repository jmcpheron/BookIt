<?
include("common.php");
include_once("session.php");
$date = fixString( $_GET['date'] );
$time = fixString( $_GET['time'] );
$ou = fixString( $_GET['ou'] );

if($ou == ""){
$ou = dbo_CurrentUserValue($id, 'default_role');
$ou = explode("/", $ou);
$role = $ou[1];
$ou = $ou[0];
}

$start_time = $time;
//TODO should the interval be a user setting?
$end_time = (substr($start_time, 0, 2) + 1).":".substr($start_time, -2, 2);
//echo $end_time;

if($time == ""){
  $all_day = " checked ";
}

if($_POST){
  //TODO move to function
  //TODO check for correct ous and roles, error out on bad
  $kkeys = array_keys($_POST);
  foreach($kkeys as $k){
    $$k = fixString($_POST[$k]);
  }
  
  $bid = md5($title.mktime().rand(0,9));
  $start_time = $date." ".$start_time;
  $end_time = $date." ".$end_time;
 
  $sql = "
  INSERT INTO blocks
  (bid, title, start_time, end_time, created_by, created)
  VALUES
  ('$bid', '$title', '$start_time', '$end_time', '$id', now())
  ";
  db_query($sql);

  $sql = "
  INSERT INTO participants
  (bid, id, ou_code, role, created, created_by, attending)
  VALUES
  ('$bid', '$id', '$ou_code', '$role', now(), '$id', '1')
  
  ";
  //echo $sql;
  db_query($sql);
  header("Location: $site_root?date=$date&view=agendaDay");
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type="text/javascript" src="<?echo $jquery_timePicker_path;?>"></script> 
<script type="text/javascript"> 
$(document).ready(function() {
  $("input[name=title]").focus();
});

  jQuery(function() {
    // Default.
    $("#time1").timePicker();
    // 02.00 AM - 03.30 PM, 15 minutes steps.
    $("#time2").timePicker({
  startTime: "02.00",  // Using string. Can take string or Date object.
  endTime: new Date(0, 0, 0, 15, 30, 0),  // Using Date object.
  show24Hours: false,
  separator:'.',
  step: 15});
    
    // An example how the two helper functions can be used to achieve 
    // advanced functionality.
    // - Linking: When changing the first input the second input is updated and the
    //   duration is kept.
    // - Validation: If the second input has a time earlier than the firs input,
    //   an error class is added.
    
    // Use default settings
    $("#time3, #time4").timePicker({
    show24Hours: false,
    step: 15
    });
        
    // Store time used by duration.
    var oldTime = $.timePicker("#time3").getTime();
    
    // Keep the duration between the two inputs.
    $("#time3").change(function() {
      if ($("#time4").val()) { // Only update when second input has a value.
        // Calculate duration.
        var duration = ($.timePicker("#time4").getTime() - oldTime);
        var time = $.timePicker("#time3").getTime();
        // Calculate and update the time in the second input.
        $.timePicker("#time4").setTime(new Date(new Date(time.getTime() + duration)));
        oldTime = time;
      }
    });
    // Validate.
    $("#time4").change(function() {
      if($.timePicker("#time3").getTime() > $.timePicker(this).getTime()) {
        $(this).addClass("error");
      }
      else {
        $(this).removeClass("error");
      }
    });
    
  });
</script> 
</head>
<body>
<?
drawHeader($id);

echo "<h3>$ou</h3>";
?>

<form name="new_appt" method="post" action="">
Title: <input type=text name="title" id="title" /><br />
Role: <select name="role">
<?
drawRolesSelection($id, $ou, $role);
?>
</select>
<div class="time">Start Time: <input type="text" name="start_time" id="time3" size="10" value="<?echo $start_time;?>" /> 
End Time: <input type="text" name="end_time" id="time4" size="10" value="<?echo $end_time;?>" /></div>
<input type=hidden name="date" value="<?echo $date;?>"><br />
<input type=hidden name="ou_code" value="<?echo $ou;?>"><br />
<input type=submit value="Save" />
</form>

