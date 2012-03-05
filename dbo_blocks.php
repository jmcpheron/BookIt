<?

function dbo_DeleteParticipant($bid, $uid){
  $sql = "
  DELETE from participants where bid = '$bid' and id = '$uid'
  ";
  $results = db_query($sql);
  return $results;

}

?>
