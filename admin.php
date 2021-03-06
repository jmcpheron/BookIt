<?
include("common.php");
include_once("session.php");
include("permission_functions.php");
$ou = fixString($_GET['ou']);
$role = fixString($_GET['role']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript' src='<?echo $bootstrap_modal_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
  $("select").change( function(){
    var this_id = $(this).prop('id');
    var this_value = $(this).val();
    //alert(this_id + ' ' + this_value)

    //Ajax stuff
    $.post("update_role_permissions.php", { "ou": "<?echo $ou;?>", "role": "<?echo $role;?>", "key": this_id, "value": this_value },
      function(data){
      if(data.status == 'error'){
        window.location = "<?echo $site_root;?>";
      }
      if(data.status == 'warning'){
        alert(data.message);
      }
      if(data.status == 'success'){
        var remove_link = "remove_role_permission.php?ou=<?echo $ou;?>&role=<?echo $role;?>&p=" + this_id + "&v=" + this_value;
        $("#modal-from-dom").modal('hide');
        $("#permissions-table tbody").append("<tr><td>" + this_id + "</td><td>" + this_value + "</td><td><a href='" + remove_link + "' class='btn danger'>x</a></td></tr>");
    
        //Reset form
        $("select").val($("option:first").val());
      }
     }, "json");

  });

  $("#reset").click( function(){
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
?>
<ul class="breadcrumb">
<li><a href="profile.php">Profile</a> / </li>
<li><a href="profile.php#admin">Admin</a> / </li>
<li>Roles</li>
</ul>

          <!-- modal content -->
          <div id="modal-from-dom" class="modal hide fade">
            <div class="modal-header">
              <a href="#" class="close">&times;</a>
              <h3>Choose Permission</h3>
            </div>
            <div class="modal-body">
<?

$roles = listRolesInOu($ou);
function drawSelection($id, $roles){
$selection = "
<select class=\"span6\" class=\"pick-affected\" id=\"$id\">
<option >Choose affected role(s)</option>
<option value=\"all\">All</option>
";
foreach($roles as $r){
  $selection.="<option value=\"".$r['role']."\">".$r['role']."</option>";
}
$selection.="
<option value=\"self\">Self</option>
</select>
";
return $selection;
}

$permissions = listAvailablePermissions();
echo "<table>";
echo "<tr><th>Permission Code</th><th>Description</th><th>Affects</th></tr>\n";
foreach($permissions as $p){
  echo "<tr>";
  echo "<td>";
  echo $p['permission'];
  echo "</td><td>";
  echo $p['description'];
  echo "</td><td>";
  echo drawSelection($p['permission'], $roles);
  echo "</td>";
  echo "</tr>";
}
echo "</table>";
?>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn primary">Primary</a>
              <a href="#" class="btn secondary close">Close</a>
            </div>
          </div>

<?
echo "<h2>Permissions for: $ou / $role</h2>";
echo "<button data-controls-modal=\"modal-from-dom\" data-backdrop=\"true\" data-keyboard=\"true\" class=\"btn success\">+ Add Permission</button>";
echo "<table class=\"span6\" id=\"permissions-table\">\n";
echo "<thead>\n";
echo "<tr><th>Permission</th><th>Value</th><th>&nbsp;</th></tr>\n";
echo "</thead>";

echo "<tbody>\n";
$permissions = listRolePermissions($ou, $role);
foreach($permissions as $p){
  echo "<tr>";
  echo "<td>".$p['permissions']."</td>";
  echo "<td>".$p['affected']."</td>";
  echo "<td><a href=\"remove_role_permission.php?ou=$ou&role=$role&p=".$p['permissions']."&v=".$p['affected']."\" class=\"btn danger\">X</a></td>";
  echo "</tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";
?>
</div>
</div>
</body>
</html>
