<?
include("common.php");
include_once("session.php");
$date = fixString( $_GET['date'] );
$time = fixString( $_GET['time'] );

if($time == ""){
  $all_day = " checked ";
}

if($_POST){
  echo "Process request";
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
  $("input:radio").click(function() {
    var full_role = $(this).attr('id');
    var css_key = $(this).attr('name');
    $.post("update_user_settings.php", { "key": css_key, "value": full_role },
      function(data){
      if(data.status == 'error'){
        window.location = "<?echo $site_root;?>";
      }
      if(data.status == 'success'){
        $("div#top_notifications").html("Prefrences saved. \"" + data.key + "\" set to \"" + data.value + "\"" );
        $("div#top_notifications").addClass("notify");
        $("div#top_notifications").addClass("alert-message");
      }
     }, "json");
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
//TODO Turn this into a more generic function
//Get current value
echo "\n";

echo "<div class=\"blah\">";

echo "<form class=\"form-stacked\">";
echo "<h2>Default Role</h2>\n";
$current_default_role = dbo_CurrentUserValue($id, 'default_role');

$results = getOus($id);
if($results){
foreach($results as $item){
  echo "<h3>".$item['long_name']."</h3>";
  echo "<br />";
  $roles = getRoles($id, $item['ou_code']);
  
  foreach($roles as $role){
    $full_role = $item['ou_code']."/".$role['role'];
    echo "<label for=\"$full_role\">";
    echo "<input type=\"radio\" name=\"default_role\" value=\"$full_role\" class=\"roles\" id=\"$full_role\" ";
      if($full_role == $current_default_role){
        echo "CHECKED";
      }
    echo ">\n";
    echo $role['long_name'];
    echo "</label>\n";
    echo "<br />\n";
  }
}
}
//Default View
echo "<h2>Default View</h2>";
$current_default_view = dbo_CurrentUserValue($id, 'default_view');
  
  foreach($view_array as $view){
    $full_role = $item['ou_code']."/".$role['role'];
    //echo "<span class=\"roles\" id=\"".$item['ou_code']."/".$role['role']."\">+</span>";
    echo "<label>";
    echo "<input type=\"radio\" name=\"default_view\" value=\"$view\" id=\"$view\" ";
      if($view == $current_default_view){
        echo "CHECKED";
      }
    echo ">\n";
    echo "<span>$view</span>";
    echo "</label>\n";
    //echo "<br />\n";
  }


//Default Time Slot Size
echo "<h2>Default Time Slot Size</h2>";
$current_default_view = dbo_CurrentUserValue($id, 'slot_size');
  
  foreach($slot_array as $slot){
    $full_role = $item['ou_code']."/".$role['role'];
    //echo "<span class=\"roles\" id=\"".$item['ou_code']."/".$role['role']."\">+</span>";
    echo "<label>";
    echo "<input type=\"radio\" name=\"slot_size\" value=\"$slot\" id=\"$slot\" ";
      if($slot == $current_default_view){
        echo "CHECKED";
      }
    echo ">\n";
    echo "<span>$slot</span>";
    echo "</label>\n";
    //echo "<br />\n";
  }
?>
  </div>
</div>
<a href="index.php" class="btn primary">Done</a>
</form>

</div>
</div>
</body>
</html>
