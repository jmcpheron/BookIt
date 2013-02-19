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
<title><?echo strip_tags($site_title);?> </title>
<?
echo $common_js;
echo $common_css;
?>
<script type='text/javascript'>
$(document).ready(function() {
  $("input[name=username]").focus();
});
</script>
</head>
<body>
<?
if(!$uname){
  $uname = $_GET['preun'];
}

//Unlogged in header
echo '
    <div class="navbar navbar-fixed-top" >
      <div class="navbar-inner">
        <div class="container">
          <h3><a href="'.$site_root.'" class="brand" ></a></h3>
        </p>
      </div><!-- container?-->
      </div><!-- /navbar-inner -->
    </div><!-- /navbar -->
';


//TODO Change login instructions to a message an administrator can edit


echo "
<div class=\"container\">
<div class=\"row\">
  <div class=\"span4 well\">
  <form action=\"".$site_root."login.php\" method=post class=\"form-vertical\">
    <fieldset>
      <legend>Please login using your MyGateway ID and PIN</legend>
      <div class=\"control-group\">
        <input type=\"hidden\" name=\"p\" value=\"$page\"/>
        <label class=\"control-label\" for=\"username\">Username: </label>
        <div class=\"controls\">
          <input type=\"text\" name=\"username\" value=\"$uname\"/>
        </div>
      </div>
  
  
      <div class=\"control-group\">
        <label class=\"control-label\" for=\"password\">Password: </label>
        <div class=\"controls\">
          <input type=\"password\" name=\"password\" />
        </div>
      </div>
    </fieldset>
  <input type=\"submit\" value=\"Login\" class=\"btn btn-primary\" />
  </form>
  </div>


</div>
</div>
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
  /*
  session_start();
  $_SESSION['id'] = $username;
  $_SESSION['hash'] = md5($username.$salt);
  */
  
  $c_hash = md5($username.$salt);
  setcookie('id', $username, $cookie_time);
  setcookie('hash', $c_hash, $cookie_time);
  
  
  $success = true;

  header("Location: http://$page");

}else{
  //echo "Error. Could be a bad username or password";
}
//Try local password
if(checkLogin($username, $password) == true){
  /*
  session_start();
  $_SESSION['id'] = $username;
  $_SESSION['hash'] = md5($username.$salt);
  */
  
  $c_hash = md5($username.$salt);
  setcookie('id', $username, $cookie_time);
  setcookie('hash', $c_hash, $cookie_time);
  $success = true;

  //TODO Remove for production
  addRole($username, 'bookit', 'charter');
  include("demo_functions.php");
  //addSomeAppts($username);
  //END remove

  header("Location: http://$page");
}


if($success == false){

  echo "Error. Could be a bad username or password";
  exit;
}

?>
