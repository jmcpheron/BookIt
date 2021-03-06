<?
include("common.php");
include_once("session.php");
$bid = fixString($_GET['bid']);

$sid = fixString($_GET['sid']);
$sou = fixString($_GET['sou']);
$srole = fixString($_GET['srole']);
$ou = fixString($_GET['ou']);
$role = fixString($_GET['role']);
$s = fixString($_GET['s']);

$info = getBlockGeneral($id, $bid);

$bid_date = substr($info[0]['start_time'], 0, 10);

$extra_array = array(
  'sid'=>$sid,
  'sou'=>$sou,
  'srole'=>$srole,
  'ou'=>$ou,
  'role'=>$role,
  's'=>$s,
);
$query_string = http_build_query($extra_array);
$back_to_search = "place.php?date=$bid_date&$query_string";


if($_POST){
 
  addParticipant($bid, $sid, $sou, $srole, $id, '1');
  header("Location: index.php?date=$bid_date");
  exit;
}
getLdapPersonInfo($sid);
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
});
</script>
</head>
<body>
<div class="container">
<?
drawHeader($id);

$place_name = getName($sid);
echo "
<div class=\"alert alert-info\">
  <a class=\"close\" href=\"$back_to_search\">x</a>
  <p><strong>Add $place_name (@$sid) to this Block?</strong><br />
  Well?
  </p>

  <div class=\"alert-actions\">
  <form action=\"\" method=\"post\">
  <input type=\"hidden\" name=\"bid\" value=\"$bid\">
  <input type=\"submit\" class=\"btn btn-primary\" value=\"Add $srole\"> 
  <a class=\"btn small\" href=\"$back_to_search\">Cancel</a>
  </form>
  </div>
</div>
";

echo "<h3>".$info[0]['title']."</h3>";
echo "<h4>".$info[0]['start_time']."</h4>";



$details = getBlockDetails($id, $bid);
echo "<hr />Participants";

echo "<table class=\"table table-bordered table-stripes table-condensed\">";
echo "<thead><tr><th>Participant</th><th>Role</th><th>Attending</th><th>Attendaing from</th><th>Details</th></tr></thead>\n";
echo "<tbody>";
foreach($details as $item){
echo "<tr>";
echo "<td>";
echo $item['firstname']." ";
echo $item['lastname'];
echo "</td><td>";
//echo $item['ou_code'];
echo $item['role'];
echo "</td><td>";
echo $item['attending'];
echo "</td><td>";
echo $item['long_name'];
echo "</td><td>";
echo "<a href=\"participant_info.php?bid=$bid&uid=".$item['id']."\" class=\"btn\">...</a>";
echo "</td>";
echo "</tr>\n";

}
echo "</tbody>";
echo "</table>";

$properties = getBlockProperties($id, $bid);
echo "<hr />Block Properties<br />";
if($properties){

echo "<table class=\"table table-bordered table-stripes table-condensed\">";
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
