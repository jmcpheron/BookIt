<?
include("dbo_blocks.php");

function deleteParticipant($bid, $uid){

  //Databse delete 
  dbo_DeleteParticipant($bid, $uid);

  //TODO send alerts

  //Log it
  $log = "action:deleteParticipant,id:$uid,bid:$bid,deleted_by:$id";
  miscLog($log);

}

?>
