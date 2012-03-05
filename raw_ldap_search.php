<?
//function searchLdap($search){
include("config.php");

$array_name = explode('*', $query);
$ds=ldap_connect($ldap_server);  
  
//ldap_set_option($ds, LDAP_OPT_SIZELIMIT, 10);
      $r=ldap_bind($ds, $ldap_admin, $ldap_password);

      if(!$r) die("ldap_bind failed<br>");

  //TODO make this generic and store it in the database or a config file
  //The filter may need to be a function or something
  $search="ou=People,o=nocccd.edu,o=cp";
  $filter="(&(objectClass=*)(uid=$id))";
  $filter="(&(objectClass=*)(sn=McPhero*))";
  $filter="(&(objectClass=*)(displayname=*McPheron*))";
  $filter="(&(objectClass=*)(displayname=*$query*) )";
  //$filter="(&(objectClass=*)(givenname=".$array_name[0].")(sn=Ho))";
  //$filter="(&(objectClass=*)(displayname=".$search."))";
 
  $sr = ldap_search($ds, $search, $filter, array('sn', 'givenname', 'uid', 'displayname'), array(), 15, 10);
  $info = ldap_get_entries($ds, $sr);


//}
?>
