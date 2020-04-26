<?php

class Zone extends Dbc{

	protected function getZone($id){
		if($id == "all"){
		$sql = "SELECT * FROM zone";
		} else {
		$sql = "SELECT * FROM zone WHERE id = '$id'";
		}
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while($empRecord = mysqli_fetch_assoc($result)){
			$data[] = $empRecord;
			}
		return $data;
		}

		protected function getFacilities($id){
				if($id == "all"){
			$sql = "SELECT * FROM facility";
		} else {
			$sql = "SELECT * FROM facility WHERE id = '$id'";
		}
			$result = mysqli_query($this->dbConnect(), $sql);
			$data = array();
			while($empRecord = mysqli_fetch_assoc($result)) {
				$data[] = $empRecord;
				}
			return $data;
			}

	protected function getZoneType($id){
			if($id == "all"){
				$sql = "SELECT * FROM zone_type";
			} else {
				$sql = "SELECT * FROM zone_type WHERE id = '$id'";
			}
				$result = mysqli_query($this->dbConnect(), $sql);
				$data = array();
				while($empRecord = mysqli_fetch_assoc($result)) {
					$data[] = $empRecord;
					}
				return $data;
				}
		protected function getZoneContent($id){
				if($id == "all"){
					$sql = "SELECT * FROM zone_content";
				} else {
					$sql = "SELECT * FROM zone_content WHERE zone_type_id = '$id'";
				}
					$result = mysqli_query($this->dbConnect(), $sql);
					$data = array();
					while($empRecord = mysqli_fetch_assoc($result)) {
						$data[] = $empRecord;
						}
					return $data;
					}
		protected function getZoneContentId($id){
				if($id == "all"){
					$sql = "SELECT * FROM zone_content";
				} else {
					$sql = "SELECT * FROM zone_content WHERE id = '$id'";
				}
					$result = mysqli_query($this->dbConnect(), $sql);
					$data = array();
					while($empRecord = mysqli_fetch_assoc($result)) {
						$data[] = $empRecord;
						}
					return $data;
					}


	protected function updateContent($id, $name, $latin, $description, $zone_type){
		$sql = "UPDATE zone_content SET name='$name', description='$description', latin='$latin', zone_type_id='$zone_type' WHERE id='$id'";
		if (mysqli_query($this->dbConnect(), $sql)) {
     			echo "Content updated successfully!";
   		} else {
      			echo "Error during update: " . mysqli_error($this->dbConnect());
   		}

		}

	protected function deleteZone($id){
		$sql = "DELETE FROM zone WHERE id = '$id'";
		$result = mysqli_query($this->dbConnect(), $sql);
		if (mysqli_query($this->dbConnect(), $sql)) {
     			echo "Sletning er succesfuld!";
   		} else {
      			echo "Fejl kunne ikke slette: " . mysqli_error($this->dbConnect());
   		}

		}

	protected function insertZone($name, $description, $facility, $zone_type, $zone_content){
		$empQuery="INSERT INTO zone SET name='$name', description='$description', facility_id='$facility', zone_type_id='$zone_type', zone_content_id='$zone_content'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			echo "Zone Created";
		} else {
			echo "Zone could not be created!";
		}

	}
	protected function insertContent($name, $latin, $description, $zone_type){
		$empQuery="INSERT INTO zone_content SET name='$name', description='$description', latin='$latin', zone_type_id='$zone_type'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			echo "Content Created";
		} else {
			echo "Content could not be created!";
		}

	}
	// UPDATE IOT INFO
	protected function update_iots_facility($zone_id, $new_facility){
		global $standard;
		$sql = "UPDATE iot SET facility_id=? WHERE zone_id=?";
		$stmt = $this->prpConnect()->prepare($sql);
		$facility_id = $standard->get_zone($zone_id);
		if ($stmt->execute([$new_facility, $zone_id])){
			$action = "All IoTs connected to zone-id: ".$id." - changed facility to: ".$new_facility." - zone name: ".$facility_id[0]['name']."!";
			// ADDING RECORD TO LOG
			$standard->newInsLog($new_facility,$zone_id,"",$_SESSION['name'],$action);
		} else {
			$action = "Error - in updating IoTs facility_id to new id: ".$new_facility." - zone_id: ".$zone_id." - name: ".$facility_id[0]['name']."!";
			echo $action;
			// ADDING RECORD TO LOG
			$standard->newInsLog($new_facility,$zone_id,"",$_SESSION['name'],$action);
		}
		}

		// UPDATE ZONE
		protected function updateZone($id, $name, $description, $facility, $zone_type, $zone_content){
			global $standard;
			$sql = "UPDATE zone SET name=?, description=?, facility_id=?, zone_type_id=?, zone_content_id=? WHERE id=?";
			$stmt = $this->prpConnect()->prepare($sql);
			if ($stmt->execute([$name, $description, $facility, $zone_type, $zone_content, $id])){
				$action = "Zone: ".$name." has been updated!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($facility,$id,"",$_SESSION['name'],$action);
			} else {
				$action = "Error - Zone: ".$name." did NOT update!";
				echo $action;
				// ADDING RECORD TO LOG
				$standard->newInsLog($facility,$id,"",$_SESSION['name'],$action);
			}
			}



}



