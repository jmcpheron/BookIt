<?

function dbo_Search_Open_Appt($ou, $role, $start_day, $end_day, $by_id = null, $by_ou = null, $by_role = null){
  $sql = "
select b.bid, b.title, p.role, p.key, cast(p.value as int),
b.start_time, b.end_time,
count(s.id)
from blocks b
left join properties p on (b.bid = p.bid)
left join participants s on (b.bid = s.bid and s.role = '$role')
left join participants me on (b.bid = me.bid)
where p.ou_code = '$ou'
and TO_CHAR(b.start_time,'YYYY-MM-DD') >= '".$start_day."'
and TO_CHAR(b.start_time,'YYYY-MM-DD') <= '".$end_day."'
";
if($by_id){
  $sql .= "and me.id = '$by_id'";
}
if($by_ou){
  $sql .= "and me.ou_code = '$by_ou'";
}
if($by_role){
  $sql .= "and me.role = '$by_role'";
}
$sql .= "
and p.role = '$role'
and p.key = 'max'
group by b.bid, b.title, p.role, p.key, p.value, b.start_time, b.end_time
having count(s.id) < cast(p.value as int)
order by b.start_time
";
  $results = db_query($sql);
  return $results;
}


function dbo_Appt_General($id, $bid){
$sql = "
select * from blocks b
WHERE b.bid = '$bid'
";
$results = db_query($sql);
return $results;

}

function dbo_Appt_Details($id, $bid){
$sql = "
select * from blocks b
left join participants p on (b.bid= p.bid)
left join person n on (p.id = n.id)
left join ous o on (p.ou_code = o.ou_code)
WHERE b.bid = '$bid'
order by role
";
$results = db_query($sql);
return $results;

}

function dbo_Block_Properties($id, $bid){
$sql = "
select * from properties p
WHERE p.bid = '$bid'
";
$results = db_query($sql);
return $results;

}


function dbo_insertSample($id, $bid){

$sql = "
DELETE FROM participants
WHERE id = '$id' and bid = '$bid'
";

db_query($sql);

$sql = "
INSERT INTO participants
(bid, id, ou_code, role, created_by, attending)
VALUES
('$bid', '$id', 'think', 'counselor', '00776161', 'y')
";
//echo $sql;
db_query($sql);
}

function dbo_getDayOfAppointments($id, $day, $ou_code, $role){

if(($ou_code == null) and ($role == null)){
  $sql = "
  SELECT b.bid, p.ou_code, p.role,
  p.attending, p.created_by,
  b.title, 
  b.start_time, 
  b.end_time
  FROM participants p 
  left join blocks b on (p.bid = b.bid)
  where b.start_time > '$day'
  ORDER BY b.start_time
  ";
}
//TODO Review
if(($ou_code != null) and ($role != null)){
  $sql = "
  SELECT b.bid, p.ou_code, p.role,
  p.attending, p.created_by,
  b.title, 
  b.start_time, 
  b.end_time
  FROM participants p 
  left join blocks b on (p.bid = b.bid)
  where b.start_time > '$day'
  and p.ou_code = '$ou_code'
  and p.role = '$role'
  and b.title = 'Counseling'
  ORDER BY b.start_time
  ";
  echo $sql;
}
$results = db_query($sql);
return $results;

}


function dbo_getBid($bid, $id = null){

//Notice the (MM - 1)
//For some reason javascript starts counting months at zero. Crazy huh?
$sql = "
SELECT b.bid, p.ou_code, p.role,
p.attending, p.created_by,
b.title, 
b.start_time, 
TO_CHAR(b.start_time,'YYYY, (MM - 1), DD, HH24, MI') AS start_time,
TO_CHAR(b.end_time,'YYYY, (MM - 1), DD, HH24, MI') AS end_time,
case when u.sub_value is null then '333399' else u.sub_value end as color
FROM participants p 
left join blocks b on (p.bid = b.bid)
left join user_settings u on (
  p.id = u.id 
  and u.key = 'calendar_color'
  and u.value = p.ou_code || '/' || p.role )
where b.bid = '$bid'
";
if($id){
  $sql.="and p.id = '$id'";
}
$sql.="
ORDER BY b.start_time
";
$results = db_query($sql);
return $results;

}

