<?
include("common.php");
include_once("session.php");
include("block_functions.php");
$bid = fixString($_GET['bid']);
$uid = fixString($_GET['uid']);

if($_POST){
  $bid = fixString($_POST['bid']);
  $uid = fixString($_POST['uid']);
  deleteParticipant($bid, $uid);

  //TODO Add an undo alert?
  header("Location: index.php");
  exit;
}

$ou = fixString($_GET['ou']);
$role = fixString($_GET['role']);
$permission = fixString($_GET['p']);
$affected = fixString($_GET['v']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<?
echo $common_js;
?>
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
$details = getBlockDetails($uid, $bid);
$details = $details[0];
?>
  <div class="alert-message block-message warning span8">
  <h2>Are you sure you want to remove this participant?</h2>
  <table class="bordered-table">
  <tr><td><b>Participant</b></td><td><?echo getName($uid);?></td></tr> 
  <tr><td><b>Role</b></td><td><?echo $details['role'];?></td></tr> 

  </table>
  <form action="" method="post" name="remove_participant">
  <input type="hidden" name="bid" value="<?echo $bid;?>" />
  <input type="hidden" name="uid" value="<?echo $uid;?>" />
  <button type="submit" class="btn btn-danger" ><i class="icon-trash icon-white"></i> Yes, Remove Participant</button> | <a href="block.php?bid=<?echo $bid;?>" class="btn" >Cancel</a>
  </form>
  </div>
</div>
</div>
</body>
</html>
