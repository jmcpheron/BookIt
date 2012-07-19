<?
include("config.php");
include("functions.php");

$query = $_GET['q'];

$array_name = explode('*', $query);

$query = str_replace(' ', '*', $query);
$ds=ldap_connect($ldap_server);  
  
//ldap_set_option($ds, LDAP_OPT_SIZELIMIT, 10);
      $r=ldap_bind($ds, $ldap_admin, $ldap_password);

      if(!$r) die("ldap_bind failed<br>");

  //TODO make this generic and store it in the database or a config file
  //The filter may need to be a function or something
  $search="ou=People,o=nocccd.edu,o=cp";
  $filter="(&(objectClass=*)(sn=McPhero*))";
  $filter="(&(objectClass=*)(displayname=*McPheron*))";
  $filter="(&(objectClass=*)(displayname=*$query*) )";
  //$filter="(&(objectClass=*)(givenname=".$array_name[0].")(sn=Ho))";
  //$filter="(&(objectClass=*)(displayname=".$search."))";
 
  @$sr = ldap_search($ds, $search, $filter, array('sn', 'givenname', 'uid', 'displayname'), 0, 11, 10 );
  @$info = ldap_get_entries($ds, $sr);

//TODO Security
//$info = array();
$sql = "
select id, firstname, lastname 
from person
where id not like '0%'
and lower(firstname) || ' ' || lower(lastname) like '%".strtolower($query)."%'
";
$results = db_query($sql);
if($results){
  foreach($results as $item){
    $info[] = array(
    0=>'sn',
    'sn'=>array('count'=>1,0=>$item['lastname']),
    1=>'givenname',
    'givenname'=>array('count'=>1,0=>$item['firstname']),
    2=>'uid',
    'uid'=>array('count'=>1,0=>$item['id']),
    3=>'displayname',
    'displayname'=>array('count'=>1,0=>$item['firstname'].' '.$item['lastname'])
    );
    $info['count'] = $info['count'] + 1;
  
  }
}

//print_r($info);
$json = json_encode($info);
echo $json;

?>
