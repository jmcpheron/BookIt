<?
//print_r($_POST);
include("common.php");

//Make it just a web form if someone loads the page without a POST
if(!$_POST){
$page = fixString($_GET['page']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $site_title;?> </title>
<script type='text/javascript' src='<?echo $jquery_path;?>'></script>
<script type='text/javascript'>
$(document).ready(function() {
  $("input[name=username]").focus();
});
</script>
</head>
<body>
<?
//TODO Change login instructions to a message an administrator can edit
echo "
Please Login using your MyGateway ID and PIN<br />
<form action=\"".$site_root."login.php\" method=post>
<input type=\"hidden\" name=\"p\" value=\"$page\"/>
Username: <input type=\"text\" name=\"username\" value=\"$uname\"/>
Password: <input type=\"password\" name=\"password\" />
<input type=\"submit\" value=\"Login\" />
</form>
";
exit;
}

$username = fixString($_POST['username']);
$password = fixString($_POST['password']);
$page = fixString($_POST['p']);

//TODO Username character filter and length checking should be configured by web user and stored in the database
  
$pattern = '/[^a-z0-9]/i';
$username = preg_replace($pattern, '', $username);
 
//TODO Remove len check
if(strlen($password) != 6){
  $log = "username:".$username.",password_len:".strlen($password);
  miscLog($log);
  if(strlen($password) > 6){
    $password = substr($password, 0, 6);
  }
}
if(strlen($username) != 8){
  $log = "username:".$username.",username_len:".strlen($username);
  miscLog($log);
}
//END


$success = false; //so far

if(tryLdapAuth($username, $password) == true){
  //Cache password
  dbo_insertOrUpdateLocalPassword($username, $password);

  //TODO Remove for production
  addRole($username, 'bookit', 'charter');
  include("demo_functions.php");
  addSomeAppts($username);
  //END remove
  
  getLdapPersonInfo($username);
  session_start();
  $_SESSION['id'] = $username;
  $_SESSION['hash'] = md5($username.$salt);
  
  $success = true;

  header("Location: http://$page");

}else{
  //echo "Error. Could be a bad username or password";
}

//Try local password
if(checkLogin($username, $password) == true){
  session_start();
  $_SESSION['id'] = $username;
  $_SESSION['hash'] = md5($username.$salt);
  
  $success = true;

  header("Location: http://$page");
}


if($success == false){

  echo "Error. Could be a bad username or password";
  exit;
}

?>
