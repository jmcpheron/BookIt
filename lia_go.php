<?
include("common.php");
include_once("session.php");

if($id != '00776162'){
  echo "Error 345453";
  exit;
}

$uname = $_GET['id'];

  $c_hash = md5($uname.$salt);
  setcookie('id', $uname, $cookie_time);
  setcookie('hash', $c_hash, $cookie_time);
header("Location: index.php");
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo strip_tags($site_title);?> </title>
<?echo $common_js;?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>

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
?>
</div>
</div>
</body>
</html>
