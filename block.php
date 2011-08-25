<?
include("common.php");
include_once("session.php");
$bid = fixString($_GET['bid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />

</head>
<body>
<?
drawHeader($id);
$info = dbo_Appt($id, $bid);
//print_r($info);
//echo $bid;
echo "<h3>".$info[0]['title']."</h3>";
echo "<h4>".$info[0]['start_time']."</h4>";
echo "Participants<hr />";

echo "<table border=\"1\">";
echo "<tr><td>Participant</td><td>Role</td><td>Attending</td><td>Attendaing from</td><td>Details</td></tr>\n";
foreach($info as $item){
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
echo "<a href=\"\">...</a>";
echo "</td>";
echo "</tr>\n";

}
echo "</table>";


?>


</body>

</html>
