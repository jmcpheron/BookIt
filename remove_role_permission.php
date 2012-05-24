<?
include("common.php");
include_once("session.php");

include("permission_functions.php");
if($_POST){
  $ou = fixString($_POST['ou']);
  $role = fixString($_POST['role']);
  $permission = fixString($_POST['p']);
  $affected = fixString($_POST['v']); 
  deleteRolePermission($ou, $role, $permission, $affected);
  header("Location: permissions.php?ou=$ou&role=$role");
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
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
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
  <div class="alert-message block-message warning span8">
  <h2>Are you sure you want to remove this permission?</h2>
  <table class="bordered-table">
  <tr><td><b>OU</b></td><td><?echo $ou;?></td></tr> 
  <tr><td><b>Role</b></td><td><?echo $role;?></td></tr> 
  <tr><td><b>Permission</b></td><td><?echo $permission;?></td></tr> 
  <tr><td><b>Affected</b></td><td><?echo $affected;?></td></tr> 

  </table>
  <form action="" method="post" name="remove_permission">
  <input type="hidden" name="ou" value="<?echo $ou;?>" />
  <input type="hidden" name="role" value="<?echo $role;?>" />
  <input type="hidden" name="p" value="<?echo $permission;?>" />
  <input type="hidden" name="v" value="<?echo $affected;?>" />
  <input type="submit" class="btn danger" value="Yes, Remove Role's Permission" /> | <a href="permissions.php?ou=<?echo $ou;?>&role=<?echo $role;?>" class="btn" >Cancel</a>
  </form>
  </div>
</div>
</div>
</body>
</html>