class ViewZone extends Zone{

	public function printAddZoneForm($zone_id = "non"){
			if ($zone_id == "non") {?>
			<div class="formula"><h1>New Zone</h1>
			<?=$sub_links?>
			<form action="zone.php" name="add_zone" onsubmit="return validateZoneForm()" method="post">
			<label for="facility">Facility:</label><select name="facility" type="select">
				<?php $options = $this->getFacilities("all");
				echo "<option value=\"0\">Choose a Facility</option>";
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
				}
				?></select>

			<label for="zone_type">Zone Type:</label><select id="zone_type" name="zone_type" type="select">
				<option value="0">- Select Type! -</option>
				<?php
					$types = $this->getZoneType("all");
						foreach ($types as $type) {
								echo "<option value=\"".$type['id']."\">".$type['name']."</option>";
						}?>

			</select>
			<label for="zone_content">Zone Content:</label>
			<select id="zone_content" name="zone_content">
   			<option value="0">- Select Content! -</option>

			</select>

			<label for="name">Name:</label><input type="text" name="name" value="Name" required><br>
			<label>Description: </label><input type="text" name="description" value="Give it a clear description">
			<input type="hidden" name="addzone" value="1"><input type="submit" name="submit" value="Send"></form></div>
			<?php
			} else {
			$datas = $this->getZone($zone_id);
				echo "<div class=\"formula\"><h1>Edit Zone</h1><form action=\"zone.php\" onsubmit=\"return validateZoneForm()\" method=\"post\">";
			foreach ($datas as $data) {
				echo "<label for=\"facility\">Facility:</label><select name=\"facility\" type=\"select\">";
					$options = $this->getFacilities("all");
					foreach ($options as $option) {
						echo "<option value=\"".$option['id']."\"";
						if ($option['id'] == $data['facility_id']){
							echo " selected";
						}
						echo ">".$option['name']."</option>";
					}
				echo "</select>";
				echo "<label for=\"zone_type\">Zone Kind:</label><select id=\"zone_type\" name=\"zone_type\" type=\"select\">";
					$ztypes = $this->getZoneType("all");
					foreach ($ztypes as $ztype) {
					echo "<option value=\"".$ztype['id']."\"";
					if ($ztype['id'] == $data['zone_type_id']){
						echo " selected";
					}
					echo ">".$ztype['name']."</option>";
				}echo "</select>";

				echo "<label for=\"zone_content\">Zone Content:</label><select id=\"zone_content\" name=\"zone_content\" type=\"select\">";
					$zcont = $this->getZoneContent($data['zone_type_id']);
					foreach ($zcont as $zc) {
					echo "<option value=\"".$zc['id']."\"";
					if ($zc['id'] == $data['zone_content_id']){
						echo " selected";
					}
					echo ">".$zc['name']."</option>";
				}echo "</select>";



				echo "<label>Name</label><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
				echo "<label>Description</label><input type=\"text\" name=\"description\" value=\"".$data['description']."\" required>";
				echo "<input type=\"hidden\" name=\"old_fac_id\" value=\"".$data['facility_id']."\">";
				echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
				echo "<input type=\"hidden\" name=\"addzone\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Update\"></form></div>";
				}
			}
			}

			public function printAddContentForm($cont_id = "non"){
					if ($cont_id == "non") {?>
					<div class="formula"><h1>New Content</h1>
					<form action="zone.php" name="add_content" method="post">
					<label for="zone_type">Zone Kind/Type:</label><select name="zone_type" type="select">
						<?php $options = $this->getZoneType("all");
						foreach ($options as $option) {
						echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
						}
						?></select>
					<label for="name">Name:</label><input type="text" name="name" value="Name" required>
					<label for="latin">Latin name:</label><input type="text" name="latin" value="Latin name" required>
					<label>Description: </label><input type="text" name="description" value="Give some description">
					<input type="hidden" name="addcont" value="1"><input type="submit" name="submit" value="Save"></form></div>
					<?php
					} else {
					$datas = $this->getZoneContentId($cont_id);
						echo "<div class=\"formula\"><h1>Edit Content</h1><form action=\"zone.php\" method=\"post\">";
					foreach ($datas as $data) {
						echo "<label for=\"zone_type\">Zone kind:</label><select name=\"zone_type\" type=\"select\">";
							$options = $this->getZoneType("all");
							foreach ($options as $option) {
							echo "<option value=\"".$option['id']."\"";
							if ($option['id'] == $data['zone_type_id']){
								echo " selected";
							}
							echo ">".$option['name']."</option>";
						}echo "</select>";

						echo "<label>Name</label><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
						echo "<label>Latin name</label><input type=\"text\" name=\"latin\" value=\"".$data['latin']."\">";
						echo "<label>Description</label><input type=\"text\" name=\"description\" value=\"".$data['description']."\" required>";

						echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
						echo "<input type=\"hidden\" name=\"addcont\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Update\"></form></div>";
						}
					}
					}


	public function addNewZone($name, $description, $facility, $zone_type, $zone_content){
			global $standard;
			echo $this->insertZone($name, $description, $facility, $zone_type, $zone_content);
			$action = "New Zone ".$name." added - ".$description." to ".$facility."-id";
			$standard->newInsLog($facility,"0","0","0",$action);
			}

	public function updateNewZone($id, $name, $description, $facility, $zone_type, $zone_content, $old_fac_id){
			echo $this->updateZone($id, $name, $description, $facility, $zone_type, $zone_content);
			if ($old_fac_id !== $facility) {
				echo $this->update_iots_facility($id, $facility);
			}
			}

	public function addNewContent($name, $latin, $description, $zone_type){
			global $standard;
			echo $this->insertContent($name, $latin, $description, $zone_type);
			$action = "New Content ".$name." added - ".$description." to ".$zone_type."-id";
			$standard->newInsLog("0","0","0","0",$action);
			}
	public function updateNewContent($id, $name, $latin, $description, $zone_type){
			global $standard;
			echo $this->updateContent($id, $name, $latin, $description, $zone_type);
			$action = "Content Id: ".$id.", Name:".$name." Updated - Description: ".$description."";
			$standard->newInsLog("0","0","0","0",$action);
			}

	public function showZones($zid = "0"){
			global $standard;
			if ($zid == "0"){
				$datas = $standard->get_zonebyfac("all");
			} else {
				$datas = $standard->get_zonebyfac($zid);
			}
			//$datas = $this->getZone("all");
			echo "<tr><th>Zone id</th>";
			echo "<th>Zone</th>";
			echo "<th>Description</th>";
			echo "<th>Facility</th>";
			echo "<th>Kind</th>";
			echo "<th>Content</th>";
			echo "<th>Temp.</th>";
			echo "<th>Humidity</th>";
			echo "<th>Action</th></tr>";
			foreach ($datas as $data) {
				echo "<tr title=\"".$data['description']."\"><td> ".$data['id']."</td>";
				echo "<td><b>".$data['name']."</b></td>";
				echo "<td>".$data['description']."</td>";
				$facilities = $this->getFacilities($data['facility_id']);
				if (empty($facilities)) {
					echo "<td>0</td>";
				}
				foreach ($facilities as $facility) {
				echo "<td>".$facility['name']."</td>";
				}
				$types = $this->getZoneType($data['zone_type_id']);
				if (empty($types)) {
					echo "<td>0</td>";
				} else {
				foreach ($types as $type) {
				echo "<td>".$type['name']."</td>";
				}}
				$contents = $this->getZoneContentId($data['zone_content_id']);
				if (empty($contents)) {
					echo "<td>0</td>";
				} else {
				foreach ($contents as $content) {
				echo "<td>".$content['name']." - ".$content['latin']."</td>";
			}}

				echo "<td><b>".$data['temp']."C</b></td>";
				echo "<td><b>".$data['moist']."%</b></td>";
				echo "<td><a href=\"zone.php?page=rediger&id=".$data['id']."\">Edit</a></td></tr>";
				}
		}
		public function showAllContents(){
				$datas = $this->getZoneContentId("all");
				echo "<tr><th>Id</th>";
				echo "<th>Name</th>";
				echo "<th>Latin name</th>";
				echo "<th>Description</th>";
				echo "<th>Zone Type/Kind</th>";
				echo "<th>Action</th></tr>";
				foreach ($datas as $data) {
					echo "<tr><td> ".$data['id']."</td>";
					echo "<td><b>".$data['name']."</b></td>";
					echo "<td>".$data['latin']."</td>";
					echo "<td>".$data['description']."</td>";
					$types = $this->getZoneType($data['zone_type_id']);
					if (empty($types)) {
						echo "<td>0</td>";
					} else {
					foreach ($types as $type) {
					echo "<td>".$type['name']."</td>";
					}}
					echo "<td><a href=\"zone.php?page=rediger_cont&id=".$data['id']."\">Edit</a></td></tr>";
					}
			}





}




?>
