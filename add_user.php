<?
include("common.php");
include_once("session.php");

$ou = fixString($_GET['ou']);
$role = fixString($_GET['role']);
$id = fixString($_GET['id']);

if($_POST){
  addRole($id, $ou, $role); 

  header("Location: users.php?ou=$ou&role=$role&added=$id");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
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

  <div class="row">

    <div class="span3">
      <div class="well">
        Helpful info
      </div>
    </div>


    <div class="span5 ">
      <div class="alert alert-block alert-info">
        <h4 class="alert-heading">Add User?</h4>
        Are you sure you want to add <?echo $id;?> to <?echo $ou." / ".$role;?>?
        <br /><br />
        <form action="" method="post" class="form">
          <input type="hidden" name="id" value="<?echo $id;?>">
          <input type="hidden" name="ou" value="<?echo $ou;?>">
          <input type="hidden" name="role" value="<?echo $role;?>">
          <button class="btn btn-success ">Yes, Add User</button> | 
          <a href="users.php?ou=<?echo $ou;?>&role=<?echo $role;?>" class="btn">No</a>
        </form>
      </div>
    </div>
  </div>

</div>
</div>
</body>
</html>
