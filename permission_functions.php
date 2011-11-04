<?
include("dbo_permission_settings.php");

function canIDoThis($myinfo, $their_info, $permission){

  
  //Assume false
  $return = false;
  $results = dbo_rolePermissions($myinfo['ou_code'], $myinfo['role'], $permission);
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
