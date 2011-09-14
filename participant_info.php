<?
include("common.php");
include_once("session.php");
$bid = fixString($_GET['bid']);
$uid = fixString($_GET['uid']);
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
$info = getBlockGeneral($id, $bid);
echo "<h3><a href=\"block.php?bid=$bid\">".$info[0]['title']."</a></h3>";
echo "<h4>".$info[0]['start_time']."</h4>";


