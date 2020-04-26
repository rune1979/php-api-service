<?php

class Rfid extends Dbc{


	protected function getZone($id){
		$sql = "SELECT * FROM zone WHERE id = '$id'";
		$result = mysqli_query($this->dbConnect(), $sql);
		//$data = array();
		$data = mysqli_fetch_assoc($result);
		return $data;
		}


	protected function getAllZones(){
		$sql = "SELECT * FROM zone ORDER BY id DESC";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}


	protected function getAllRfid(){
		$sql = "SELECT * FROM locks ORDER BY id DESC";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}
	protected function getRfid($id){
		$sql = "SELECT * FROM locks WHERE id='$id'";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
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


	protected function insertRfid($name, $description, $zone){
		$empQuery="INSERT INTO locks SET name='$name', description='$description', zone='$zone'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Ny RFID: ".$name." Beskrivelse ".$description." knyttet til Zone: ".$zone."";
		} else {
			$messgae = "RFID oprettelse fejlede";
		}
		echo $messgae;
		$this->insertLog("0",$name,"0","0","0",$messgae);
	}

	protected function updateRfid($id, $name, $description, $zone){
		$empQuery="UPDATE locks SET name='$name', description='$description', zone='$zone' WHERE id='$id'";
		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$messgae = "Opdateret RFID: ".$name." Beskrivelse ".$description." til Zone: ".$zone."";

		} else {
			$messgae = "Fejl i opdatering af RFID: ".$name." Beskrivelse ".$description." til Zone: ".$zone."";

		}
			echo $messgae;
			$this->insertLog("0",$id,"0","0","0",$messgae);

	}


}



class ViewRfid extends Rfid{


	public function printAddRfidForm($id){
			if ($id == "non"){
			?><table><tr><td>Tilføj RFID: </td>
			<td style="text-align:right" colspan="1"><?=$sub_links?></td></tr>
			<tr><td><form action="rfid.php" method="post">
			RFID enhedsnavn: </td><td><input type="text" name="name" value="" required></td></tr>
			<tr><td> Beskrivelse: </td><td><input type="text" name="description" value=""></td></tr>
			<tr><td> Tilhørende zone: </td><td><select name="zone">
			<?php
			$options = $this->getAllZones();
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
			}
			?>
			</select></td></tr><br>
			<tr><td></td><td><input type="hidden" name="addrfid" value="1"><input type="submit" name="submit" value="Send"></form></td></tr></table>
			<?php
			} else {
			$datas = $this->getRfid($id);
			echo "<table><tr><td>Rediger RFID: <form action=\"rfid.php\" method=\"post\"></td>";
				foreach ($datas as $data) {
				echo "RFID enhedsnavn: </td><td><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required></td></tr>";
				echo "<tr><td>Beskrivelse: </td><td><input type=\"text\" name=\"description\" value=\"".$data['description']."\" required></td></tr>";
				echo "<tr><td>Tilhørende Zone:</td><td><select name=\"zone\">";
				$options = $this->getAllZones();
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\"";
				if ($option['id'] == $data['zone']){
				echo "selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select></td></tr>";
				echo "<tr><td><input type=\"hidden\" name=\"id\" value=\"".$data['id']."\"></td>";
				echo "<td><input type=\"hidden\" name=\"addrfid\" value=\"2\"><input type=\"submit\" name=\"submit\"value=\"Send\"></form></td></tr>";
				}
				echo "</table>";


			}
			}

	public function addNewRfid($name, $description, $zone){
			echo $this->insertRfid($name, $description, $zone);
			}

	public function updateRfidView($id, $name, $description, $zone){
			echo $this->updateRfid($id, $name, $description, $zone);
			}


	public function showAllRfid(){
			$datas = $this->getAllRfid();

			echo "<tr><th>Device id</th>";
			echo "<th>Device Name</th>";
			echo "<th>Description</th>";
			echo "<th>Zone</th>";
			echo "<th>Actions</th></tr>";
			foreach ($datas as $data) {
				$zone = $this->getZone($data['zone']);
				echo "<tr><td>".$data['id']."</td>";
				echo "<td>".$data['name']."</td>";
				echo "<td>".$data['description']."</td>";
				echo "<td>".$data['zone']." - ".$zone['name']."</td>";

				echo "<td><a href=\"rfid.php?page=rediger&id=".$data['id']."\">Edit</a></td></tr>";
				}
		}



}




?>
