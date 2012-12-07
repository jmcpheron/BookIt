<?
include("common.php");
/*
session_start();
session_destroy();
session_start();
*/

  setcookie('id', '', time() - 3600);
  setcookie('hash', '', time() - 3600);

$page = $_GET['p'];
if(strlen($page) < 3){
  $page = $site_root;
}
header("Location: $page");
?>
