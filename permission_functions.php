<?

function canIDoThis($myinfo, $their_info, $permission){

  //Assume false
  $return = false;

$sql = "
select *
from role_permissions
where ou = '".$myinfo['ou_code']."'
and role = '".$myinfo['role']."'
and permissions = '$permission'
";
  $results = db_query($sql);
  if($results){
    foreach($results as $item){
      $affected = $item['affected'];
      if($affected == 'all'){
        $return = true;
      }

      if($affected == $their_info['role']){
        $return = true;
      }

      if( ($affected == 'self') && ($myinfo['id'] == $their_info['id'] )){
        $return = true;
      }
    }
  }
return $return;

}


?>
