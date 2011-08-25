<?
include("common.php");
session_start();
session_destroy();
session_start();
header("Location: $site_root");
?>