function dbo_getMonthOfAppointments($id, $day, $ou_code = null, $role = null){

//Notice the (MM - 1)
//For some reason javascript starts counting months at zero. Crazy huh?
$sql = "
SELECT b.bid, p.ou_code, p.role,
p.attending, p.created_by,
b.title, 
b.start_time, 
TO_CHAR(b.start_time,'YYYY, (MM - 1), DD, HH24, MI') AS start_time,
TO_CHAR(b.end_time,'YYYY, (MM - 1), DD, HH24, MI') AS end_time,
case when u.sub_value is null then '#333399' else u.sub_value end as color
FROM participants p 
left join blocks b on (p.bid = b.bid)
left join user_settings u on (
  p.id = u.id 
  and u.key = 'calendar_color'
  and u.value = p.ou_code || '/' || p.role )
where p.id = '$id'
--and TO_CHAR(b.start_time,'YYYY-MM') = '".substr($day, 0, 7)."'
ORDER BY b.start_time
";
 
//TODO review
if(($ou_code != null) and ($role != null)){
$sql = "
SELECT b.bid, p.ou_code, p.role,
p.attending, p.created_by,
b.title, 
b.start_time, 
TO_CHAR(b.start_time,'YYYY, (MM - 1), DD, HH24, MI') AS start_time,
TO_CHAR(b.end_time,'YYYY, (MM - 1), DD, HH24, MI') AS end_time
FROM participants p 
left join blocks b on (p.bid = b.bid)
where p.id = '$id'
and p.ou_code = '$ou_code'
and p.role = '$role'
and b.title = 'Counseling'
ORDER BY b.start_time
";
}
//TODO Filter month
$results = db_query($sql);
return $results;
}

function dbo_person($id){

$sql = "
SELECT firstname, middlename, lastname 
FROM person 
WHERE id = '$id'
";
//TODO Filter month
$results = db_query($sql);
$return = $results[0];
return $return;

}

function dbo_addRole($id, $ou, $role){

$sql = "
INSERT INTO ou_roles
(id, ou_code, role)
VALUES
('$id', '$ou', '$role')
";
$results = db_query($sql);
return $results;
}

function getRoles($id, $ou){

$sql = "
select o.role, r.long_name, r.description
from ou_roles o
left join roles r on (o.ou_code = r.ou_code and o.role = r.role)
where o.ou_code = '$ou'
and o.id = '$id'
";
$results = db_query($sql);
return $results;
}

function getOus($id){

$sql = "
SELECT ou_roles.ou_code,
ous.long_name
FROM ou_roles
LEFT JOIN ous on (ou_roles.ou_code = ous.ou_code)
where id = '$id'
group by ou_roles.ou_code, ous.long_name
";
$results = db_query($sql);
return $results;
}

function dbo_getPassword($username){
  $sql = "
  SELECT content
  FROM login
  WHERE method = 'password'
  AND id = '$username'
  ";
  $results = db_query($sql);
  return $results;
}


function dbo_insertOrUpdateLocalPassword($id, $password){ 
  include("common.php");
  $sql = "
  SELECT count(*) as c
  FROM login
  WHERE id = '$id'
  AND method = 'password'
  ";
  $results = db_query($sql);
  if($results[0]['c'] > 0){
    //update
    $sql = "
    UPDATE login
    SET content = '".md5($password)."'
    WHERE id = '$id'
    AND method = 'password'
    ";
  }else{
    //Insert
    $sql = "
    INSERT INTO login
    (id, method, content)
    VALUES
    ('$id', 'password', '".md5($password)."')
    ";
  }
  $results = db_query($sql);
  
return $results;

}

function dbo_insertOrUpdateLocalPerson($id, $firstname = null, $middlename = null, $lastname = null, $dob = null){ 
  if(strlen($dob) < 10){
    $dob = 'null';
  }
  $sql = "
  SELECT count(*) as c
  FROM person
  WHERE id = '$id'
  ";
  $results = db_query($sql);
  if($results[0]['c'] > 0){
    //update
    $sql = "
    UPDATE person
    SET firstname = '$firstname',
    middlename = '$middlename',
    lastname = '$lastname',
    dob = $dob
    WHERE id = '$id'
    ";
  }else{
    //Insert
    $sql = "
    INSERT INTO person
    (id, firstname, middlename, lastname, dob)
    VALUES
    ('$id', '$firstname', '$middlename', '$lastname', $dob)
    ";
  }
  $results = db_query($sql);
  
return $results;

}

function dboUpdateUserSettings($id, $key, $value, $sub_value = null){
  if(!$sub_value) {
  $sql = "
  UPDATE user_settings
  SET active = '0',
  deleted_by = '$id',
  deleted_ts = ('now'::text)::timestamp with time zone	
  WHERE key = '$key'
  AND id = '$id'
  AND active = '1'
  ";
  db_query($sql);

  $sql = "
  INSERT INTO user_settings
  (id, key, value)
  VALUES
  ('$id', '$key', '$value')
  ";
  $return = db_query($sql);
  }else{
  $sql = "
  UPDATE user_settings
  SET active = '0',
  deleted_by = '$id',
  deleted_ts = ('now'::text)::timestamp with time zone	
  WHERE key = '$key'
  AND id = '$id'
  AND value = '$value'
  AND active = '1'
  ";

  //Try it with just a delete. Why do we need to save a history of user settings?
  $sql = "
  delete from  user_settings
  WHERE key = '$key'
  AND id = '$id'
  AND value = '$value'
  ";
  db_query($sql);

  $sql = "
  INSERT INTO user_settings
  (id, key, value, sub_value)
  VALUES
  ('$id', '$key', '$value', '$sub_value')
  ";
  $return = db_query($sql);

  }
  return $return;
}


