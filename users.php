<?
include("common.php");
include_once("session.php");
include("permission_functions.php");
$ou = fixString($_GET['ou']);
$role = fixString($_GET['role']);
$page = fixString($_GET['p']);

$offset = 0;
if(!$page){
  $page = 1;
}
$limit = 50;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<style>
li {cursor:pointer;}
</style>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
  $("#form").submit(function(){
    $("#spinner").show();
    $("#ajax").html("");
    var q = $("input[name=search]").val();
    $.getJSON(
      "ldap_search.php?q=" + q,
      {},
      function(data){
        //debugger;
        $("#spinner").hide();
        $("#ajax").append("<ul>");
        for ( var i = 0; i < data.count; i++){
          //$("#ajax").append("<a href='" + data[i].uid[0] + "' >" + data[i].displayname[0] + ' (@' + data[i].uid[0] + ')</a><br />');
          $("#ajax").append("<li><a href='add_user.php?ou=<?echo $ou;?>&role=<?echo $role;?>&id=" + data[i].uid[0] + "'>" + data[i].displayname[0] + ' (@' + data[i].uid[0] + ')</a></li>');
        }
        $("#ajax").append("</ul>");
      }
    );

    return false;
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
  <div class="row">
    <div class="span4">
      <form id="form">
      <input type="text" name="search" id="search" value="McPheron" />
      <br />
      <br />
      <input type="submit" class="btn" value="Search">
      </form>

      <div class="span6" id="spinner" style="display:none;">
      <img src="images/ajax-loader.gif">
      </div>
      <div id="ajax">
      </div>

    </div>

    <div class="span10 offset1">
<?
echo "<table class=\"zebra-striped\">\n";
$users_list = listUsersInRole($ou, $role, $limit, $offset);
foreach($users_list as $user){
  echo "<tr>";
  echo "<td>".$user['lastname']."</td>";
  echo "<td>".$user['firstname']."</td>";
  echo "</tr>\n";
}
echo "</table>\n";
?>
    </div>

  </div>


</div>
</div>
</body>
</html>
