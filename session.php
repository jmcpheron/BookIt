<?
session_start();
$hash = $_SESSION['hash'];
$id = $_SESSION['id'];


if(!$id){
$id = 0;
}

$logged_in = false;
if($hash == md5($id.$salt)){
$logged_in = true;
}

if($logged_in == false){
  $page = urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  header("Location: ".$site_root."login.php?page=$page");
  exit;
  
}
?>
