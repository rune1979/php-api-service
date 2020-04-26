<?php

class ControlScheduler extends Dbc{

	// ADD NEW TIME SEGMENT TO IOT, FOR AJAX USE
	protected function addTimer($temp_id, $iot_id, $description, $from_date, $to_date, $total, $start_time, $to_time, $set_val){
		// INCLUDE CLASS FILES FOR AJAX
		//include "inc/class.php";
		//$standard = new ViewBasic();
		// THE REST IS NORMAL CONTROL METHOD
		$sql = "INSERT INTO scheduler (iot_id, description, from_date, to_date, daysofweek, start_time, to_time, set_val) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->prpConnect()->prepare($sql);
		if ($stmt->execute([$iot_id, $description, $from_date, $to_date, $total, $start_time, $to_time, $set_val])){
			$action = "New Time Segment Added to IoT id: ".$iot_id."";
			echo $action;
			// ADDING RECORD TO LOG
			//$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - While trying to add New Time Segment to IoT id: ".$iot_id."!";
			echo $action;
			// FOR DEBUGGING
			//print_r($stmt->errorInfo());
			// ADDING RECORD TO LOG
			//$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		}
		}

	// EDIT TIME SEGMENT IN TEMPLATE
	protected function editTimer($id, $iot_id, $description, $from_date, $to_date, $total, $from_time, $to_time, $set_val){
		// INCLUDE CLASS FILES FOR AJAX
		include "inc/class.php";
		$standard = new ViewBasic();
		// THE REST IS NORMAL CONTROL METHOD
		$sql = "UPDATE scheduler SET description=?, from_date=?, to_date=?, daysofweek=?, start_time=?, to_time=?, set_val=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		if ($stmt->execute([$description, $from_date, $to_date, $total, $from_time, $to_time, $set_val, $id])){
			$action = "Time Segment: ".$id."-".$description." edited. Belonging to IoT: ".$iot_id."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - Trying to edit Time Segment: ".$id."-".$description.". Belonging to IoT: ".$iot_id."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		}
		}

	// DELETE TIME SEGMENT IN TEMPLATE
	protected function deleteTimer($id, $iot_id, $description){
		// INCLUDE CLASS FILES FOR JQUERY
		include "inc/class.php";
		$standard = new ViewBasic();

		// THE REST IS NORMAL CONTROL METHOD
		$sql = "DELETE FROM scheduler WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		if ($stmt->execute([$id])){
			$action = "Time Segment: ".$id."-".$description." DELETED. Belonging to IoT: ".$iot_id."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - Trying to DELETE Time Segment: ".$id."-".$description.". Belonging to IoT: ".$iot_id."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,$iot_id,$_SESSION['name'],$action);
		}
		}


			// GET TIMERS FOR IOT ID
		protected function getSchedules($id){
			$sql = "SELECT * FROM scheduler WHERE iot_id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			$results = $stmt->fetchAll();
			return $results;
		}


}



class ViewScheduler extends ControlScheduler{

	// ADD NEW TIMER TO IOT
	public function add_timer($temp_id = "", $iot_id, $description, $from_date, $to_date, $total, $from_time, $to_time, $set_val){
			$timers = $this->getSchedules($iot_id);
			// MAKE SURE TIME IS RIGHT SET
			$time_a = strtotime($from_time); $time_b = strtotime($to_time);
			if ($time_a > $time_b){
				echo "Error in time settings, Start time can't be higher than End time.";
				return;
			}
			// WE WANT TO MAKE SURE THAT NO TIMERS ARE OVERLAPING
			//$this_to_day = $from_day + $days;
			foreach ($timers as $timer) {
				// GET SAME IOT_ID (DEVICE) AS THE CURRENT ONE
				//if ($timer['iot_id'] == $iot_id){
					//$seg_to_day = $timer['from_day'] + $timer['days'];
					if (($from_date >= $timer['from_date'] && $from_date < $timer['to_date']) || ($to_date > $timer['from_date'] && $to_date <= $timer['to_date']) || ($from_date <= $timer['from_date'] && $to_date >= $timer['to_date'])){
						// BITWISE OPERATORS "AND" TO SEE IF THERE ARE ANY CLASHES WITH WEEK DAYS, DON'T MIND THE LAST BIT (128), THAT IS NOT A WEEKDAY
						$new_dow = $timer['daysofweek'] - 128; $existing_dow = $total - 128;
						$res = ($new_dow & $existing_dow);
						if ($res !== 0) {
							// NOW FOR ASSURING THAT NO TIME IS CONFLICTING
							$time_seg_a = strtotime($timer['from_time']); $time_seg_b = strtotime($timer['to_time']);
							if (($time_a > $time_seg_a && $time_a < $time_seg_b) || ($time_b > $time_seg_a && $time_b < $time_seg_b) || ($time_a <= $time_seg_a && $time_b >= $time_seg_b)){
								echo "Error on dates or time, there are conflicts!";
								return;
							}
						}
					}
				//}
			}
			echo $this->addTimer($temp_id, $iot_id, $description, $from_date, $to_date, $total, $from_time, $to_time, $set_val);
		}

