<?

function dbo_rolePermissions($ou, $role, $permission){
$sql = "
select *
from role_permissions
where ou = '$ou'
and role = '$role'
and permissions = '$permission'
";
$results = db_query($sql);
return $results;
}

function dbo_listOusWithPermission($id, $permission){

  $sql = "
  select p.ou, p.role, p.affected 
  from ou_roles r
  left join role_permissions p on (r.ou_code = p.ou and r.role = p.role)
  where r.id = '$id'
  and permissions = '$permission'
  group by p.ou, p.role, p.affected
  ";
  $results = db_query($sql);
  return $results;
}

function dbo_listRolesInOu($ou){

  $sql = "
  select role, long_name, description
  from roles
  where ou_code = '$ou'
  ";
  $results = db_query($sql);
  return $results;
}

function dbo_listRolePermissions($ou, $role){

  $sql = "
  select permissions, allow, affected
  from role_permissions
  where ou = '$ou'
  and role = '$role'
  order by permissions
  ";
  $results = db_query($sql);
  return $results;
}

function dbo_listAvilablePermissions(){

  $sql = "
  select permission, description
  from permissions
  order by permission
  ";
  $results = db_query($sql);
  return $results;
}

function dbo_deleteRolePermission($ou, $role, $permission, $affected){
  $sql = "
  DELETE FROM role_permissions
  WHERE ou = '$ou'
  AND role = '$role'
  AND permissions = '$permission'
  AND affected = '$affected'
  ";
  $return = db_query($sql);
  return $return;
}
?>
