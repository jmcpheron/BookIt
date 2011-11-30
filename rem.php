<?
include("common.php");
include_once("session.php");
include("demo_functions.php");

$pattern = '/\D/i';
$pattern = '/[^a-z0-9]/i';
$id = "@00123456";
echo preg_replace($pattern, '', $id);
echo "<br />";
$id = "00123456";
echo preg_replace($pattern, '', $id);
$id = "demo0001";
echo "<br />";
echo preg_replace($pattern, '', $id);
exit;

$foo = addSomeAppts($id);
print_r($foo);
exit;

$sql = "
select * from blocks
where created_by = '$id'
";
$results = db_query($sql);
//print_r($results);


$day =  strtotime(date('Y-m-d', time()));
echo $day;
echo "<br />";
$first_appt = date('Y-m-d H:i:00', $day + (9 *60 * 60));

$c = 0;
while($c <= 5){
  echo $first_appt;
  $first_appt = date('Y-m-d H:i:00', strtotime($first_appt) + (30 * 60));

  echo "<br />";

  $c++;
}

//echo date('Y-m-d H:i:00', time());

$foo = dbo_getRangeOfAppointments($id, date('Y-m-d'), date('Y-m-d'));
$foo = dbo_getRangeOfAppointments($id, '2011-09-01', '2011-09-14');
//print_r($foo);
?>
