<?
include("common.php");
include_once("session.php");

$ou = fixString( $_GET['ou'] );

if($ou == ""){
$ou = dbo_CurrentUserValue($id, 'default_role');
$ou = explode("/", $ou);
$role = $ou[1];
$ou = $ou[0];
}
  $dows = array('U', 'M', 'T', 'W', 'R', 'F', 'S');

if($_POST){
  $interval = fixString($_POST['interval']);
  $appt_title = fixString($_POST['title']);

  $dow_to_use = array();

  foreach($_POST['dow'] as $p_dow){
    $dow_to_use[] = array_search($p_dow, $dows);
      
    }

  $l_date = $_POST['start_date'];
  while($l_date <= $_POST['end_date']){
    //Is it the right dow of the week?
    if(in_array( date('w', strtotime($l_date)), $dow_to_use) ){
      $each_slot =  strtotime($l_date." ".$_POST['start_time']);
      while($each_slot < strtotime($l_date." ".$_POST['end_time'])){
        $each_slot = $each_slot + ($interval * 60 );

        $start_appt = date('Y-m-d H:i:00', $each_slot);
        $end_appt = date('Y-m-d H:i:00', strtotime($start_appt) + ($_POST['interval'] * 60));
        $this_bid = newBlock($start_appt, $end_appt, $appt_title, $id);
        //Add myself to these blocks
        addParticipant($this_bid, $id, $ou, $role, $id, '1');
 
        //Make it an open appt
        addProperty($this_bid, $id, $ou, 'student', 'max', '1', $id);
      }
    }
    $l_date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($l_date)) . " +1 day") );
  }
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo strip_tags($site_title);?> </title>
<?echo $common_js;?>
<link rel='stylesheet' type='text/css' href='<?echo $jquery_timePicker_css;?>' />
<script type="text/javascript" src="<?echo $jquery_timePicker_path;?>"></script> 
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>

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
<?
echo $common_css;
?>
</head>
<body>
<div class="container">
<div class="content main">
<?
drawHeader($id);
$long_name = getOuLongName($ou);
echo "<h3>$long_name [$ou]</h3>";
echo "<h4>$role</h4>";

?>


<br />
<br />
<form method="post" action="" name="slots" class="form form-inline">
Title: <input type="text" name="title" class="span4" /> 
<br />
<br />
Days of the week:<br />
<?
foreach($dows as $dow){
  echo "<input type=\"checkbox\" name=\"dow[]\" value=\"$dow\" id=\"dow-$dow\"> <label for=\"dow-$dow\">$dow</label> | \n";
}
$start_time = "9:00 AM";
$end_time = "3:00 PM";
?>
<br />
<br />
<div class="time">Start Time: <input type="text" name="start_time" id="time3" size="10" value="<?echo $start_time;?>" class="span2" /> 

<br /><br />
End Time: <input type="text" name="end_time" id="time4" size="10" value="<?echo $end_time;?>" class="span2" /></div>
<br /><br />
Interval in minutes: <input type="text" name="interval" class="span1" value="30">
<br /><br />
Start date: <input type="text" name="start_date" class="span2" value="<?echo date('Y-m-d');?>">
<br /><br />
End date: <input type="text" name="end_date" class="span2" value="<?echo date('Y-m-d', strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +2 week") );?>">
<br /><br />

<input type="submit" class="btn btn-primary">
</form>
</div>
</div>
</body>
</html>
