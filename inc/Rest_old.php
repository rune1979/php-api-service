<?php
class Rest extends Dbc{
     
    private $empTable = 'emp';	
   
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function getEmployee($empId) {		
		$sqlQuery = '';
		if($empId) {
			$sqlQuery = "WHERE id = '".$empId."'";
		}	
		$empQuery = "
			SELECT * 
			FROM ".$this->empTable." $sqlQuery
			ORDER BY id DESC";	
		$resultData = mysqli_query($this->dbConnect(), $empQuery);
		$empData = array();
		while( $empRecord = mysqli_fetch_assoc($resultData) ) {
			$empData[] = $empRecord;
		}
		header('Content-Type: application/json');
		echo json_encode($empData);	
	}
	public function getUserHash($hash) {		
		$sqlQuery = '';
		if($hash) {
			$sqlQuery = "WHERE rfid = '".$hash."'";
		}	
		$empQuery = "
			SELECT id, passd 
			FROM emp $sqlQuery
			ORDER BY id DESC";	
		$resultData = mysqli_query($this->dbConnect(), $empQuery);
		$user_id = array();
		while( $empRecord = mysqli_fetch_assoc($resultData) ) {
			$user_id[] = $empRecord;
		}
		return $user_id;
		//header('Content-Type: application/json');
		//echo json_encode($user_id);
		//echo $user_id
	}

	public function getRelation($zone_id, $user_id){		
		$sqlQuery = '';
		if($user_id) {
			$sqlQuery = "WHERE user_id='".$user_id."' AND zone_id='".$zone_id."'";
		}	
		$empQuery = "SELECT id FROM access_relation $sqlQuery";	
		$resultData = mysqli_query($this->dbConnect(), $empQuery);
		if(!$resultData){
			return "n";
		} else {
		//$lock_id = mysqli_fetch_assoc($resultData);
		//return $lock_id;
			return "y";
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

	public function getAccess($rfid, $unit_id){		
		$zone = $this->getUnitZone($unit_id);
		$user_pd = $this->getUserHash($rfid);
		foreach ($user_pd as $user){
			$user_id = $user['id'];
			$passd = $user['passd'];
			}
		$access = $this->getRelation($zone, $user_id);
		if($access == "y"){
			$messgae = "Zone access successfull.";
			$lock_key = $passd;
		} else {
			$messgae = "Zone access denied.";
			$lock_key = "non";		
		}
		$access_response = array(
			'lock_key' => $lock_key,
			'status_message' => $messgae
		);
		header('Content-Type: application/json');
		echo json_encode($access_response);
			
	}

	function insertEmployee($empData){ 		
		$empName=$empData["empName"];
		$empAge=$empData["empAge"];
		$empSkills=$empData["empSkills"];
		$empAddress=$empData["empAddress"];		
		$empDesignation=$empData["empDesignation"];
		$empQuery="
			INSERT INTO ".$this->empTable." 
			SET name='".$empName."', age='".$empAge."', skills='".$empSkills."', address='".$empAddress."', designation='".$empDesignation."'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Employee created Successfully.";
			$status = 1;			
		} else {
			$messgae = "Employee creation failed.";
			$status = 0;			
		}
		$empResponse = array(
			'status' => $status,
			'status_message' => $messgae
		);
		header('Content-Type: application/json');
		echo json_encode($empResponse);
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

	protected function updateTemp($id,$temp,$moist){ 		
		$empQuery="UPDATE zone SET temp='$temp', moist='$moist'	WHERE id='$id'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
				$messgae = "Temp updated successfully.";
				$status = 1;			
			} else {
				$messgae = "Temp update failed.";
				$status = 0;			
			}
		return $message;
		//$empResponse = array(
		//	'status' => $status,
		//	'status_message' => $messgae
		//);
		//header('Content-Type: application/json');
		//echo json_encode($empResponse);
	}

	function updateEmployee($empData){ 		
		if($empData["id"]) {
			$empName=$empData["empName"];
			$empAge=$empData["empAge"];
			$empSkills=$empData["empSkills"];
			$empAddress=$empData["empAddress"];		
			$empDesignation=$empData["empDesignation"];
			$empQuery="
				UPDATE ".$this->empTable." 
				SET name='".$empName."', age='".$empAge."', skills='".$empSkills."', address='".$empAddress."', designation='".$empDesignation."' 
				WHERE id = '".$empData["id"]."'";
				echo $empQuery;
			if( mysqli_query($this->dbConnect(), $empQuery)) {
				$messgae = "Employee updated successfully.";
				$status = 1;			
			} else {
				$messgae = "Employee update failed.";
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
	public function deleteEmployee($empId) {		
		if($empId) {			
			$empQuery = "
				DELETE FROM ".$this->empTable." 
				WHERE id = '".$empId."'	ORDER BY id DESC";	
			if( mysqli_query($this->dbConnect, $empQuery)) {
				$messgae = "Employee delete Successfully.";
				$status = 1;			
			} else {
				$messgae = "Employee delete failed.";
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
