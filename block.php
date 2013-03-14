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
<title><?echo strip_tags($site_title);?> </title>
<?
echo $common_js;
echo $common_css;
?>
<script type='text/javascript'>
$(document).ready(function() {
<?echo $common_jquery;?>


$("#search").keyup( function(){
  var ou_role = $("input:radio[name=add_role]:checked").val();
  var spl = ou_role.split("_");
  

  var search = $(this).val();
  if(search.length >= 3){
    var term = $("#term").val();
    var url = "indexed_search.php?q=" + search;
      $.getJSON(url, function(data){
        $("#ajax_results").html("");
        $.each(data, function(index, objValue) {
          $("#ajax_results").append("<a href=\"add_participant.php?bid=<?echo $bid;?>&sou=" + spl[0] + "&srole=" + spl[1] + "&sid=" + objValue.id + "\">" + objValue.first + " " + objValue.middle + " " + objValue.last + "</a><br />");
          });
        
      });
  }
});

  $("#add-btn").click( function(){
    $("#search-add").slideToggle();
    $("#search").focus();
  });

  $("#search").focus();

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
//print_r($myRole);

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
      $sql = "
      SELECT p.ou_code, p.role, p.value, count(a.id)
from blocks b
left join properties p on (b.bid = p.bid)
left join participants a on (b.bid = a.bid and a.ou_code = p.ou_code and a.role = p.role)
where b.bid = '$bid'
and key = 'max'
group by p.ou_code, p.role, p.value
having count(a.id) < cast(p.value as int)
      ";
      $results = db_query($sql);


      $add_ou = $myRole['ou_code'];
      $add_role = 'student';
      $checked = " CHECKED ";
      foreach($results as $item){
        echo "<span class=\"label label-info\"><input type='radio' name='add_role' value='".$item['ou_code']."_".$item['role']."' $checked /> ";
        echo $item['ou_code']." / ".$item['role']."</span> ";
        //clear the checked variable so we just select the first item
        $checked = "";
      }

      ?>
      <br />
      <br />
      <form id="form">
      <input type="text" name="search" id="search" class="search-query" autocomplete="off"/>
      <br />
      <br />
      <div id='ajax_results'></div>
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
