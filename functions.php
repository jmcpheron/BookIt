<?

function fixString($str){
  $return = $str;
  $return = strip_tags($return);
  $return = addslashes($return);

  return $return;
}


function db_query($sql){
include("config.php");

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

function drawCalControld($date, $current_view, $extra_array = null){
  if($extra_array){
    $query_string = http_build_query($extra_array);
  }
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
  $return.="<a class=\"btn info\" href=\"?date=$prev&view=$current_view&$query_string\">&larr;</a> ";
  $return.="<a class=\"btn info\" href=\"?date=$next&view=$current_view&$query_string\">&raquo;</a> &nbsp; ";


  include("common.php");
  foreach($view_array as $view){
    if($current_view == $view){
      $return.= "<a class=\"btn disabled\">$view</a> ";
    }else{
      $return.= "<a class=\"btn primary\" href=\"?date=$date&view=$view&$query_string\">$view</a> ";
    }
  }
echo  $return;
}

function getName($id){
  $person = dbo_person($id);
  $return = "[Name not found]";
  if($person){
    $return = $person['firstname'];
    $return .= " ";
    $return .= $person['middlename'];
    $return .= " ";
    $return .= $person['lastname'];
  }
  //$return = $person;
  return $return;
}

function drawHeader($id){
  $page = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  include("common.php");
  include("session.php");
  $person = dbo_person($id);

echo '
  <div class="topbar-wrapper" style="z-index: 5;">
    <div class="topbar" data-dropdown="dropdown" >
      <div class="topbar-inner">
        <div class="container">
          <h3><a href="'.$site_root.'">'.$site_title.'</a></h3>
<!--
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle">Dropdown</a>
              <ul class="dropdown-menu">
                <li><a href="#">Secondary link</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Another link</a></li>
              </ul>
            </li>
          </ul>
          <form class="pull-left" action="">
            <input type="text" placeholder="Search" />
          </form>
-->
        <p class="pull-right">Logged in as 
        <a href="profile.php">'.$person['firstname'].' '.$person['lastname'].'</a> | 
        <a href="logout.php">Logout</a></p>
        </div>
          <div id="top_notifications" class="span8">
          </div>
      </div><!-- /topbar-inner -->
    </div><!-- /topbar -->
  </div><!-- /topbar-wrapper -->
';

/*
  echo "<div class=\"header\">";
  echo "<a href=\"$site_root\">$site_title</a>";
  echo "</div>\n";
*/
  //echo "<div id=\"top_notifications\">&nbsp;<br /><br /><br /></div>\n";

  if($logged_in == true){
    //echo "<div id=\"login\"><a href=\"profile.php\">".$person['firstname']." ".$person['lastname']."</a> | <a href=\"logout.php\">Logout</a></div>\n";
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
      //$return.= "backgroundColor: 'darkred',\n";
      //$return.= "textColor: 'darkred',\n";
      $return.= "allDay: false";
      $return.= "},\n\n";
    }
    $return = substr($return, 0, -1);
  }else{
    
  }
  echo $return;
}

function getOpenAppointments($ou_code = null, $role = null, $start_day, $end_day, $by_id = null, $by_ou = null, $by_role = null){
  $appointments = dbo_Search_Open_Appt($ou_code, $role, $start_day, $end_day, $by_id, $by_ou, $by_role);
  return $appointments;
}

function drawBlockByBid($bid, $page = null, $id = null){

  $appointments = dbo_getBid($bid,  $id);
  if($appointments){
    $return = "";
    foreach($appointments as $appt){
      //Set initial color from ou/role settings 
      $color = $appt['color'];

      //Check if a rule overrides this color setting
      $full = dbo_isBidFull($bid);
      if($full > 0){
        $color = 'darkred';
      }
      

      $return.= "{";
      $return.= "title: '".$appt['title']."',\n";
      $return.= "start: new Date(".$appt['start_time']."),\n";
      $return.= "end: new Date(".$appt['end_time']."),\n";
      if($page){
        $return.= "url: '$page',\n";
      }
      $return.= "className: ['".$appt['ou_code']."-".$appt['role']."'],\n";
      $return.= "color: '".$color."',\n";
      $return.= "allDay: false";
      $return.= "},\n\r\n\r";
    }
  }else{
    
  }
  //$return = substr($return, 0, -3);
  return $return;
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
  //TODO move this to a settings database
  
  

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

function getBlockGeneral($id, $bid){
  $info = dbo_Appt_General($id, $bid);
  return $info;
}

function getBlockDetails($id, $bid){

  $info = dbo_Appt_Details($id, $bid);
  return $info;
 
}

function getBlockProperties($id, $bid){
  $info = dbo_Block_Properties($id, $bid);
  return $info;
}

function newBlock($start_time, $end_time, $title, $created_by){
  //Return bid (Block ID)
  //Make up a bid
  $bid = md5($title.mktime().rand(0,9).$start_time);
  
  dbo_newBlock($bid, $start_time, $end_time, $title, $created_by);
  return $bid;
}

function addParticipant($bid, $id, $ou_code, $role, $created_by, $attending = null){

  dbo_addParticipant($bid, $id, $ou_code, $role, $created_by, $attending);
}

function addProperty($bid, $id, $ou_code, $role, $key, $value, $created_by){
  dbo_addProperty($bid, $id, $ou_code, $role, $key, $value, $created_by);
}

function getRangeOfAppointments($id, $start_day, $end_day, $ou_code = null, $role = null){
  $results = dbo_getRangeOfAppointments($id, $start_day, $end_day, $ou_code, $role);
  return $results;
}

function getUserSettingValue($id, $setting_name){
  $results = dbo_CurrentUserValue($id, $setting_name);
  return $results;
}

function getOuLongName($ou){
  $results = dbo_ouLongName($ou);
  return $results;
}

function miscLog($log){
  $results = dbo_miscLog($log);
  return $results;
}

?>
