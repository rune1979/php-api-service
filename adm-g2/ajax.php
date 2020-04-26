<?php

require_once '../inc/config.php';
// INCLUDE CLASS FILES
include "inc/class_template.php";
include "inc/class_scheduler.php";

class AjaxC extends Dbc{

	// GET CONTENT FOR ZONE
	protected function get_z_cont($id){
		if($id == "all"){
		$sql = "SELECT * FROM zone_content";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM zone_content WHERE zone_type_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

	// GET IOTS BY IOT TYPE
	protected function getIotByType($id){
		if(empty($id)){
		$sql = "SELECT * FROM iot";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM iot WHERE iot_type_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}


	// GET ZONES BY FACILITY
	protected function get_zone_by_facility($id){
		if($id == "all" || $id == "0"){
		$sql = "SELECT * FROM zone";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM zone WHERE facility_id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

	// GET ZONE
	protected function get_zone($id){
		if($id == "all"){
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

		// UPDATE ALERT STATUS
	protected function updateAlert($id, $status){
		$sql = "UPDATE alerts SET status=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$status, $id]);
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

}



class AjaxV extends AjaxC{

	// GET CONTENT FOR SPECIFIC ZONE
	public function get_zone_content($id){
		$arrdata = $this->get_z_cont($id);
		return json_encode($arrdata);
	}

	// GET IOTS BY IOT TYPE ID
	public function get_iots_by_type($id){
		$arrdata = $this->getIotByType($id);
		return json_encode($arrdata);
	}

	// GET ZONES BY FACILITY
	public function get_zones($id){
		$arrdata = $this->get_zone_by_facility($id);
		return json_encode($arrdata);
	}

		// ADD IOT CAM
		public function get_iot_form_cam($iot_type_id){
				echo "<label for=\"name\">Name:</label><input type=\"text\" name=\"name\" value=\"\">";
				echo "<label for=\"description\">Description:</label><input type=\"text\" name=\"description\" value=\"\">";
				echo "<label for=\"img_url\">Cam url:</label><input type=\"text\" name=\"img_url\" value=\"\">";
				echo "<label for=\"zone\">Zone:</label><select type=\"select\" name=\"zone_id\">";
				$datas = $this->get_zone("all");
				foreach ($datas as $data) {
					echo "<option value=\"".$data['id']."\">".$data['name']."</option>";
					}
				echo "</select>";
				echo "<input type=\"hidden\" name=\"add_iot_type_id\" value=\"2\">";
				echo "<input type=\"hidden\" name=\"iot_type_id\" value=\"".$iot_type_id."\">";
				echo "<input type=\"submit\" name=\"submit\" value=\"Send\">";
			}

		// ADD IOT GENERAL
		public function get_iot_form($iot_type_id){
				echo "<label for=\"name\">Name:</label><input type=\"text\" name=\"name\" value=\"\">";
				echo "<label for=\"description\">Description:</label><input type=\"text\" name=\"description\" value=\"\">";
				echo "<label for=\"local_name\">Local name:</label><input type=\"text\" name=\"local_name\" value=\"\">";
				echo "<label for=\"acceptable_values\">Possible set values</label> <input type=\"text\" name=\"acceptable_values\" value=\"\">";
				echo "<label for=\"zone\">Zone:</label><select type=\"select\" name=\"zone_id\">";
				$datas = $this->get_zone("all");
				foreach ($datas as $data) {
					echo "<option value=\"".$data['id']."\">".$data['name']."</option>";
					}
				echo "</select>";
				echo "<input type=\"hidden\" name=\"add_iot_type_id\" value=\"1\">";
				echo "<input type=\"hidden\" name=\"iot_type_id\" value=\"".$iot_type_id."\">";
				echo "<input type=\"submit\" name=\"submit\" value=\"Send\">";
			}


			public function showAlerts($facility_id = "0", $alert_type, $status, $change_id, $change_status, $alert_name){
					$this->updateAlert($change_id, $change_status);
					$datas = $this->getAlerts($facility_id, $alert_type, $status);
					$alert_type_name = $this->getAlertType($alert_type);
					echo "<input type=\"hidden\" name=\"facility_id\" id=\"facility_id\" value=\"".$facility_id."\">";
					//echo "<input type=\"hidden\" name=\"alert_type\" id=\"alert_type\" value=\"".$alert_type."\">";
					//echo "<input type=\"hidden\" name=\"alert_name\" id=\"alert_name\" value=\"".$alert_name."\">";
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

						}
				}


		// MAKE SECURITY STRING
		public function get_security_string(){
			// Cryptographically Secure randomizer
			$n = 20;
			$result = bin2hex(random_bytes($n));
			return $result;
		}

}

// CREATE NEW OBJECT
$obj = new AjaxV();

// GET CONTENT FOR SPECIFIC ZONE
if(isset($_POST['get_z_content']) && !empty($_POST['get_z_content'])){
		echo $obj->get_zone_content($_POST['get_z_content']);
	}

// ADD TIME SEGMENT TO TEMPLATE
if(isset($_POST['temp_id']) && !empty($_POST['temp_id'])){
		//global $templates;
		$templates = new ViewTemplate();
		if ($_POST['time_seg_id'] == 0 || $_POST['todo'] == "add"){
			// ADD NEW SEGMENT
			$templates->add_time_template($_POST['temp_id'], $_POST['iot_types'], $_POST['iot_id'], $_POST['description'], $_POST['from_day'], $_POST['days'], $_POST['total'], $_POST['from_time'], $_POST['to_time'], $_POST['set_val']);
		} elseif ($_POST['todo'] == "save") {
			// EDIT SEGMENT
			$templates->edit_time_template($_POST['time_seg_id'], $_POST['temp_id'], $_POST['iot_types'], $_POST['iot_id'], $_POST['description'], $_POST['from_day'], $_POST['days'], $_POST['total'], $_POST['from_time'], $_POST['to_time'],$_POST['set_val']);
		} elseif ($_POST['todo'] == "delete") {
			// DELETE SEGMENT
			$templates->delete_time_template($_POST['time_seg_id'], $_POST['temp_id'], $_POST['description']);
		}
	}

	//ADD/EDIT TIMER SHEDULEING TO IOT
	if(isset($_POST['timer_schedule_id']) && !empty($_POST['timer_schedule_id'])){
			//global $templates;
			//echo "test";
			$time_schedule = new ViewScheduler();
			if ($_POST['timer_schedule_id'] == 0 || $_POST['todo'] == "add"){
				// ADD NEW TIMER
				$time_schedule->add_timer($_POST['template_id'], $_POST['iot_id'], $_POST['description'], $_POST['from_date'], $_POST['to_date'], $_POST['total'], $_POST['from_time'], $_POST['to_time'], $_POST['set_val']);
			} elseif ($_POST['todo'] == "save") {
				// EDIT TIMER
				$time_schedule->edit_timer($_POST['timer_schedule_id'], $_POST['iot_id'], $_POST['description'], $_POST['from_date'], $_POST['to_date'], $_POST['total'], $_POST['from_time'], $_POST['to_time'],$_POST['set_val']);
			} elseif ($_POST['todo'] == "delete") {
				// DELETE TIMER
				$time_schedule->delete_timer($_POST['timer_schedule_id'], $_POST['iot_id'], $_POST['description']);
			}
		}

		// IMPORT ALL SEGMENTS IN TEMPLATE TO IOT
		if(isset($_POST['ajax_import_template']) && !empty($_POST['ajax_import_template'])){
				$time_schedule = new ViewScheduler();
				$time_schedule->import_segments($_POST['template_id'], $_POST['template_iot'], $_POST['apply_to_iot'], $_POST['activation_date']);
			}

// GET IOT BY TYPE
if(isset($_POST['get_iot_by_type']) && !empty($_POST['get_iot_by_type'])){
		echo $obj->get_iots_by_type($_POST['get_iot_by_type']);
	}

// ZONE BY FACILITY
if(isset($_POST['get_zones']) && !empty($_POST['get_zones'])){
		echo $obj->get_zones($_POST['get_zones']);
	}

// GET NEW ALERT TABLE
if(isset($_POST['change_id']) && !empty($_POST['change_id'])){
		echo $obj->showAlerts($_POST['facility_id'],$_POST['alert_type'],$_POST['status'],$_POST['change_id'],$_POST['change_status'],$_POST['alert_name']);
	}

// GET NEW SECURITY STRING
if(isset($_POST['get_sec_string']) && !empty($_POST['get_sec_string'])){
		echo $obj->get_security_string();
	}

// GET SPECIFIC FORM FOR IOT SUBMISSION
if(isset($_POST['iot_type']) && !empty($_POST['iot_type'])){
		if ($_POST['iot_type'] == '5'){
		echo $obj->get_iot_form_cam($_POST['iot_type']);
		} else {
		echo $obj->get_iot_form($_POST['iot_type']);
		}
	}

?>
