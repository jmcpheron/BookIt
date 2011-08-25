<?
session_start();
$hash = $_SESSION['hash'];
$id = $_SESSION['id'];

if($id < 1){
$id = 0;
}

$logged_in = false;
if($hash == md5($id.$salt)){
$logged_in = true;
}

if($logged_in == false){
  $page = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  header("Location: login.php?page=$page");
  exit;
  
}
?>
