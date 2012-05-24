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
<?
echo $common_js;
?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>
  $("#search").focus();

  $("#form").submit(function(){
    $("#spinner").show();
    $("#ajax").html("");
    $("#error").hide();
    var q = $("input[name=search]").val();
    var send_to_url = "add_user.php?ou=<?echo $ou;?>&role=<?echo $role;?>&id=";
    $.getJSON(
      "rem_d.php?q=" + q,
      {},
      function(data){
        //debugger;
        $("#spinner").hide();
        for ( var i = 0; i < data.count; i++){
          $("#ajax").append("<a href='" + send_to_url + data[i].uid[0] + "'>" + data[i].displayname[0] + ' (@' + data[i].uid[0] + ')</a><br />');
        }

       if(i >= 15){
         $("#error").show();
         $("#error").html('<div class="alert alert-error">There may be more matches, try refining your search.</div>');
       }
       if(i == 0){
         $("#error").show();
         $("#error").html('<div class="alert alert-message ">No matches</div>');

       }

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

  </div>

  <div class="row">
    <div class="span12">
      <ul class="breadcrumb">
      <li><a href="profile.php">Profile</a> / </li>
      <li><a href="profile.php#admin">Admin</a> / </li>
      <li>Users</li>
      </ul>
    </div>
  </div>

  <div class="row">

    <div class="span3">
      <div class="well">
        Helpful info
      </div>
    </div>


    <div class="span5 well">
      <?
      echo "Add a <span class=\"label label-info\">$role</span> to <span class=\"label label-info\">$ou</span><br /><br />";
      ?>
      <form id="form">
      <input type="text" name="search" id="search" class="search-query" autocomplete="off"/>
      <br />
      <br />
      <input type="submit" class="btn" value="Search">
      </form>

      <div id="error" class="span4"></div>
      <div class="span5" id="spinner" style="display:none;">
      <img src="images/ajax-loader.gif">
      </div>
      <div class="span5" id="ajax">
      </div>

    </div>
  </div>

  <div class="row">

    <div class="span3">
      &nbsp;
    </div>

    <div class="span5">
    <h3>Current Users</h3>
<?
echo "<table class=\"table table-bordered table-striped\">\n";
echo "<thead><tr><th>ID</th><th>First</th><th>Last</th></thead>";
echo "<tbody>";
$users_list = listUsersInRole($ou, $role, $limit, $offset);
foreach($users_list as $user){
  $this_id = $user['id'];
  echo "<tr>";
  echo "<td><a href=\"#\" title=\"User details\">$this_id</a></td>";
  echo "<td>".$user['firstname']."</td>";
  echo "<td>".$user['lastname']."</td>";
  echo "</tr>\n";
}
echo "</tbody>";
echo "</table>\n";
?>
    </div>
  </div>

</div>
</div>
</body>
</html>
