<?

function fixString($str){
  $return = $str;
  $return = strip_tags($return);

  return $return;
}


function db_query($sql){
include("config.php");

//connect to a database named "mary" on the host "sheep" with a username and password

$connection = pg_connect("host=localhost port=5432 dbname=$db_name user=$db_user password=$db_pass");

// let me know if the connection fails
  if (!$connection) {
    print("Connection Failed.");
    exit;
  }

$result = pg_query($connection, $sql);
return pg_fetch_all($result);

}

function drawMyOUs($id){
  echo "<div id=\"ous\">";
  $results = getOus($id);
  foreach($results as $ou){
   echo "<a href=\"ou/?o=".$ou['ou_code']."\">".$ou['long_name']."</a><br />\n";
  }
  echo "</div>";
  
}

function drawRolesSelection($id, $ou, $role = null){
  $results = getRoles($id, $ou);
  $return = "";
  foreach($results as $item){
    $return.="<option value=\"".$item['role']."\"";
    if($item['role'] == $role){
      $return.=" SELECTED";
    }
    $return.=">".$item['role'];
    $return.="\n";
  }

   echo $return;

}

function drawCalControld($date, $current_view){
  $return = "";
  if($date == ""){
    $date = date('Y-m-d');
  }
 
  if($current_view  == 'month'){
    $prev = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " - 1 month"));
    $next = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " + 1 month"));
  }
  if($current_view  == 'agendaWeek'){
    $prev = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " - 1 week"));
    $next = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " + 1 week"));
  }
  if($current_view  == 'agendaDay'){
    $prev = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " - 1 day"));
    $next = date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " + 1 day"));
  }
  $return.="<a href=\"?date=$prev&view=$current_view\"><<</a> | ";
  $return.="<a href=\"?date=$next&view=$current_view\">>></a> | ";


  include("common.php");
  foreach($view_array as $view){
    if($current_view == $view){
      $return.= "$view | ";
    }else{
      $return.= "<a href=\"?date=$date&view=$view\">$view</a> | ";
    }
  }
echo  $return;
}


function drawHeader($id){
  $page = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  include("common.php");
  include("session.php");
  $person = dbo_person($id);
  echo "<div class=\"header\">";
  echo "<a href=\"$site_root\">$site_title</a>";
  echo "</div>";
  echo "<div id=\"top_notifications\">&nbsp;</div>";

  if($logged_in == true){
    echo "<div id=\"login\"><a href=\"profile.php\">".$person['firstname']." ".$person['lastname']."</a> | <a href=\"logout.php\">Logout</a></div>";
  }else{
  echo "<div id=\"login\">";
  //print_r($_SERVER);
  echo "
Please Login using your MyGateway ID and PIN<br />
<form action=\"".$site_root."login.php\" method=post>
<input type=\"hidden\" name=\"p\" value=\"$page\"/>
Username: <input type=\"text\" name=\"username\" value=\"$uname\"/>
Password: <input type=\"password\" name=\"password\" />
<input type=\"submit\" value=\"Login\" />
</form>
";

  echo "</div>";
  }
}

function drawMyAppointments($id, $day = null, $ou_code = null, $role = null){
  echo "<div id=\"appointments\" >";
  if($day == null){
    $day = date('Y-m-d');
  }
  $appointments = dbo_getDayOfAppointments($id, $day, $ou_code, $role);
  if($appointments){
    foreach($appointments as $appt){
      echo $appt['title'];
      echo "<br />";
    }
  }else{
    
  }
  echo "</div>";
}
function drawMyAppointmentsMonth($id, $day, $ou_code = null, $role = null, $className = null){
  if($className == null){
    $className = 'default';
  }
  $appointments = dbo_getMonthOfAppointments($id, $day, $ou_code, $role);
  if($appointments){
    $return = "";
    foreach($appointments as $appt){
      $return.= "{";
      $return.= "title: '".$appt['title']."',\n";
      $return.= "start: new Date(".$appt['start_time']."),\n";
      $return.= "end: new Date(".$appt['end_time']."),\n";
      $return.= "url: 'block.php?bid=".$appt['bid']."',\n";
      $return.= "className: ['".$appt['ou_code']."-".$appt['role']."'],\n";
      $return.= "color: '".$appt['color']."',\n";
      $return.= "allDay: false";
      $return.= "},\n\n";
    }
    $return = substr($return, 0, -1);
  }else{
    
  }
  echo $return;
}

function checkLogin($username, $password){

  //First let's check the LDAP server for login


  //This is the local login check
  $db_password = dbo_getPassword($username);
  $good_credentials = false;
  if($db_password[0]['content'] == md5($password)){
    $good_credentials = true;
  }
  return $good_credentials;
}

function drawHtmlHeader($js = null){

//<script type='text/javascript' src='fullcalendar-1.4.8/jquery/jquery-1.4.3.min.js'></script>
//<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.js'></script>
//<script type='text/javascript' src='fullcalendar-1.4.8/fullcalendar/fullcalendar.min.js'></script>
$return = "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<title>$site_title</title>
<link rel='stylesheet' type='text/css' href='fullcalendar-1.4.8/fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='css/jquery-ui-1.7.2.custom.css' />
<script type='text/javascript' src='fullcalendar-1.4.8/jquery/jquery-1.4.3.min.js'></script>
</head>

";

  echo $return;
}

function tryLdapAuth($username, $password){
include("config.php");
  $good_credentials = false;
  //TODO move this to a settings databse
  $url = $ldap_url;
  $data = array(
  'userid' => $username,
  'pin' => $password,
  'api' => $ldap_api
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, true);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $output = curl_exec($ch);
  //$info = curl_getinfo($ch);
  curl_close($ch);

  $results = json_decode($output);
 
  if(($results->authenticated == 'true') && ($results->message == 'Successful')){
    $good_credentials = true;
  }
  return $good_credentials;
}

function getLdapPersonInfo($id){
include("config.php");
  
  $ds=ldap_connect($ldap_server);  // assuming the LDAP server is on this host
  
      $r=ldap_bind($ds, $ldap_admin, $ldap_password);
      if(!$r) die("ldap_bind failed<br>");

  //TODO make this generic and store it in the database or a config file
  //The filter may need to be a function or something
  $search="ou=People,o=nocccd.edu,o=cp";
  $filter="(&(objectClass=*)(uid=$id))";
 
  $sr = ldap_search($ds, $search, $filter );
  $info = ldap_get_entries($ds, $sr);
 
  //TODO Move to databse?
  $lastname =  $info[0]['sn'][0];
  $firstname = $info[0]['givenname'][0];

  $return = array('lastname'=>$lastname, 'firstname'=>$firstname);
  dbo_insertOrUpdateLocalPerson($id, $firstname, null, $lastname, null);
  return $return;
}

function addRole($id, $ou, $role){
  //Check for existing role
  $exists = false;
  $current_roles = getRoles($id, $ou);
  if($current_roles){
  foreach($current_roles as $existing_role){
    if($role == $existing_role['role']){
      $exists = true;
    }
  }
  }
  if($exists == false){
    dbo_addRole($id, $ou, $role);
  }

}
?>
