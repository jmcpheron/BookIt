<?
include("common.php");
include_once("session.php");
include("permission_functions.php");


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
<title><?echo strip_tags($site_title);?> </title>
<?
echo $common_js;
?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>
  $("#search").focus();
$("#search").keyup( function(){
  var search = $(this).val();
  if(search.length >= 3){
    var term = $("#term").val();
    var url = "indexed_search.php?q=" + search;
      $.getJSON(url, function(data){
        $("#ajax").html("");
        $.each(data, function(index, objValue) {
          $("#ajax").append("<a href=\"lia_go.php?id=" + objValue.id + "\">" + objValue.first + " " + objValue.middle + " " + objValue.last + "</a><br />");
          });
        
      });
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
      //echo "Add a <span class=\"label label-info\">$role</span> to <span class=\"label label-info\">$ou</span><br /><br />";
      ?>
      <form id="form">
      <input type="text" name="search" id="search" class="search-query" autocomplete="off"/>
      <br />
      <br />
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
