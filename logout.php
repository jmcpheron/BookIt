<?
include("common.php");
session_start();
session_destroy();
session_start();
$page = $_GET['p'];
if(strlen($page) < 3){
  $page = $site_root;
}
header("Location: $page");
?>
