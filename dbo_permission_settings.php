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
