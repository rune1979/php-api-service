<?php
class Rest extends Dbc{
     
    private $empTable = 'emp';	
   

	public function getUserHash($rfid_id) {		
		$sqlQuery = '';
		if(!empty($rfid_id)){
		$sqlQuery = "WHERE rfid='".$rfid_id."'";	
		$empQuery = "SELECT * FROM emp $sqlQuery";	}
		$resultDat = mysqli_query($this->dbConnect(), $empQuery);
		$userinfo = array();
		while($empRec = mysqli_fetch_assoc($resultDat)) {
			$userinfo[] = $empRec;
		}
		return $userinfo;
	}

	public function getRelation($zone, $user_id){		
		if($user_id) {
			$sqlQuer = "WHERE user_id='".$user_id."' AND zone_id='".$zone."'";	
			$empQuer = "SELECT * FROM access_relation $sqlQuer";
		}	
		$resultDa = mysqli_query($this->dbConnect(), $empQuer);
		if (mysqli_num_rows($resultDa) == ""){
			return "n";
		} else {
		$data=array();
		while($row = mysqli_fetch_assoc($resultDa)) {
			$data[]=$row;            
		}
			return $data;
		}
	}

	public function getUnitZone($unit_id){		
		$sqlQuery = '';
		if($unit_id) {
			$sqlQuery = "WHERE id='".$unit_id."'";
		}	
		$empQuery = "SELECT zone FROM locks $sqlQuery";	
		$resultData = mysqli_query($this->dbConnect(), $empQuery);
		if(!$resultData){
			return "n";
		} else {
		$lock_id = mysqli_fetch_assoc($resultData);
		return $lock_id['zone'];
		}
	}

	protected function insertLog($user_id, $lock_id, $alert, $temp, $moist, $action){ 		
		$empQuery="INSERT INTO log SET user_id='$user_id', lock_id='$lock_id', alert='$alert', temp='$temp', moist='$moist', action='$action'";
		mysqli_query($this->dbConnect(), $empQuery);
		}	
	
	public function getAccessNew($rfid, $unit_id){		
		$zone = $this->getUnitZone($unit_id);
		$user_pd = $this->getUserHash($rfid);
		foreach ($user_pd as $user){
			$user_id = $user['id'];
			$passd = $user['passd'];
			}
		$access = $this->getRelation($zone, $user_id);
		date_default_timezone_set("Europe/Copenhagen");
		$timea = date("h:i");
		$date_cur_now = date("Y-m-d");
		$ac = "1";
		if($access == "n"){
			$ac = "Zone access denied!";
			$status = "0";	
			$access_response = array(
			'status' => $status,
			'status_message' => $ac,
			'time' => $timea);
			$this->insertLog($user_id, $unit_id, "Illigal login attempt", "0", "0", $ac);	
		} else {
			foreach ($access as $acu){
			$time_from = $acu['time_from'];
			$time_to = $acu['time_to'];
			$date_from = $acu['date_from'];
			$date_to = $acu['date_to'];
			$rel_id = $acu['id'];
			$userid = $acu['user_id'];
			$zone_id = $acu['zone_id'];
			}		
			$ac = "Zone access successfully granted.";
			$lock_key = $passd;
			$status = "1";	
			if(!empty($time_from)){
				$begintime = date("H:i", $data['time_from']);
				$endtime = new DateTime($access['time_to']);
				if($timea > $time_from && $timea < $time_to){
					$ac = "Access Allowed";
					$status = "1";
				} else {
					$ac = "Denied at this time of day!";
					$status = "0";
				}}

			if(!empty($date_from)){
				$begintime = date("H:i", $data['time_from']);
				$endtime = new DateTime($access['time_to']);
				if($date_cur_now > $date_from && $date_cur_now < $date_to){
					$ac = "Access Allowed";
					$status = "1";
				} else {
					$ac = "Access is denied at this moment";
					$status = "0";
				}
			
			}
			if($status == "1"){
				$access_response = array(
				'status' => $status,
				'lock_key' => $lock_key,
				'status_message' => $ac,
				'Time attempted' => $timea,
				'Date attempted' => $date_cur_now);
				$this->insertLog($user_id, $unit_id, "Login granted", "0", "0", $ac);
			} else {
				$access_response = array(
				'status' => $status,
				'lock_key' => "non",
				'status_message' => $ac,
				'Time attempted' => $timea,
				'Date attempted' => $date_cur_now);
				$this->insertLog($user_id, $unit_id, "Login failed", "0", "0", $ac);
			}			
					
		}
		header('Content-Type: application/json');
		echo json_encode($access_response);
			
	}


	function setTemp($id, $temp, $moist){ 		
		if($id){
			$empQuery="
				UPDATE zone 
				SET temp = '".$temp."', moist = '".$moist."' 
				WHERE id = '".$id."'";
			if(mysqli_query($this->dbConnect(), $empQuery)) {
				$messgae = "Zone temperature updated successfully.";
				$status = 1;			
			} else {
				$messgae = "Zone temperature update failed.";
				$status = 0;			
			}
		} else {
			$messgae = "Invalid request.";
			$status = 0;
		}
		$empResponse = array(
			'status' => $status,
			'status_message' => $messgae
		);
		header('Content-Type: application/json');
		echo json_encode($empResponse);
	}





}
?>
