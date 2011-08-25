<?
include("common.php");
session_start();
$hash = $_SESSION['hash'];
$id = $_SESSION['id'];

if($id < 1){
$id = 0;
}

$logged_in = false;
if($hash == md5($id.$salt)){
$logged_in = true;
}


if($logged_in == false){
  echo '{"error":"need to login", "status":"error"}';
  exit;
}
$key = fixString($_POST['key']);
$value = fixString($_POST['value']);
dboUpdateUserSettings($id, $key, $value);

echo '{"status":"success","key":"'.$key.'","value":"'.$value.'"}';

exit;

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
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
  $("input:radio").click(function() {
    var full_role = $(this).attr('id');
    $.post("post.php", { "full_role": full_role },
      function(data){
      alert(data.full_role); // John
       //console.log(data.time); //  2pm
     }, "json");
  });
});
</script>
</head>
<body>
<?
drawHeader($id);

$results = getOus($id);
foreach($results as $item){
  echo "<b>".$item['long_name']."</b>";
  echo "<br />";
  $roles = getRoles($id, $item['ou_code']);
  
  foreach($roles as $role){
    $full_role = $item['ou_code']."/".$role['role'];
    //echo "<span class=\"roles\" id=\"".$item['ou_code']."/".$role['role']."\">+</span>";
    echo "<input type=\"radio\" name=\"default_role\" value=\"\" class=\"roles\" id=\"$full_role\" />";
    echo "<label for=\"$full_role\">";
    echo $role['long_name'];
    echo "</label>";
    echo "<br />\n";
  }
}
?>
