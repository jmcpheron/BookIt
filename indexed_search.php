<?
include("config.php");
include("functions.php");
include("common.php");

$query = $_GET['q'];
$query = str_replace(" ", "%", $query);
$query = str_replace("'", "''", $query);

$hash = $_COOKIE['hash'];
$id = $_COOKIE['id'];


if(!$id){
$id = 0;
}

$logged_in = false;
if($hash == md5($id.$salt)){
$logged_in = true;
}else{
  //TODO Error code
  exit;
}


//TODO Security
//$info = array();
$sql = "
select id, first, middle, last
from indexed_person
where lower(first) || ' ' || lower(middle) || lower(last) like '%".strtolower($query)."%'
";
$results = db_query($sql);

//print_r($info);
$json = json_encode($results);
echo $json;

?>
