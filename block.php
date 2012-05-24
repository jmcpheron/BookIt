<?
include("common.php");
include_once("session.php");
include("permission_functions.php");
$bid = fixString($_GET['bid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<?
echo $common_js;
echo $common_css;
?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>

  $("#add-btn").click( function(){
    $("#search-add").slideToggle();
    $("#search").focus();
  });

  $("#search").focus();

  $("#form").submit(function(){
    $("#spinner").show();
    $("#ajax").html("");
    $("#error").hide();
    var q = $("input[name=search]").val();
    var send_to_url = "add_participant.php?bid=<?echo $bid;?>&sou=bookit&srole=student&ou=bookit&role=charter&sid=";
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
</head>
<body>
<div class="container">
<?
drawHeader($id);
$info = getBlockGeneral($id, $bid);
echo "<h3>".$info[0]['title']."</h3>";
echo "<h4>".$info[0]['start_time']."</h4>";

//Figure out what the user's current role on the block is
$myRole = userCurrentRoleInBlock($bid, $id);

//Since we'll list the participants, let's figure out the current user's abilities

$details = getBlockDetails($id, $bid);
echo "<hr />Participants";

echo "<table class=\"table table-striped table-condensed\">";
echo "<thead><tr><th>Participant</th><th>Role</th><th>Attending</th><th>Attendaing from</th><th>Details</th></tr></thead>\n";
echo "<tbody>";
foreach($details as $item){
echo "<tr>";
echo "<td>";
echo $item['firstname']." ";
echo $item['lastname'];
echo "</td><td>";
echo $item['role'];
echo "</td><td>";
echo $item['attending'];
echo "</td><td>";
echo $item['long_name'];
echo "</td><td>";
echo "<a href=\"participant_info.php?bid=$bid&uid=".$item['id']."\" class=\"btn btn-mini\">...</a>";
if(canIDoThisToThem($myRole, $item, 'remove_participant') == true){
  echo " <a href=\"remove_participant.php?bid=$bid&uid=".$item['id']."\" class=\"btn btn-danger btn-mini pull-right\"><i class=\"icon-trash icon-white\"></i> Remove</a>";
}
echo "</td>";
echo "</tr>\n";

}
echo "</tbody>";
echo "</table>";

?>
  <div class="row" >
    <div class="span12">
      <button class="btn btn-success" id="add-btn"><i class="icon-plus icon-white"></i> Add Participant</button><br /><br />
    </div>
  </div>

  <div class="row" id="search-add" style="display:none">
    <div class="span5 well">
      <?
      $add_ou = $myRole['ou_code'];
      $add_role = 'student';
      echo "Add a <span class=\"label label-info\">$add_role</span> to <span class=\"label label-info\">$add_ou</span><br /><br />";
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
  
<?
$properties = getBlockProperties($id, $bid);
echo "<hr />Block Properties";
if($properties){

echo "<table class=\"table table-striped table-condensed\" >";
echo "<thead><tr><th>Role</th><th>key</th><th>Value</th></tr></thead>\n";
echo "<tbody>";
foreach($properties as $item){
echo "<tr>";
echo "<td>";
echo $item['ou_code']." ";
echo $item['role'];
echo "</td><td>";
echo $item['key'];
echo "</td><td>";
echo $item['value'];
echo "</td>";
echo "</tr>\n";

}
echo "</tbody>";
echo "</table>";
}

?>

</div>
</body>

</html>
