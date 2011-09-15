<?

function addSomeAppts($id){

  $day =  date('Y-m-d', time());
  $uday =  strtotime(date('Y-m-d', time()));
  //Check for anything today
  $results = dbo_getRangeOfAppointments($id, $day, $day);
  if(!$results){
    $start_time = date('Y-m-d H:i:00', $uday + (9 *60 * 60));

    $c = 0;
    while($c <= 5){
      $end_time = date('Y-m-d H:i:00', strtotime($start_time) + (30 * 60));
      //echo $start_time;
      //echo $end_time;
      $this_bid = newBlock($start_time, $end_time, 'Sample Appt', $id);

      //Add myself to these blocks
      addParticipant($this_bid, $id, 'bookit', 'charter', $id, '1');
 
      //Make it an open appt
      addProperty($this_bid, $id, 'bookit', 'student', 'max', '1', $id);


      $start_time = date('Y-m-d H:i:00', strtotime($start_time) + (30 * 60));
      $c++;
    }
  }
  return $results;
/*
*/
}

?>