function dboUpdateRolePermissions($ou, $role, $key, $value){
  $return = "unknown_error";
  $sql = "
  SELECT *
  FROM role_permissions
  WHERE ou = '$ou'
  AND role = '$role'
  AND permissions = '$key'
  AND affected = '$value'
  ";
  $results = db_query($sql);
  if(strlen($results[0]['permissions']) > 0){
    $return = "already_set";
  }else{
    $sql = "
    INSERT INTO 
    role_permissions
    (ou, role, permissions, affected, allow)
    VALUES
    ('$ou', '$role', '$key', '$value', '1')
    ";
    db_query($sql);
    $return = "set";
  }
  return $return;
}

function dbo_CurrentUserValue($id, $key, $value = null){
  if(!$value){
  $sql = "
  SELECT value
  FROM user_settings
  WHERE key = '$key'
  and id = '$id'
  and active = '1'
  ";
  $return = db_query($sql);
  return $return[0]['value'];
  }else{
  $sql = "
  SELECT sub_value as value
  FROM user_settings
  WHERE key = '$key'
  and id = '$id'
  and value = '$value'
  and active = '1'
  ";
  $return = db_query($sql);
  return $return[0]['value'];

  }
}

function dbo_getRangeOfAppointments($id, $start_day, $end_day, $ou_code = null, $role = null){
//TODO Maybe make the $ou_code and $role an array so we can display blocks from mutiple roles in one call

//Notice the (MM - 1)
//For some reason javascript starts counting months at zero. Crazy huh?
$sql = "
SELECT b.bid, p.ou_code, p.role,
p.attending, p.created_by,
b.title,
b.start_time,
TO_CHAR(b.start_time,'YYYY, (MM - 1), DD, HH24, MI') AS start_time,
TO_CHAR(b.end_time,'YYYY, (MM - 1), DD, HH24, MI') AS end_time,
case when u.sub_value is null then '#333399' else u.sub_value end as color
FROM blocks b
--FROM participants p
left join participants p on (b.bid = p.bid)
--left join blocks b on (p.bid = b.bid)
left join user_settings u on (
  p.id = u.id
  and u.key = 'calendar_color'
  and u.value = p.ou_code || '/' || p.role )
where p.id = '$id'
";
if($ou_code){
  $sql.= "and p.ou_code = '$ou_code'";
}
if($role){
  $sql.= "and p.role = '$role'";
}
$sql .= "
and TO_CHAR(b.start_time,'YYYY-MM-DD') >= '".$start_day."'
and TO_CHAR(b.start_time,'YYYY-MM-DD') <= '".$end_day."'
ORDER BY b.start_time
";

$results = db_query($sql);
return $results;
}

function dbo_newBlock($bid, $start_time, $end_time, $title, $created_by){
  $sql = "
  INSERT INTO blocks
  (bid, title, start_time, end_time, created_by, created)
  VALUES
  ('$bid', '$title', '$start_time', '$end_time', '$created_by', now())
  ";
  db_query($sql);
  //Should we return somenthing?

}

function dbo_addParticipant($bid, $id, $ou_code, $role, $created_by, $attending){
  //quote attending character if not null
  if($attending){
    $attending = "'$attending'";
  }
  $sql = "
  INSERT INTO participants
  (bid, id, ou_code, role, created, created_by, attending)
  VALUES
  ('$bid', '$id', '$ou_code', '$role', now(), '$created_by', $attending)
  ";
  db_query($sql);
  //Should we return somenthing?

}

function dbo_addProperty($bid, $id, $ou_code, $role, $key, $value, $created_by){
  //TODO Check for duplicates, or overlapping properties
  $sql = "
  INSERT INTO properties
  (bid, id, ou_code, role, key, value, created_by)
  VALUES
  ('$bid', '$id', '$ou_code', '$role', '$key', '$value', '$created_by')
  ";
  db_query($sql);
}

function dbo_isBidFull($bid){
  $sql = "
select count(s.id) as count
from properties p
left join participants s on (
  p.bid = s.bid 
  and p.ou_code = s.ou_code
  and p.role = s.role
)
where p.bid = '$bid'
and s.id is not null
  ";
  $results = db_query($sql);
  return $results[0]['count'];
}

function dbo_ouLongName($ou){
  $sql = "
select long_name
from ous
where ou_code = '$ou'
  ";
  $results = db_query($sql);
  return $results[0]['long_name'];
}

function dbo_miscLog($log){
  $sql = "
  INSERT INTO log (log) VALUES ('$log')
  ";
  $results = db_query($sql);
  return $results;
}

function dbo_getUserRole($bid, $id){
  $sql = "
  select ou_code, role, id 
  from participants 
  where bid = '$bid'
  and id = '$id'
  ";
  $result = db_query($sql);
  return $result[0];
}
?>
