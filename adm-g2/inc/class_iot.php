<?php

class IoT extends Dbc{

	// GET IOT TYPES
	protected function getIotTypes($id = "all"){
		if($id == "all" || $id == "0"){
		$sql = "SELECT * FROM iot_type";
		$stmt = $this->prpConnect()->query($sql);
		} else {
		$sql = "SELECT * FROM iot_type WHERE id = ? ORDER BY id DESC";
		$stmt = $this->prpConnect()->prepare($sql);
		$stmt->execute([$id]);
		}
		$results = $stmt->fetchAll();
		return $results;
		}

	// INSERT NEW IOT insertIoT
	protected function insertIoT($name, $description, $iot_type_id, $img_url, $zone_id, $local_name, $acceptable_values){
		global $standard;
		$sql = "INSERT INTO iot (name, description, iot_type_id, img_url, zone_id, local_name, facility_id, acceptable_values) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->prpConnect()->prepare($sql);
		$facility_id = $standard->get_zone($zone_id);
		if ($stmt->execute([$name, $description, $iot_type_id, $img_url, $zone_id, $local_name, $facility_id[0]['facility_id'], $acceptable_values])){
			$action = "New IoT device added to zone: ".$facility_id[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($facility_id[0]['facility_id'],$zone_id,$name,$_SESSION['name'],$action);
		} else {
			$action = "Error - New IoT device NOT added!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($facility_id[0]['facility_id'],$zone_id,$name,$_SESSION['name'],$action);
		}
		}

	// UPDATE IOT INFO
	protected function updateIoTDevice($id, $name, $description, $img_url, $zone_id, $local_name, $acceptable_values){
		global $standard;
		$sql = "UPDATE iot SET name=?, description=?, img_url=?, zone_id=?, local_name=?, facility_id=?, acceptable_values=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$facility_id = $standard->get_zone($zone_id);
		if ($stmt->execute([$name, $description, $img_url, $zone_id, $local_name, $facility_id[0]['facility_id'], $acceptable_values, $id])){
			$action = "Updated IoT id: ".$id." - name: ".$name." at zone: ".$facility_id[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($facility_id[0]['facility_id'],$zone_id,$id,$_SESSION['name'],$action);
		} else {
			$action = "Error - Updating IoT id: ".$id." - name: ".$name." at zone: ".$facility_id[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($facility_id[0]['facility_id'],$zone_id,$id,$_SESSION['name'],$action);
		}
		}

	// UPDATE IOT SETTINGS
	protected function update_IoTSet($id, $set_val_forced, $set_val_once, $set_val, $alert_type, $max_alert, $min_alert, $equal_alert, $not_equal_alert, $zone_id){
		global $standard;
		$sql = "UPDATE iot SET set_val_forced=?, set_val_once=?, set_val=?, alert_type=?, max_alert=?, min_alert=?, equal_alert=?, not_equal_alert=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$zone = $standard->get_zone($zone_id);
		if ($stmt->execute([$set_val_forced, $set_val_once, $set_val, $alert_type, $max_alert, $min_alert, $equal_alert, $not_equal_alert, $id])){
			$action = "Updated IoT id: ".$id." - at zone: ".$zone[0]['name']." updated alert or action settings!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone_id,$id,$_SESSION['name'],$action);
		} else {
			$action = "Error - Updating settings for IoT id: ".$id." - at zone: ".$zone[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone_id,$id,$_SESSION['name'],$action);
		}
		}

	protected function deleteIoT($iot_id){
		global $standard;
		$iot = $standard->get_iot($iot_id);
		$zone = $standard->get_zone($iot[0]['zone_id']);
		$sql = "DELETE FROM iot WHERE id = ?";
		$stmt = $this->prpConnect()->prepare($sql);
		$facility_id = $standard->get_zone($zone_id);
		if ($stmt->execute([$iot_id])){
			$action = "IoT device: ".$iot[0]['name']." deleted!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone[0]['id'],$iot[0]['id'],$_SESSION['name'],$action);
		} else {
			$action = "Error - Could not delete device!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone[0]['id'],$iot[0]['id'],$_SESSION['name'],$action);
		}
		}


}



class ViewIoT extends IoT{

	// SETUP FORM FOR EDIT OR ADD
	public function printAddIoTForm($id){
			global $standard;
			if ($id == "non"){
			?><div class="formula"><h1>Add IoT device</h1>
				<form><label for="facility">IoT Type:</label><select id="iot_type" name="iot_type" type="select">
					<?php
					$options = $this->getIotTypes();
					foreach ($options as $option) {
					echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
					}
					?></select></form>
			<form action="iot.php" method="post" id="form_iot">
			</form>
			<?php
			} else {
			$datas = $standard->get_iot($id);
			echo "<div class=\"formula\"><h1>Edit IoT</h1><form action=\"iot.php\" method=\"post\">";
				foreach ($datas as $data) {
				echo "<label for=\"name\">Name</label> <input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
				echo "<label for=\"description\">Description</label> <input type=\"text\" name=\"description\" value=\"".$data['description']."\">";
				echo "<label for=\"acceptable_values\">Acceptable values</label> <input type=\"text\" title=\"A description of what possible values to return\" name=\"acceptable_values\" value=\"".$data['acceptable_values']."\">";
				echo "<label for=\"zone\">Zone</label><select name=\"zone\">";
				$options = $standard->get_zone();
					foreach ($options as $option) {
						echo "<option value=\"".$option['id']."\"";
						if ($option['id'] == $data['zone']){
							echo "selected";
						}
						echo ">".$option['name']."</option>";
					}
				echo "</select>";
				$types = $this->getIotTypes($data['iot_type_id']);
					foreach ($types as $type) {
						// code...
						echo "<label for=\"type\">IoT Type</label> <input type=\"text\" name=\"type\" value=\"".$type['name']."\" readonly>";
						echo "<input type=\"hidden\" name=\"iot_type\" value=\"".$type['id']."\">";
					}

				// IF WEB-CAM SHOW FIELD
				if ($data['iot_type_id'] == 5){
					echo "<label for=\"img_url\">Cam URL</label> <input type=\"text\" name=\"img_url\" value=\"".$data['img_url']."\">";
				}	else {
					echo "<input type=\"hidden\" name=\"img_url\" value=\"\">";
				}
				echo "<label for=\"local_name\">Local name</label> <input type=\"text\" name=\"local_name\" value=\"".$data['local_name']."\">";
				echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
				echo "<input type=\"hidden\" name=\"add_iot\" value=\"2\"><input type=\"submit\" name=\"submit\"value=\"Send\"></form>";
				}
				echo "</div>";
			}
			}

			public function printIoTSettings($id){
					global $standard;
					$datas = $standard->get_iot($id);
					echo "<div class=\"formula\"><h1>IoT Manual Settings</h1><form action=\"iot.php\" method=\"post\">";
						foreach ($datas as $data) {
						echo "<label for=\"name\">Name</label><input type=\"text\" style=\"color:gray;\" name=\"name\" value=\"".$data['name']."\" readonly>";
						echo "<label for=\"name\">Current value</label> <input type=\"text\" style=\"color:gray;\" name=\"name\" value=\"".$data['cur_val']."\" readonly>";
						echo "<div class=\"formula\">Send new value to IoT</div>";
						echo "<label title=\"This value will be returned to the unit on next call only!\" for=\"set_val_once\">Set temp value</label> <input title=\"This value will be returned to the unit on next call only!\" type=\"text\" name=\"set_val_once\" value=\"".$data['set_val_once']."\">";
						echo "<label title=\"This value will be returned to the unit on every call if nothing else is determined!\" for=\"set_val\">Set default value</label> <input title=\"This value will be returned to the unit on every call if nothing else is determined!\" type=\"text\" name=\"set_val\" value=\"".$data['set_val']."\">";
						echo "<label title=\"If set, this value will overrule all other return values!\" for=\"set_val\">Set forced value</label> <input title=\"If set, this value will overrule all other return values!\" type=\"text\" name=\"set_val_forced\" value=\"".$data['set_val_forced']."\">";
						echo "<div class=\"formula\">Alert Setup</div>";
						echo "<label for=\"zone\">Alert Type</label><select name=\"alert_type\">";
						$options = $standard->get_alert_type();

							foreach ($options as $option) {
								echo "<option value=\"".$option['id']."\"";
								if ($option['id'] == $data['alert_type']){
									echo "selected";
								}
								echo ">".$option['name']."</option>";
							}
						echo "</select>";
						echo "<label title=\"If current value exceeds this value!\" for=\"max_alert\">Max. value (>)</label> <input title=\"If current value exceeds this value!\" type=\"text\" name=\"max_alert\" value=\"".$data['max_alert']."\">";
						echo "<label title=\"If current value goes below this value!\" for=\"min_alert\">Min. value (<)</label> <input title=\"If current value goes below this value!\" type=\"text\" name=\"min_alert\" value=\"".$data['min_alert']."\">";
						echo "<label title=\"If current value is equal to this value!\" for=\"equal_alert\">Equal to (==)</label> <input title=\"If current value is equal to this!\" type=\"text\" name=\"equal_alert\" value=\"".$data['equal_alert']."\">";
						echo "<label title=\"If current value is NOT equal to this value!\" for=\"not_equal_alert\">Not equal to (!==)</label> <input title=\"If current value is NOT equal to this!\" type=\"text\" name=\"not_equal_alert\" value=\"".$data['not_equal_alert']."\">";

						echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
						echo "<input type=\"hidden\" name=\"zone_id\" value=\"".$data['zone_id']."\">";
						echo "<input type=\"hidden\" name=\"add_iot\" value=\"1\"><input type=\"submit\" name=\"submit\"value=\"Send\"></form>";
						}
						echo "</div>";

					}

	// INSERT NEW IOT DEVICE
	public function addNewIoT($name, $description, $iot_type_id, $img_url, $zone_id, $local_name, $acceptable_values){
				$this->insertIoT($name, $description, $iot_type_id, $img_url, $zone_id, $local_name, $acceptable_values);
			}

	// UPDATE IOT DEVICE
	public function updateIoT($id, $name, $description, $img_url, $zone_id, $local_name, $acceptable_values){
			echo $this->updateIoTDevice($id, $name, $description, $img_url, $zone_id, $local_name, $acceptable_values);
			}

	// UPDATE IOT DEVICE
	public function updateIoTSettings($id, $set_val_forced, $set_val_once, $set_val, $alert_type, $max_alert, $min_alert, $equal_alert, $not_equal_alert, $zone_id){
			echo $this->update_IoTSet($id, $set_val_forced, $set_val_once, $set_val, $alert_type, $max_alert, $min_alert, $equal_alert, $not_equal_alert, $zone_id);
			}

	// DELETE IOT DEVICE
	public function deleteIoTView($iot_id){
				$this->deleteIoT($iot_id);
			}


	// SHOW IOT DEVICES
	public function showIoT($facility_id){
			global $standard;
			$datas = $standard->get_iot();
			echo "<tr><th>Device Name</th>";
			echo "<th>Zone</th>";
			echo "<th>Facility</th>";
			echo "<th>Description</th>";
			echo "<th>Current value</th>";
			echo "<th>Actions</th></tr>";
			foreach ($datas as $data) {
				$zone = $standard->get_zone($data['zone_id']);
				foreach ($zone as $zones) {
					if ($zones['facility_id'] == $facility_id || $facility_id == "all" || $facility_id == 0){
						$facility = $standard->get_facility($zones['facility_id']);
						echo "<tr><td>".$data['name']."</td>";
						echo "<td>".$zones['name']."</td>";
						echo "<td>".$facility[0]['name']."</td>";
						echo "<td>".$data['description']."</td>";
						echo "<td><b>".$data['cur_val']."</b></td>";
						echo "<td><a href=\"iot.php?page=rediger&id=".$data['id']."\">Edit</a> | <a href=\"iot.php?page=delete&id=".$data['id']."\" onclick=\"return confirm('Are you sure you want to delete this IoT device!?');\">Delete</a> | <a href=\"iot.php?page=settings&id=".$data['id']."\">Manual Settings</a> | <a href=\"iot.php?page=timer&id=".$data['id']."&name=".$data['name']."\">Timer</a></td></tr>";
					}
				}
			}
		}

		public function printTimeSchedule($id, $name){
				global $standard;
				// THIS IS FOR EDITING AN EXISTING TEMPLATE
				if (empty($id)){
						echo "You have to choose something to edit!";
				} else {
				//$datas = $standard->get_template($id, null);
				//$cont = $standard->get_temp_cont($id);
				$cont = $standard->get_scheduler($id);
				$iots = $standard->get_iot_type();
				echo "<h3>Set timer for: ".$name."</h3>";
				echo "<table width=\"100%\">";
				echo "<tr><th>Description</th>";
				echo "<th>From date (incl. selected)</th>";
				echo "<th>To date (excl. selected)</th>";
				echo "<th>Days of week</th>";
				echo "<th>From time</th>";
				echo "<th>To time</th>";
				echo "<th>Set value</th>";
				echo "<th>Action</th></tr>";
				foreach ($cont as $content) {
					echo "<tr><form action=\"\" id=\"".$content[id]."\" method=\"post\">";
					echo "<input type=\"hidden\" id=\"".$content[id]."_ibt\" name=\"iot_id\" value=\"".$id."\">";
					if (!empty($content[time_temp_id])){
						echo "<div>Importet from template_id: ".$content[time_temp_id]."</div>";
					}
					echo "<td><input type=\"text\" name=\"description\" value=\"".$content['description']."\"></td>";
					echo "<td><input type=\"date\" name=\"from_date\" size=\"3\" min=\"0\" value=\"".$content['from_date']."\"></td>";
					echo "<td><input type=\"date\" name=\"to_date\" size=\"3\" min=\"0\" value=\"".$content['to_date']."\"></td>";
					// MAKE SURE TO FORMAT AS BYTE
					$byte_format = base_convert($content['daysofweek'], 10, 2);
					$each_bit = preg_split('//', $byte_format, -1, PREG_SPLIT_NO_EMPTY);

					echo "<td><div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"64\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[1]=='1')?'checked':"")."><label for=\"monday\">Monday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"32\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[2]=='1')?'checked':"")."><label for=\"tuesday\">Tuesday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"16\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[3]=='1')?'checked':"")."><label for=\"wednesday\">Wednesday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"8\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[4]=='1')?'checked':"")."><label for=\"thursday\">Thursday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"4\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[5]=='1')?'checked':"")."><label for=\"friday\">Friday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"2\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[6]=='1')?'checked':"")."><label for=\"saturday\">Saturday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"1\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[7]=='1')?'checked':"")."><label for=\"sunday\">Sunday</label></div>";
					echo "<div><input type=\"hidden\" id=\"".$content['id']."_total\" size=\"2\" name=\"total\" value=\"".$content['daysofweek']."\"/></div></td>";

					echo "<td><input type=\"time\" name=\"from_time\" value=\"".$content['start_time']."\"></td>";
					echo "<td><input type=\"time\" name=\"to_time\" value=\"".$content['to_time']."\"></td>";
					echo "<td><input type=\"text\" name=\"set_val\" size=\"3\" value=\"".$content['set_val']."\"></td>";
					echo "<input type=\"hidden\" name=\"template_id\" value=\"".$content['time_temp_id']."\"><input type=\"hidden\" name=\"timer_schedule_id\" value=\"".$content['id']."\"></td>";
					echo "<td><input type=\"submit\" name=\"submit\" class=\"submit_time_schedule\" value=\"save\"><input type=\"submit\" name=\"submit\" class=\"submit_time_schedule\" value=\"delete\"></form></td></tr>";
				}
				echo "<tr><form action=\"\" id=\"0\" method=\"post\"><input type=\"hidden\" id=\"".$id."_ibt\" name=\"iot_id\" value=\"".$id."\">";
				echo "<td><input type=\"text\" name=\"description\" value=\"\"></td>";
				echo "<td><input type=\"date\" size=\"3\" name=\"from_date\" min=\"0\" value=\"\"></td>";
				echo "<td><input type=\"date\" size=\"3\" name=\"to_date\" min=\"0\" value=\"\"></td>";
				$hexadecimal = '255';
				$number = base_convert($hexadecimal, 10, 2);
				$new = "0";
				echo "<td><div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"64\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"monday\">Monday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"32\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"tuesday\">Tuesday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"16\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"wednesday\">Wednesday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"8\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"thursday\">Thursday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"4\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"friday\">Friday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"2\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"saturday\">Saturday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"1\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"sunday\">Sunday</label></div><div><input type=\"text\" id=\"0_total\" size=\"2\" name=\"total\" value=\"255\"/></div></td>";
				echo "<td><input type=\"time\" size=\"5\" step=\"1\" name=\"from_time\" value=\"00:00:00\"></td>";
				echo "<td><input type=\"time\" size=\"5\" step=\"1\" name=\"to_time\" value=\"00:00:00\"></td>";
				echo "<td><input type=\"text\" name=\"set_val\" size=\"3\" value=\"\"><input type=\"hidden\" name=\"template_id\" value=\"\"><input type=\"hidden\" name=\"timer_schedule_id\" value=\"7\"></td>";
				echo "<td><div class=\"success\"></div><input type=\"submit\" name=\"submit\" class=\"submit_time_schedule\" value=\"add\"></form></td></tr>";
				echo "</table>";
				}
			}




}




?>
