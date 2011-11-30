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
echo $common_css;
?>
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

echo "<table border=\"1\" class=\"zebra-striped\">";
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
if(canIDoThisToThem($myRole, $item, 'remove_participant') == true){
  echo " <a href=\"#\" class=\"btn danger pull-right\">Remove</a>";
}
echo "</td>";
echo "</tr>\n";

}
echo "</tbody>";
echo "</table>";

$properties = getBlockProperties($id, $bid);
echo "<hr />Block Properties";
if($properties){

echo "<table border=\"1\">";
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
