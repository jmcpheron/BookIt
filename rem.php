<?
include("common.php");
include_once("session.php");

$sql = "
select * from blocks
where created_by = '$id'
";
$results = db_query($sql);
//print_r($results);



$foo = dbo_getRangeOfAppointments($id, date('Y-m-d'), date('Y-m-d'));
print_r($foo);
?>
