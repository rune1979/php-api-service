<?php

class ControlBasic extends Dbc{

		protected function getFacility($id = "all"){
			if($id == "all" || $id == "0"){
			$sql = "SELECT * FROM facility";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM facility WHERE id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}

		protected function getZone($id = "all"){
			if($id == "all" || $id == "0"){
			$sql = "SELECT * FROM zone";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM zone WHERE id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}

		// GET IOT UNIQUE OR ALL
		protected function getIoT($id = "all"){
			if($id == "all" || $id == "0"){
			$sql = "SELECT * FROM iot";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM iot WHERE id = ? ORDER BY id DESC";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}

		// GET IOT BY IOT TYPE ID
		protected function getIoTByType($id){
			if(empty($id)){
			$sql = "SELECT * FROM iot";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM iot WHERE iot_type_id = ? ORDER BY id DESC";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}

		// GET IOT UNIQUE OR ALL
		protected function getIoTType($id){
			if($id == "all"){
			$sql = "SELECT * FROM iot_type";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM iot_type WHERE id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}

		protected function getUser($id = "all"){
			if($id == "all" || $id == "0"){
			$sql = "SELECT * FROM emp";
			$stmt = $this->prpConnect()->query($sql);
			} else {
			$sql = "SELECT * FROM emp WHERE id = ? ORDER BY id DESC";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
			$results = $stmt->fetchAll();
			return $results;
			}



		protected function getZoneF($id){
			$sql = "SELECT * FROM zone WHERE facility_id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			$results = $stmt->fetchAll();
			return $results;
			}

		// GET LOGS
	protected function getLogs($id){
		if($id == "all" || $id == "0"){
		$sql = "SELECT * FROM log ORDER BY id DESC";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM log WHERE facility_id = ? ORDER BY id DESC";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

		// GET ALERTS
	protected function getAlerts($facility_id, $alert_type, $status){
		if($facility_id == "all" || $facility_id == "0"){
		$sql = "SELECT * FROM alerts WHERE status = ? AND alert_type_id = ? ORDER BY id DESC";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$status, $alert_type]);
		} else {
		$sql = "SELECT * FROM alerts WHERE facility_id = ? AND status = ? AND alert_type_id = ? ORDER BY id DESC";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$facility_id, $status, $alert_type]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

		// GET LOGS
	protected function getAlertType($id){
		if($id == "all" || $id == "0"){
		$sql = "SELECT * FROM alert_type ORDER BY id ASC";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM alert_type WHERE id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

		// GET TEMPLATE's CONTENT
	protected function getTempCont($id){
		$sql = "SELECT * FROM time_control WHERE time_temp_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		$results = $stmt->fetchAll();
		return $results;
		}
		// GET SCHEDULER FOR IOT 
	protected function getScheduler($id){
		$sql = "SELECT * FROM scheduler WHERE iot_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		$results = $stmt->fetchAll();
		return $results;
		}
		// GET TIME TEMPLATES
	protected function getTemplate($id, $facility_id){
		if($id == "all" || $id == "0" && $facility_id == "all" || $facility_id == "0"){
		$sql = "SELECT * FROM time_templates ORDER BY id DESC";
		$stmt = $this->prpConnect()->query($sql);
	} else if ($id == "all" || $id == "0"){
		$sql = "SELECT * FROM time_templates WHERE fac_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$facility_id]);
		} else {
			$sql = "SELECT * FROM time_templates WHERE id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			}
		$results = $stmt->fetchAll();
		return $results;
		}

	// GET ALL RFID
	protected function getAllRfid(){
		$sql = "SELECT * FROM locks ORDER BY id DESC";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}


	protected function getRoles(){
		$sql = "SELECT * FROM roles ORDER BY id DESC";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data; //An array
		}

	protected function checkUser($user){
		$sql = "SELECT id, user, cpr FROM emp WHERE user = '$user'";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = mysqli_fetch_assoc($result);
		if ($data > 0) {
			return '1';
		} else {
			return $data;
		}
		}

	protected function insertUser($name, $cpr, $role, $rfid, $passd, $user, $passwd){
		$empQuery="INSERT INTO emp SET name='$name', cpr='$cpr', role='$role', rfid='$rfid', passd='$passd', user='$user', passwd='$passwd'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Employee created Successfully.";
			$status = 1;
		} else {
			$messgae = "Employee creation failed.";
			$status = 0;
		}
		return $message;
	}

	protected function insertLog($user_id, $lock_id, $alert, $temp, $moist, $action){
		$empQuery="INSERT INTO log SET user_id='$user_id', lock_id='$lock_id', alert='$alert', temp='$temp', moist='$moist', action='$action'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Employee created Successfully.";
			//$status = 1;
		} else {
			$messgae = "Employee creation failed.";
			//$status = 0;
		}
		//return $message;
	}


	protected function newInsertLog($facility_id, $zone_id, $lock_id, $user_id, $action){
		$sql="INSERT INTO log SET user_id=?, lock_id=?, zone_id=?, facility_id=?, action=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$user_id, $lock_id, $zone_id, $facility_id, $action]);
		}

	protected function insertZone($name, $description){
		$empQuery="INSERT INTO zone SET name='$name', description='$description'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Zone created Successfully.";
		} else {
			$messgae = "Zone creation failed.";
		}
		return $message;
	}
	protected function insertRfid($name, $description, $zone){
		$empQuery="INSERT INTO locks SET name='$name', description='$description', zone='$zone'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "RFID created Successfully.";
		} else {
			$messgae = "RFID creation failed.";
		}
		return $message;
	}

}



class ViewBasic extends ControlBasic{

	public function printUserProfile($id){
			$datas = $this->getUser($id);
			//return $datas;
			echo "<table>";
			foreach ($datas as $data) {
				echo "<tr><td>Userid: </td><td> ".$data['id']."</td></tr>";
				echo "<tr><td>Navn: </td><td>".$data['name']."</td></tr>";
				echo "<tr><td>Username: </td><td>".$data['user']."</td></tr>";
				echo "<tr><td>Role: </td><td>".$data['role']."</td></tr>";
				echo "<tr><td>CPR: </td><td>".$data['cpr']."</td></tr>";
				echo "<tr><td>RFID: </td><td>".$data['rfid']."</td></tr>";
				echo "<tr><td></td><td><a href=\"users.php?page=rediger&id=".$data['id']."\">Rediger</a></td></tr>";
				}echo "</table>";
		}

	public function newInsLog($facility_id, $zone_id, $lock_id, $user_id, $action){
		$this->newInsertLog($facility_id, $zone_id, $lock_id, $user_id, $action);
		}

	// Getting the zones related to a facility
	public function get_zonebyfac($fac){
		if ($fac == "all" || $fac == 0){
			return $this->getZone($fac);
		} else {
			return $this->getZoneF($fac);
		}

	}

	// Getting the iot
	public function get_iot($id = "all"){
		return $this->getIoT($id);
	}
	// Getting the iot by iot type id
	public function get_iot_by_type($id){
		return $this->getIoTByType($id);
	}
	// Getting the iot_type
	public function get_iot_type($id = "all"){
		return $this->getIoTType($id);
	}

	// Getting the iot
	public function get_user($id = "all"){
		return $this->getUser($id);
	}

	// Getting the zones
	public function get_zone($id = "all"){
		return $this->getZone($id);
	}
	// Getting template by ID
	public function get_template($id = "all", $facility_id = "all"){
		return $this->getTemplate($id, $facility_id);
	}
	// Getting template content by template id
	public function get_temp_cont($id){
		return $this->getTempCont($id);
	}
	// Getting timer schedule for iot id
	public function get_scheduler($id){
		return $this->getScheduler($id);
	}
	// Getting the zones
	public function get_facility($id){
		return $this->getFacility($id);
	}
	// Getting the alert type
	public function get_alert_type($id = "all"){
		return $this->getAlertType($id);
	}

	public function getFacilities($id = "0"){
				echo "<form action=\"\" method=\"post\">";
				echo "<select name=\"facility\">";
				echo "<option value=\"0\" ";
				if ($id == "0") {
					echo " selected";
				}
				echo ">All facilities</option>";
				$options = $this->getFacility("all");
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\" ";
				if ($option['id'] == $id){
				echo " selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select> <input type=\"submit\" name=\"submitfacility\" value=\"Choose\"></form>";
		}



	public function showAllUsers(){
			$datas = $this->getUser("all");
			echo "<tr><td>Userid</td>";
			echo "<td>Navn</td>";
			echo "<td>Username</td>";
			echo "<td>Role</td>";
			echo "<td>Handling</td></tr>";
			foreach ($datas as $data) {
				echo "<tr><td> ".$data['id']."</td>";
				echo "<td>".$data['name']."</td>";
				echo "<td>".$data['user']."</td>";
				echo "<td>".$data['role']."</td>";
				echo "<td><a href=\"users.php?page=rediger&id=".$data['id']."\">Rediger</a></td></tr>";
				}
		}



	public function printAddUserForm(){ ?>
			<tr><td>Tilføj Bruger: </td>
			<td style="text-align:right" colspan="8"><?=$sub_links?></td></tr>
			<tr><td><form action="users.php" method="post">
			Navn: </td><td><input type="text" name="name" value="" required></td></tr></br>
			<tr><td> RFID: </td><td><input type="text" name="rfid" value=""></td></tr></br>
			<tr><td> Brugernavn: </td><td><input type="text" name="user" value=""></td></tr><br>
			<tr><td> Cpr: </td><td><input type="text" name="cpr" value=""></td></tr><br>
			<tr><td> Rolle: </td><td><select name="role">
			<?php
			$options = $this->getRoles();
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['navn']."</option>";
			}
			?>
			</select></td></tr><br>
			<tr><td>Web password: </td><td><input type="text" name="passwd" value=""></td></tr><br>
			<tr><td>Dørkode: </td><td><input type="text" name="passd" value=""> <br>
						<input type="hidden" name="adduser" value="1"></td></tr>
			<tr><td></td><td><input type="submit" name="submit" value="Send"></td></tr>

			</table>
			<?php
			}

	public function addNewUser($name, $cpr, $role, $rfid, $passd, $user, $passwd){
			$check_user = $this->checkUser($user);
			//$cpr_enc = password_hash($cpr, PASSWORD_DEFAULT);
			$passwd_enc = password_hash($passwd, PASSWORD_DEFAULT);
			$rfid_enc = password_hash($rfid, PASSWORD_DEFAULT);
			//$passd_enc = password_hash($passd, PASSWORD_DEFAULT);
			if ($check_user == '1'){
				$action = "Bruger eksistere allerede - Kan ikke oprettes!";
				echo $action;
				$this->insertLog($user,"0","0","0","0",$action);
			} else {
				echo $this->insertUser($name, $cpr, $role, $rfid_enc, $passd, $user, $passwd_enc);
				$action = "Bruger ".$user." oprettet!";
				$this->insertLog($user,"0","0","0","0",$action);
			}

		}

	public function printAddZoneForm($zone_id){
			if ($zone_id == "non") {
			?>
			<tr><td>Tilføj Zone: </td>
			<td style="text-align:right" colspan="8"><?=$sub_links?></td></tr>
			<tr><td><form action="zone.php" method="post">
			Zone Navn: </td><td><input type="text" name="name" value="" required></td></tr></br>
			<tr><td> Beskrivelse: </td><td><input type="text" name="description" value=""></td></tr></br>
			<tr><td></td><td><input type="hidden" name="addzone" value="1"><input type="submit" name="submit" value="Send"></form></td></tr></table><?php	} else {
			$datas = $this->getZone($zone_id);
			//return $datas;
			echo "<table><tr><td>Rediger Zone: <form action=\"zone.php\" method=\"post\"></td>";
			foreach ($datas as $data) {
				echo "<tr><td>Zone Navn: </td><td><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required></td></tr>";
				echo "<tr><td>Beskrivelse: </td><td><input type=\"text\" name=\"name\" value=\"".$data['description']."\" required></td></tr>";
				echo "<tr><td><input type=\"hidden\" name=\"zone_id\" value=\"".$data['id']."\"></td>";
				echo "<td><input type=\"hidden\" name=\"addzone\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Send\"></form></td></tr>";
				}echo "</table>";
			}
		}

	public function addNewZone($name, $description){
			//$check_user = $this->checkUser($user);
			echo $this->insertZone($name, $description);
			$action = "New Zone ".$name." added - ".$description."";
			$this->insertLog($name,"0","0","0","0",$action);
			}

	public function showZoneTemp($zid = "0"){
			if ($zid == "0"){
				$datas = $this->getZone("all");
			} else {
				$datas = $this->getZoneF($zid);
			}
			echo "<tr><th>Name</th>";
			echo "<th>Description</th>";
			echo "<th>Temp.</th>";
			echo "<th>Humid.</th>";
			echo "<th>Actions</th></tr>";
			foreach ($datas as $data) {
				echo "<tr><td title=\"".$data['description']."\"> ".$data['name']."</td>";
				echo "<td>".$data['description']."</td>";
				echo "<td>".$data['temp']."C</td>";
				echo "<td>".$data['moist']."%</td>";
				echo "<td><a href=\"zone.php?page=rediger&id=".$data['id']."\">Rediger</a></td></tr>";
				}
		}

	// SHOW ALERTS ON CONTROL PANEL
	public function showAlerts($facility_id = "0", $alert_type, $status, $alert_name){
			$datas = $this->getAlerts($facility_id, $alert_type, $status);
			$alert_type_name = $this->getAlertType($alert_type);
			echo "<input type=\"hidden\" name=\"facility_id\" id=\"facility_id\" value=\"".$facility_id."\">";
			echo "<input type=\"hidden\" name=\"set_status\" id=\"set_status\" value=\"".$status."\">";
			echo "<tr><th>Alert Type</th>";
			echo "<th>Description</th>";
			echo "<th>Who</th>";
			echo "<th>Facility ID</th>";
			echo "<th>Time</th>";
			echo "<th>Status</th></tr>";
			foreach ($datas as $data) {
				echo "<tr><td title=\"".$data['description']."\" bgcolor=\"".$alert_type_name[0]['color']."\"> ".$alert_type_name[0]['name']."</td>";
				echo "<td>".$data['description']."</td>";
				echo "<td>".$data['who']."</td>";
				echo "<td>".$data['facility_id']."</td>";
				echo "<td>".$data['time_created']."</td>";
				echo "<td><input type=\"hidden\" name=\"alert_type\" id=\"alert_type\" value=\"".$alert_type."\"><input type=\"hidden\" name=\"alert_name\" id=\"alert_name\" value=\"".$alert_name."\"><select id=\"".$data['id']."\" class=\"status\" name=\"status\"><option value=\"1\"";
				if ($data['status'] == 1){
					echo " selected";
				}
				echo ">Active</option><option value=\"2\"";
				if ($data['status'] == 2){
					echo " selected";
				}
				echo ">Corrected!</option></select>";
				echo "</td></tr>";
				//echo "<input type=\"hidden\" name=\"".$data['id']."\" id=\"".$data['id']."\" value=\"".$data['id']."\">";
				}
		}

	public function showAllLogs($fac = "all"){
			$datas = $this->getLogs($fac);
			echo "<tr><th>Time</th>";
			echo "<th>Facility</th>";
			echo "<th>Zone</th>";
			echo "<th>IoT Device</th>";
			echo "<th>User</th>";
			echo "<th>Action</th></tr>";
			foreach ($datas as $data) {
				echo "<tr title=\"".$data['id']."\"><td> ".$data['time']."</td>";
				echo "<td>".$data['facility_id']."</td>";
				echo "<td>".$data['zone_id']."</td>";
				echo "<td>".$data['lock_id']."</td>";
				echo "<td>".$data['user_id']."</td>";
				echo "<td>".$data['action']."</td></tr>";
				}
		}

	public function printAddRfidForm(){ ?>
			<tr><td>Tilføj RFID: </td>
			<td style="text-align:right" colspan="8"><?=$sub_links?></td></tr>
			<tr><td><form action="rfid.php" method="post">
			RFID enhedsnavn: </td><td><input type="text" name="name" value="" required></td></tr></br>
			<tr><td> Beskrivelse: </td><td><input type="text" name="description" value=""></td></tr></br>
			<tr><td> Tilhørende zone: </td><td><select name="zone">
			<?php
			$options = $this->getZone("all");
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
			}
			?>
			</select></td></tr><br>
			<tr><td></td><td><input type="hidden" name="addrfid" value="1"><input type="submit" name="submit" value="Send"></form></td></tr></table>
			<?php
			}

	public function addNewRfid($name, $description, $zone){
			echo $this->insertRfid($name, $description, $zone);
			$action = "New RFID: ".$name." added - ".$description." to Zone: ".$zone."";
			$this->insertLog($name,"0","0","0","0",$action);

			}

	public function showAllRfid(){
			$datas = $this->getAllRfid();
			echo "<tr><td>RFID id</td>";
			echo "<td>RFID</td>";
			echo "<td>Beskrivelse</td>";
			echo "<td>Zone</td>";
			echo "<td>Handling</td></tr>";
			foreach ($datas as $data) {
				echo "<tr><td>".$data['id']."</td>";
				echo "<td>".$data['name']."</td>";
				echo "<td>".$data['description']."</td>";
				echo "<td>".$data['zone']."</td>";
				echo "<td><a href=\"rfid.php?page=rediger&id=".$data['id']."\">Rediger</a></td></tr>";
				}
		}



}




?>