	// EDIT EXISTING TIME SEGMENT
	public function edit_timer($id, $iot_id, $description, $from_date, $to_date, $total, $from_time, $to_time, $set_val){
		$timers = $this->getSchedules($iot_id);
		// MAKE SURE TIME IS RIGHT SET
		$time_a = strtotime($from_time); $time_b = strtotime($to_time);
		if ($time_a > $time_b){
			echo "Error in time settings, Start time can't be higher than End time.";
			return;
		}
		// WE WANT TO MAKE SURE THAT NO TIMERS ARE OVERLAPING
		//$this_to_day = $from_day + $days;
		foreach ($timers as $timer) {
			// GET SAME IOT_ID (DEVICE) AS THE CURRENT ONE
			if ($timer['id'] !== $id){

				if (($from_date >= $timer['from_date'] && $from_date < $timer['to_date']) || ($to_date > $timer['from_date'] && $to_date <= $timer['to_date']) || ($from_date <= $timer['from_date'] && $to_date >= $timer['to_date'])){
					// BITWISE OPERATORS "AND" TO SEE IF THERE ARE ANY CLASHES WITH WEEK DAYS, DON'T MIND THE LAST BIT (128), THAT IS NOT A WEEKDAY
					$new_dow = $timer['daysofweek'] - 128; $existing_dow = $total - 128;
					$res = ($new_dow & $existing_dow);
					if ($res !== 0) {
						//if (($a & $b) !== 128 && ($a & $b) == true){
						// NOW FOR ASSURING THAT NO TIME IS CONFLICTING
						$time_seg_a = strtotime($timer['from_time']); $time_seg_b = strtotime($timer['to_time']);
						if (($time_a > $time_seg_a && $time_a < $time_seg_b) || ($time_b > $time_seg_a && $time_b < $time_seg_b) || ($time_a <= $time_seg_a && $time_b >= $time_seg_b)){
							echo "Error on dates or time, there are conflicts!";
							return;
						  }
						}
					//}
				}
			}
		}
				echo $this->editTimer($id, $iot_id, $description, $from_date, $to_date, $total, $from_time, $to_time, $set_val);
		}

	// DELETE TIMER FOR IOT
	public function delete_timer($id, $iot_id, $description){
				echo $this->deleteTimer($id, $iot_id, $description);
		}

	// IMPORT TIMESEGMENTS FROM TEMPLATE TO IOT
	public function import_segments($template_id, $from_iot, $to_iot, $Date){
				// INCLUDE CLASS FILES FOR JQUERY
				include "inc/class.php";
				$standard = new ViewBasic();
				// GET ALLE CONTENT FROM TEMPLATE
				$all_iots = $standard->get_temp_cont($template_id);
				// GET EXISTING SCHEDULES FOR IOT
				$all_existing_schedules = $standard->get_scheduler($to_iot);

				$conflicts = 0;
				foreach ($all_iots as $new_iot) if ($new_iot['iot_id'] == $from_iot) {
					// LOOP OVER EACH EXISTING TIMER IN IOT TO SEE IF THERE ARE CONFLICTS
					$conflict_found = false;
					$new_time_a = strtotime($new_iot['from_time']); $new_time_b = strtotime($new_iot['to_time']);
					$from_date = date('Y-m-d', strtotime($Date. ' + '.$new_iot['from_day'].' days'));
					$to_date = date('Y-m-d', strtotime($from_date. ' + '.$new_iot['days'].' days'));
					foreach ($all_existing_schedules as $exist_iot){

						if (($from_date >= $exist_iot['from_date'] && $from_date < $exist_iot['to_date']) || ($to_date > $exist_iot['from_date'] && $to_date <= $exist_iot['to_date']) || ($from_date <= $exist_iot['from_date'] && $to_date >= $exist_iot['to_date'])){
							$new_dow = $new_iot['daysofweek'] - 128; $existing_dow = $exist_iot['daysofweek'] - 128;
							$res = ($new_dow & $existing_dow);
							if ($res !== 0) {
									$existing_time_a = strtotime($exist_iot['start_time']); $existing_time_b = strtotime($exist_iot['to_time']);
									if (($new_time_a > $existing_time_a && $new_time_a < $existing_time_b) || ($new_time_b > $existing_time_a && $new_time_b < $existing_time_b) || ($new_time_a <= $existing_time_a && $new_time_b >= $existing_time_b)){
										$conflict_found = true;
										echo "\n There is a conflict! from_time: ".$from_date.", to_time: ".$to_date."";
										$conflicts++;
									}
							}
						}
						//$conflict_found = true;
						//$conflicts++;
						//break;
					}

				if ($conflict_found == false){
					// IMPORT SEGMENT TO SCHEDULE
					echo $this->addTimer($new_iot['time_temp_id'], $to_iot, $new_iot['description'], $from_date, $to_date, $new_iot['daysofweek'], $new_iot['from_time'], $new_iot['to_time'], $new_iot['set_val']);
					//echo $new_iot['time_temp_id'], $to_iot, $new_iot['description'], $from_date, $to_date, $new_iot['daysofweek'], $new_iot['from_time'], $new_iot['to_time'], $new_iot['set_val'];
					echo "\n No conflicts \n";
				}


				}
				// print_r($all_existing_schedules);
				echo "\n Import Done! \n Number of time conflicts found: ".$conflicts."";
				//echo $this->deleteTimer($id, $iot_id, $description);
		}







}




?>
