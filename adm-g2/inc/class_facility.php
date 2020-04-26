<?php

class Facility extends Dbc{

	//GET Facilities
	protected function getFacility($id){
		if($id == "all"){
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


	protected function updateFacility($id, $name, $address, $country, $description, $time_zone, $geo, $sec_s){
		$sql = "UPDATE facility SET name='$name', address='$address', country='$country', description='$description', time_zone='$time_zone', geo='$geo', hash='$sec_s' WHERE id='$id'";
		if (mysqli_query($this->dbConnect(), $sql)) {
     			echo "Update successfull!";
   		} else {
      			echo "Error during update: " . mysqli_error($this->dbConnect());
   		}

		}

	protected function insertLog($user_id, $lock_id, $alert, $temp, $moist, $action){
		$empQuery="INSERT INTO log SET user_id='$user_id', lock_id='$lock_id', alert='$alert', temp='$temp', moist='$moist', action='$action'";
		mysqli_query($this->dbConnect(), $empQuery);
		}


	protected function deleteFacility($id){
		$sql = "DELETE FROM facility WHERE id = '$id'";
		$result = mysqli_query($this->dbConnect(), $sql);
		if (mysqli_query($this->dbConnect(), $sql)) {
     			echo "Successfully deleted!";
   		} else {
      			echo "Error could not delete: " . mysqli_error($this->dbConnect());
   		}

		}

	protected function insertFacility($name, $address, $country, $description, $time_zone, $geo, $sec_s){
		$empQuery="INSERT INTO facility SET name='$name', address='$address', country='$country', description='$description', time_zone='$time_zone', geo='$geo', hash='$sec_s'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			echo "Facility Added!";
		} else {
			echo "Error in adding Facility.";
		}

	}




}



class ViewFacility extends Facility{

	public function byte_randomizer(){
		// Cryptographically Secure randomizer
		$n = 20;
		$result = bin2hex(random_bytes($n));
		return $result;
	}

	//Form for adding or editing faciliies
	public function printAddFacilityForm($facility_id){
			// GET TIMEZONES
			$timezone_identifiers = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
			$number_of_timezones = 425;
			if ($facility_id == "non") {
			// GET CRYPTO STRING
			$crypto_sec = $this->byte_randomizer();
			?>
			<div class="formula"><h1>New Facility</h1>
			<form action="facility.php" method="post">
			<label for="name">Name:*</label><input type="text" name="name" value="Name" required><br>
			<label>Address:</label><input type="text" name="address" value="Some address">
			<label>Country:</label><input type="text" name="country" value="Denmark">
			<label>Description:</label><input type="text" name="description" value="Give it a clear description">
			<label for="time_zone">Time Zone</label><select name="time_zone">
			<option disabled selected>Please Select Timezone</option>
			<?
			for($i = 0; $i < $number_of_timezones; $i++) {
    	// Print the timezone identifiers
    		echo "<option value='" . $timezone_identifiers[$i] . "'>" . $timezone_identifiers[$i] . "</option>";
			}
			?>
			</select>
			<label>GEO data:</label><input type="text" name="geo" value="GEO Location">
			<label>Safety string:</label><input type="text" name="sec_string" value="<?php echo $crypto_sec;?>" readonly>
			<input type="hidden" name="add_facility" value="1"><input type="submit" name="submit" value="Send"></form></div>
			<?php
			} else {
			$datas = $this->getFacility($facility_id);
			echo "<div class=\"formula\"><h1>Edit Facility</h1><form action=\"facility.php\" method=\"post\">";
			foreach ($datas as $data) {
				echo "<label>Name</label><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
				echo "<label>Address</label><input type=\"text\" name=\"address\" value=\"".$data['address']."\">";
				echo "<label>Country</label><input type=\"text\" name=\"country\" value=\"".$data['country']."\">";
				echo "<label>Description</label><input type=\"text\" name=\"description\" value=\"".$data['description']."\">";
				echo "<label>Time Zone</label><select name=\"time_zone\">";
				for($i = 0; $i < $number_of_timezones; $i++) {
	    	// Print the timezone identifiers
	    		echo "<option value='" . $timezone_identifiers[$i] . "'";
					if ($data['time_zone'] == $timezone_identifiers[$i]){
						echo " selected";
					}
					echo ">" . $timezone_identifiers[$i] . "</option>";
				}
				echo "</select>";
				echo "<label>GEO location</label><input type=\"text\" name=\"geo\" value=\"".$data['geo']."\">";
				echo "<p align=\"center\">WARNING! Changing Security String below may result in communication shutdown for your facility!</p>";
				echo "<button type=\"button\" name=\"change_sec\" id=\"change_sec\" value=\"1\"><b>Change Sec. hash</b></button><input type=\"text\" name=\"sec_string\" id=\"sec_string\" value=\"".$data['hash']."\" readonly>";
				echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";

				echo "<input type=\"hidden\" name=\"add_facility\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Send\"></form></div>";
				}
			}
			}


	public function addNewFacility($name, $address, $country, $description, $time_zone, $geo, $sec_s){
			echo $this->insertFacility($name, $address, $country, $description, $time_zone, $geo, $sec_s);
			$action = "New Facility ".$name." - ".$description." added!";
			$this->insertLog($name,"0","0","0","0",$action);
			}

	public function updateNewFacility($id, $name, $address, $country, $description, $time_zone, $geo, $sec_s){
			echo $this->updateFacility($id, $name, $address, $country, $description, $time_zone, $geo, $sec_s);
			$action = "Facility Id: ".$id.", Name:".$name." Updated!";
			$this->insertLog($id,$id,"0","0","0",$action);
			}

	public function showAllFacilities(){
			$datas = $this->getFacility("all");
			echo "<tr><th>Id</th>";
			echo "<th>Facility</th>";
			echo "<th>Address</th>";
			echo "<th>Country</th>";
			echo "<th>Security String</th>";
			echo "<th>Time Zone</th>";
			echo "<th>Action</th></tr>";
			foreach ($datas as $data) {
				echo "<tr title=\"".$data['description']."\"><td> ".$data['id']."</td>";
				echo "<td><b>".$data['name']."</b></td>";
				echo "<td>".$data['address']."</td>";
				echo "<td>".$data['country']."</td>";
				echo "<td>".$data['hash']."</td>";
				echo "<td>".$data['time_zone']."</td>";
				echo "<td><a href=\"facility.php?page=rediger&id=".$data['id']."\">Edit</a></td></tr>";
				}
		}





}




?>
