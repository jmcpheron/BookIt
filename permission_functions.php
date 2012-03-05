<?
include("dbo_permission_settings.php");

function canIDoThisToThem($myinfo, $their_info, $permission){

  
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

function listOusWithPermission($id, $permission){
  $results = dbo_listOusWithPermission($id, $permission);
  if(!$results){
    $results = array();
  }
  return $results;

}

function listRolesInOu($ou){
  $results = dbo_listRolesInOu($ou);
  if(!$results){
    $results = array();
  }
  return $results;
}

function listRolePermissions($ou, $role){
  $results = dbo_listRolePermissions($ou, $role);
  if(!$results){
    $results = array();
  }
  return $results;
}

function listAvailablePermissions(){
  $results = dbo_listAvilablePermissions($ou, $role);
  if(!$results){
    $results = array();
  }
  return $results;

}

function deleteRolePermission($ou, $role, $permission, $affected){
  $results = dbo_deleteRolePermission($ou, $role, $permission, $affected);
  if(!$results){
    $results = array();
  }
  return $results;

}

function listUsersInRole($ou, $role, $limit, $offset = 6){
  $results = dbo_listUsersInROle($ou, $role, $limit, $offset);
  if(!$results){
    $results = array();
  }
  
  return $results;

}

?>
