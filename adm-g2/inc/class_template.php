<?php

class ControlTemplate extends Dbc{

	// ADD NEW TIME SEGMENT TO TEMPLATE, ONLY FOR AJAX USE
	protected function addTimeTemplate($temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val){
		// INCLUDE CLASS FILES FOR AJAX
		include "inc/class.php";
		$standard = new ViewBasic();
		// THE REST IS NORMAL CONTROL METHOD
		$sql = "INSERT INTO time_control (time_temp_id, iot_type_id, iot_id, description, from_day, days, daysofweek, from_time, to_time, set_val) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->prpConnect()->prepare($sql);
		// GET MOTHER TEMPLATE INFO
		$template = $standard->get_template($temp_id, null);
		if ($stmt->execute([$temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val])){
			$action = "New Time Segment Added to Template id: ".$template[0]['name']."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - While trying to add New Time Segment to Template id: ".$template[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		}
		}

	// EDIT TIME SEGMENT IN TEMPLATE
	protected function editTimeTemplate($id, $temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val){
		// INCLUDE CLASS FILES FOR AJAX
		include "inc/class.php";
		$standard = new ViewBasic();
		// THE REST IS NORMAL CONTROL METHOD
		$sql = "UPDATE time_control SET iot_type_id=?, iot_id=?, description=?, from_day=?, days=?, daysofweek=?, from_time=?, to_time=?, set_val=? WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		// GET MOTHER TEMPLATE INFO
		$template = $standard->get_template($temp_id, null);
		if ($stmt->execute([$iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val, $id])){
			$action = "Time Segment: ".$id."-".$description." edited. Belonging to Template name: ".$template[0]['name']."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - Trying to edit Time Segment: ".$id."-".$description." edited. Belonging to Template name: ".$template[0]['name']."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		}
		}

	// DELETE TIME SEGMENT IN TEMPLATE
	protected function deleteTimeTemplate($id, $temp_id, $description){
		// INCLUDE CLASS FILES FOR JQUERY
		include "inc/class.php";
		$standard = new ViewBasic();

		// THE REST IS NORMAL CONTROL METHOD
		$sql = "DELETE FROM time_control WHERE id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		// GET MOTHER TEMPLATE INFO
		$template = $standard->get_template($temp_id, null);
		if ($stmt->execute([$id])){
			$action = "Time Segment: ".$id."-".$description." DELETED. Belonging to Template name: ".$template[0]['name']."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		} else {
			$action = "DB Error - Trying to DELETE Time Segment: ".$id."-".$description.". Belonging to Template name: ".$template[0]['name']."";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog(null,null,null,$_SESSION['name'],$action);
		}
		}

	// ADD NEW TEMPLATE
	protected function addTemplate($user_id, $zone_id, $fac_id, $name, $description){
		global $standard;
		$sql = "INSERT INTO time_templates (name, description, user_id, zone_id, fac_id) VALUES (?, ?, ?, ?, ?)";
		$stmt = $this->prpConnect()->prepare($sql);
		$zone = $standard->get_zone($zone_id);
		if ($stmt->execute([$name, $description, $user_id, $zone_id, $fac_id])){
			$action = "New Time Template added! by: ".$user_id.", named: ".$name.", to Zone: ".$zone[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone_id,null,$user_id,$action);
		} else {
			$action = "DB Error - While trying to add New Time Template!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($zone[0]['facility_id'],$zone_id,null,$user_id,$action);
		}
		}

		// EDIT TEMPLATE
		protected function editTemplate($id, $user_id, $zone_id, $fac_id, $name, $description){
			global $standard;
			$sql = "UPDATE time_templates SET name=?, description=?, user_id=?, zone_id=?, fac_id=? WHERE id=?";
			$stmt = $this->prpConnect()->prepare($sql);
			$zone = $standard->get_zone($zone_id);
			if ($stmt->execute([$name, $description, $user_id, $zone_id, $fac_id, $id])){
				$action = "Time Template edited! by: ".$user_id.", named: ".$name.", to Zone: ".$zone[0]['name']."!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($fac_id,$zone_id,null,$user_id,$action);
			} else {
				$action = "DB Error - While trying to Edit Time Template!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($fac_id,$zone_id,null,$user_id,$action);
			}
			}

			// DELETE TEMPLATE
		protected function deleteTemplate($id){
			global $standard;
			$template = $standard->get_template($id, null);
			$sql = "DELETE FROM time_templates WHERE id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$zone = $standard->get_zone($template[0]['zone_id']);
			if ($stmt->execute([$id])){
				$action = "Time Template: ".$template[0]['name']." deleted, at Zone:".$zone[0]['name']."!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($template[0]['fac_id'],$template[0]['zone_id'],"",$_SESSION['name'],$action);
			} else {
				$action = "Error - Could not delete Time Template: ".$template[0]['name']." at Zone:".$zone[0]['name']."!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($template[0]['fac_id'],$template[0]['zone_id'],"",$_SESSION['name'],$action);
			}
			}


			// GET TEMPLATE's CONTENT !! TEMPORARY FIX FOR AJAX METHOD
		protected function getTempCont($id){
			$sql = "SELECT * FROM time_control WHERE time_temp_id = ?";
			$stmt = $this->prpConnect()->prepare($sql);
			$stmt->execute([$id]);
			$results = $stmt->fetchAll();
			return $results;
		}


}



class ViewTemplate extends ControlTemplate{


	// DELETE TEMPLATE
	public function delete_template($id){
				$this->deleteTemplate($id);
			}

	public function showTemplates($facility_id){
			global $standard;
			$datas = $standard->get_template(null, $facility_id);
			echo "<tr><th>Template Name</th>";
			echo "<th>Description</th>";
			echo "<th>Facility</th>";
			echo "<th>Zone</th>";
			echo "<th>Last update</th>";
			echo "<th>Author</th>";
			echo "<th>Actions</th></tr>";
			foreach ($datas as $data) {
				echo "<tr><td> ".$data['name']."</td>";
				echo "<td>".$data['description']."</td>";
				//$user_name = $standard->get_user($data['user_id']);
				//foreach ($user_name as $un) {
				//echo "<td>".$data['user_id']." : ".$un['name']."</td>";
				//}
				$facility_name = $standard->get_facility($data['fac_id']);
				foreach ($facility_name as $fc) {
				echo "<td>".$data['fac_id']." : ".$fc['name']."</td>";
				}
				$zone_name = $standard->get_zone($data['zone_id']);
				foreach ($zone_name as $zn) {
				echo "<td>".$data['zone_id']." : ".$zn['name']."</td>";
				}
				echo "<td>".$data['last_update']."</td>";
				echo "<td>".$data['user_id']."</td>";
				echo "<td><a href=\"time_template.php?page=edit&id=".$data['id']."\">Edit</a> | ";
				echo "<a href=\"time_template.php?page=delete&id=".$data['id']."\" onclick=\"return confirm('Are you sure you want to delete this Template!?');\">Delete</a> | ";
				echo "<a href=\"time_template.php?page=edit_template&id=".$data['id']."\">Edit Features</a> | ";
				echo "<a href=\"time_template.php?page=execute_template&id=".$data['id']."\">Use</a>";
				echo "</td></tr>";
				}
		}

		public function printTimeTemplates($id){
				global $standard;
				// THIS IS FOR EDITING AN EXISTING TEMPLATE
				if (empty($id)){
						echo "You have to choose something to edit!";
				} else {
				$datas = $standard->get_template($id, null);
				$cont = $standard->get_temp_cont($id);
				$iots = $standard->get_iot_type();
				echo "<h3>Edit: ".$datas[0]['name']."</h3>";
				echo "<table width=\"100%\">";
				echo "<tr><th>IoT Type</th>";
				echo "<th>Description</th>";
				echo "<th>From day</th>";
				echo "<th>Num. of days</th>";
				echo "<th>Days of week</th>";
				echo "<th>Start time</th>";
				echo "<th>To time</th>";
				echo "<th>Set value</th>";
				echo "<th>Action</th></tr>";
				foreach ($cont as $content) {
					echo "<tr><td><form action=\"\" id=\"".$content[id]."\" method=\"post\">";
					echo "<select id=\"".$content[id]."\" class=\"iot_type_seg_temp\" name=\"iot_types\">";
					foreach ($iots as $iot) {
						echo "<option value=\"".$iot['id']."\"";
						if ($iot['id'] == $content['iot_type_id']){
							echo "selected";
						}
						echo ">".$iot['name']."</option>";
					}
					echo "</select></div>";
					echo "<div><select id=\"".$content[id]."_ibt\" name=\"iot_id\">";
					$iotbytype = $standard->get_iot_by_type($content['iot_type_id']);
					foreach ($iotbytype as $ibt) {
						echo "<option value=\"".$ibt['id']."\"";
						if ($ibt['id'] == $content['iot_id']){
							echo "selected";
						}
						echo ">".$ibt['name']."</option>";
					}
					echo "</select></div></td>";
					echo "<td><input type=\"text\" name=\"description\" value=\"".$content['description']."\"></td>";
					echo "<td><input type=\"number\" name=\"from_day\" size=\"3\" min=\"0\" value=\"".$content['from_day']."\"></td>";
					echo "<td><input type=\"number\" name=\"days\" size=\"3\" min=\"0\" value=\"".$content['days']."\"></td>";
					// MAKE SURE TO FORMAT AS BYTE
					$byte_format = base_convert($content['daysofweek'], 10, 2);
					$each_bit = preg_split('//', $byte_format, -1, PREG_SPLIT_NO_EMPTY);

					echo "<td><div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"64\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[1]=='1')?'checked':"")."><label for=\"monday\">Monday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"32\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[2]=='1')?'checked':"")."><label for=\"tuesday\">Tuesday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"16\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[3]=='1')?'checked':"")."><label for=\"wednesday\">Wednesday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"8\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[4]=='1')?'checked':"")."><label for=\"thursday\">Thursday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"4\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[5]=='1')?'checked':"")."><label for=\"friday\">Friday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"2\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[6]=='1')?'checked':"")."><label for=\"saturday\">Saturday</label></div>";
					echo "<div><input type=\"checkbox\" id=\"".$content['id']."_check\" name=\"check_week\" value=\"1\" class=\"check_week\" onchange=\"daysofWeek('".$content['id']."')\" ".(($each_bit[7]=='1')?'checked':"")."><label for=\"sunday\">Sunday</label></div><div><input type=\"hidden\" id=\"".$content['id']."_total\" size=\"2\" name=\"total\" value=\"".$content['daysofweek']."\"/></div></td>";

					echo "<td><input type=\"time\" name=\"from_time\" value=\"".$content['from_time']."\"></td>";
					echo "<td><input type=\"time\" name=\"to_time\" value=\"".$content['to_time']."\"></td>";
					echo "<td><input type=\"text\" name=\"set_val\" size=\"3\" value=\"".$content['set_val']."\"></td>";
					echo "<input type=\"hidden\" name=\"temp_id\" value=\"".$id."\"><input type=\"hidden\" name=\"time_seg_id\" value=\"".$content['id']."\"></td>";
					echo "<td><input type=\"submit\" name=\"submit\" class=\"submit_template\" value=\"save\"><input type=\"submit\" name=\"submit\" class=\"submit_template\" value=\"delete\"><div class=\"success\"></div></form></td></tr>";
				}
				echo "<tr><td><form action=\"\" id=\"0\" method=\"post\">";
				echo "<select id=\"0\" class=\"iot_type_seg_temp\" name=\"iot_types\">";
				foreach ($iots as $iot) {
					echo "<option value=\"".$iot['id']."\">".$iot['name']."</option>";
				}
				echo "</select>";
				echo "<div><select id=\"0_ibt\" name=\"iot_id\">";
				echo "</select></div></td>";
				echo "<td><input type=\"text\" name=\"description\" value=\"\"></td>";
				echo "<td><input type=\"number\" size=\"3\" name=\"from_day\" min=\"0\" value=\"\"></td>";
				echo "<td><input type=\"number\" size=\"3\" name=\"days\" min=\"0\" value=\"\"></td>";
				$hexadecimal = '255';
				$number = base_convert($hexadecimal, 10, 2);
				$new = "0";
				echo "<td><div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"64\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"monday\">Monday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"32\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"tuesday\">Tuesday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"16\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"wednesday\">Wednesday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"8\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"thursday\">Thursday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"4\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"friday\">Friday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"2\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"saturday\">Saturday</label></div>";
				echo "<div><input type=\"checkbox\" id=\"0_check\" name=\"check_week\" value=\"1\" class=\"check_week\" onchange=\"daysofWeek('".$new."')\" checked><label for=\"sunday\">Sunday</label></div><div><input type=\"hidden\" id=\"0_total\" size=\"2\" name=\"total\" value=\"255\"/></div></td>";
				echo "<td><input type=\"time\" size=\"5\" step=\"1\" name=\"from_time\" value=\"00:00:00\"></td>";
				echo "<td><input type=\"time\" size=\"5\" step=\"1\" name=\"to_time\" value=\"00:00:00\"></td>";
				echo "<td><input type=\"text\" name=\"set_val\" size=\"3\" value=\"\"><input type=\"hidden\" name=\"temp_id\" value=\"".$id."\"><input type=\"hidden\" name=\"time_seg_id\" value=\"0\"></td>";
				echo "<td><div class=\"success\"></div><input type=\"submit\" name=\"submit\" class=\"submit_template\" value=\"add\"></form></td></tr>";
				echo "</table>";
				}
			}


			public function printImportTemplates($id){
					global $standard;
					// THIS IS FOR EDITING AN EXISTING TEMPLATE
					if (empty($id)){
							echo "You have to choose a template to import!";
					} else {
					$datas = $standard->get_template($id, null);
					$cont = $standard->get_temp_cont($id);
					$iots = $standard->get_iot_type();
					echo "<h3>Activate Template Items: ".$datas[0]['name']."</h3>";
					echo "<table width=\"100%\">";
					echo "<tr><th>IoT Template Name</th>";
					echo "<th>Apply to IoT</th>";
					echo "<th>Activation date</th>";
					echo "<th>Action</th></tr>";
					$temp = $cont;

					// FIND UNIQUE IOTS IN TEMPLATE
					$uniq = array();
					foreach($cont as $k=>$v) if(!isset($uniq[$v['iot_id']])) $uniq[$v['iot_id']] = $v;
					$output = array_values($uniq);

					foreach ($output as $content) {
						echo "<tr><td><form action=\"\" id=\"".$content[id]."\" method=\"post\">";
						$iotbytype = $standard->get_iot_by_type($content['iot_type_id']);
						foreach ($iotbytype as $ibt) {
							if ($ibt['id'] == $content['iot_id']){
								echo "<input type=\"hidden\" name=\"template_iot\" value=\"".$ibt['id']."\">".$ibt['name']."";
							}
						}
						echo "</td><td><select id=\"".$content[id]."_ibt\" name=\"apply_to_iot\">";
						foreach ($iotbytype as $ibt) {
							echo "<option value=\"".$ibt['id']."\"";
							if ($ibt['id'] == $content['iot_id']){
								echo "selected";
							}
							echo ">".$ibt['name']."</option>";
						}
						echo "</select></td>";
						echo "<td><input type=\"date\" name=\"activation_date\" value=\"\"></td>";
						echo "<input type=\"hidden\" name=\"template_id\" value=\"".$id."\"><input type=\"hidden\" name=\"ajax_import_template\" value=\"1\"></td>";
						echo "<td><input type=\"submit\" name=\"submit\" class=\"import_template\" value=\"import\"></form></td></tr>";
					}
					echo "</table>";
					}
				}


	public function printTemplateForm($id){
			global $standard;
			if ($id == null) {
				// ADDING NEW TEMPLATE
				?>
			<div class="formula"><h1>Add new template</h1>
			<form action="time_template.php" method="post">
			<label for="name">Template name</label><input type="text" name="name" value="" required>
			<label for="description">Description</label> <input type="text" name="description" value="" required>
			<input type="hidden" name="user_id" value="<?=$_SESSION['name']?>" required>
			<input type="hidden" name="fac_id" value="<?=$_SESSION['facility_select']?>" required>
			<label for="zone">Zone</label> <select name="zone_id">
			<?php
			// GETTING ZONES BY FACILITY ID
			$options = $standard->get_zonebyfac($_SESSION['facility_select']);
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
			}	?>
			</select>
			<input type="hidden" name="addtemplate" value="1">
			<input type="submit" name="submit" value="Send"></td></tr>
			</div>
			<?php
			// THIS IS FOR EDITING AN EXISTING TEMPLATE
			} else {
			$datas = $standard->get_template($id, $_SESSION['facility_select']);
			echo "<div class=\"formula\"><h1>Edit template</h1><form action=\"time_template.php\" method=\"post\">";
			foreach ($datas as $data) {

				echo "<label for=\"fac_id\">Facility</label> <select id=\"fac_id\" name=\"fac_id\">";
				$options = $standard->get_facility("all");
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\"";
				if ($option['id'] == $data['fac_id']){
				echo "selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select>";
			echo "<label for=\"zone_id\">Zone</label> <select id=\"zone_id\" name=\"zone_id\">";
				$options = $standard->get_zonebyfac($data['fac_id']);
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\"";
				if ($option['id'] == $data['zone_id']){
				echo "selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select>";
			echo "<input type=\"hidden\" name=\"user_id\" value=\"".$_SESSION['name']."\">";
			echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
			echo "<label for=\"name\">Template name</label><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
			echo "<label for=\"description\">Description</label><input type=\"text\" name=\"description\" value=\"".$data['description']."\" required>";
			echo "<input type=\"hidden\" name=\"edit\" value=\"1\"><input type=\"submit\" name=\"submit\" value=\"Send\"></form>";
		}echo "</div>";
			}
		}

	// ADD NEW TEMPLATE
	public function add_template($user_id, $zone_id, $fac_id, $name, $description){
				echo $this->addTemplate($user_id, $zone_id, $fac_id, $name, $description);
		}
	// EDIT MAIN FEATURES OF TEMPLATE
	public function edit_template($id, $user_id, $zone_id, $fac_id, $name, $description){
			echo $this->editTemplate($id, $user_id, $zone_id, $fac_id, $name, $description);
			}

	// ADD NEW TIME SEGMENT TO TEMPLATE
	public function add_time_template($temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val){
			$segments = $this->getTempCont($temp_id);
			// MAKE SURE TIME IS RIGHT SET
			$time_a = strtotime($from_time); $time_b = strtotime($to_time);
			if ($time_a > $time_b){
				echo "Error in time settings, Start time can't be higher than End time.";
				return;
			}
			// WE WANT TO MAKE SURE THAT NO SEGMENT WITH SPECIFIC ID IS CROSSING IN TIME
			$this_to_day = $from_day + $days;
			foreach ($segments as $seg) {
				// GET SAME IOT_ID (DEVICE) AS THE CURRENT ONE
				if ($seg['iot_id'] == $iot_id){
					$seg_to_day = $seg['from_day'] + $seg['days'];
					if (($from_day >= $seg['from_day'] && $from_day < $seg_to_day) || ($this_to_day > $seg['from_day'] && $this_to_day <= $seg_to_day) || ($from_day <= $seg['from_day'] && $this_to_day >= $seg_to_day)){
						// BITWISE OPERATORS "AND" TO SEE IF THERE ARE ANY CLASHES WITH WEEK DAYS, DON'T MIND THE LAST BIT (128), THAT IS NOT A WEEKDAY
						if (($total & $seg['daysofweek']) !== 128 && ($total & $seg['daysofweek']) == true){
							// NOW FOR ASSURING THAT NO TIME IS CONFLICTING
							$time_seg_a = strtotime($seg['from_time']); $time_seg_b = strtotime($seg['to_time']);
							if (($time_a > $time_seg_a && $time_a < $time_seg_b) || ($time_b > $time_seg_a && $time_b < $time_seg_b) || ($time_a <= $time_seg_a && $time_b >= $time_seg_b)){
								echo "Error on dates or time, there are conflicts!";
								return;
							}
						}
					}
				}
			}
			echo $this->addTimeTemplate($temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val);
		}

	// EDIT EXISTING TIME SEGMENT IN TEMPLATE
	public function edit_time_template($id, $temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val){
			$segments = $this->getTempCont($temp_id);
			// MAKE SURE TIME IS RIGHT SET
			$time_a = strtotime($from_time); $time_b = strtotime($to_time);
			if ($time_a > $time_b){
				echo "Error in time settings, Start time can't be higher than End time.";
				return;
			}
			// WE WANT TO MAKE SURE THAT NO SEGMENT WITH SPECIFIC ID IS CROSSING IN TIME
			$this_to_day = $from_day + $days;
			foreach ($segments as $seg) {
				// GET SAME IOT_ID (DEVICE) AS THE CURRENT ONE
				if ($seg['iot_id'] == $iot_id && $seg['id'] !== $id){
					$seg_to_day = $seg['from_day'] + $seg['days'];
					if (($from_day >= $seg['from_day'] && $from_day < $seg_to_day) || ($this_to_day > $seg['from_day'] && $this_to_day <= $seg_to_day) || ($from_day <= $seg['from_day'] && $this_to_day >= $seg_to_day)){
						// BITWISE OPERATORS "AND" TO SEE IF THERE ARE ANY CLASHES WITH WEEK DAYS, DON'T MIND THE LAST BIT (128), THAT IS NOT A WEEKDAY
						if (($total & $seg['daysofweek']) !== 128 && ($total & $seg['daysofweek']) == true){
							// NOW FOR ASSURING THAT NO TIME IS CONFLICTING
							$time_seg_a = strtotime($seg['from_time']); $time_seg_b = strtotime($seg['to_time']);
							if (($time_a > $time_seg_a && $time_a < $time_seg_b) || ($time_b > $time_seg_a && $time_b < $time_seg_b) || ($time_a <= $time_seg_a && $time_b >= $time_seg_b)){
								echo "Error on dates or time, there are conflicts!";
								return;
							}
						}
					}
				}
			}
				echo $this->editTimeTemplate($id, $temp_id, $iot_types, $iot_id, $description, $from_day, $days, $total, $from_time, $to_time, $set_val);
		}
	// DELETE TIME SEGMENT IN TEMPLATE
	public function delete_time_template($id, $temp_id, $description){
				echo $this->deleteTimeTemplate($id, $temp_id, $description);
		}







}




?>
