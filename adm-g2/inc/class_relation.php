<?php

class User extends Dbc{


	protected function getUser($id){
		if($id == "all"){
		$sql = "SELECT * FROM emp ORDER BY id DESC";
		} else {
		$sql = "SELECT * FROM emp WHERE id = '$id'";
		}
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}

	protected function getZone($id){
		if($id == "all"){
		$sql = "SELECT * FROM zone ORDER BY id DESC";
		} else {
		$sql = "SELECT * FROM zone WHERE id = '$id'";
		}
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}

	protected function getRelation($id){
		if($id == "all"){
		$sql = "SELECT * FROM access_relation ORDER BY id DESC";
		} else {
		$sql = "SELECT * FROM access_relation WHERE user_id = '$id'";
		}
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}
	protected function getRelationId($id){
		if($id == "all"){
		$sql = "SELECT * FROM access_relation ORDER BY id DESC";
		} else {
		$sql = "SELECT * FROM access_relation WHERE id = '$id'";
		}
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
			return $data;
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

	protected function insertZone($user_id, $zone_id, $from_time, $to_time, $from_date, $to_date){
		if(!empty($from_time)){
		$timing = ", time_from='$from_time', time_to='$to_time'";
		} else {
		$timing = "";
		}
		if(!empty($from_date)){
		$dating = ", date_from='$from_date', date_to='$to_date'";
		} else {
		$dating = "";
		}
		$empQuery="INSERT INTO access_relation SET user_id='$user_id', zone_id='$zone_id' ".$timing."".$dating."";
		mysqli_query($this->dbConnect(), $empQuery);
		}

	protected function insertLog($user_id, $lock_id, $alert, $temp, $moist, $action){
		$empQuery="INSERT INTO log SET user_id='$user_id', lock_id='$lock_id', alert='$alert', temp='$temp', moist='$moist', action='$action'";
		mysqli_query($this->dbConnect(), $empQuery);
		}

	protected function updateRelation($id, $user_id, $zone_id, $time_from, $time_to, $date_from, $date_to){
		if (empty($time_from)){
		$t_ofday = "";
		}else{
		$t_ofday = ", time_from='$time_from', time_to='$time_to'";
		}
		if (empty($date_from)){
		$d_day = "";
		}else{
		$d_day = ", date_from='$date_from', date_to='$date_to'";
		}
		$sql = "UPDATE access_relation SET user_id='$user_id', zone_id='$zone_id' $t_ofday $d_day WHERE id='$id'";
		if (mysqli_query($this->dbConnect(), $sql)) {
     			$msg = "Relation ændret successfuldt";
   		} else {
      			$msg = "Fejl i updatering af relation: " . mysqli_error($this->dbConnect());
   		}
		echo $msg;
		$this->insertLog($user_id,$zone_id,"Relationsændring","0","0",$msg);

		}
}



class ViewRelation extends User{


	public function showRelations($u_id){
			$datas = $this->getRelation($u_id);
			echo "<tr><th>Id</th>";
			echo "<th>User</th>";
			echo "<th>Zone</th>";
			echo "<th>From Time</th>";
			echo "<th>To Time</th>";
			echo "<th>From Date</th>";
			echo "<th>To Date</th>";
			echo "<th>Handling</th></tr>";
			foreach ($datas as $data) {
				echo "<tr><td> ".$data['id']."</td>";
				$user_name = $this->getUser($data['user_id']);
				foreach ($user_name as $un) {
				echo "<td>".$data['user_id']." : ".$un['name']."</td>";
				}
				$zone_name = $this->getZone($data['zone_id']);
				foreach ($zone_name as $zn) {
				echo "<td>".$data['zone_id']." : ".$zn['name']."</td>";
				}
				$time = str_split($data['time_from'],5);
				echo "<td>".$time['0']."</td>";
				$time = str_split($data['time_to'],5);
				echo "<td>".$time['0']."</td>";
				echo "<td>".$data['date_from']."</td>";
				echo "<td>".$data['date_to']."</td>";
				echo "<td><a href=\"relation.php?page=rediger&id=".$data['id']."\">Edit</a> | ";
				echo "<a href=\"relation.php?page=slet&id=".$data['id']."\">Delete</a></td></tr>";
				}
		}



	public function printRelationForm($id){
			if ($id == "non") {	?>
			<table><tr><td>Tilføj Adgang:</td><td></td></tr>
			<tr><td><form action="relation.php" method="post">
			<tr><td>Bruger: </td><td><select name="user_id">
			<?php
			$options = $this->getUser("all");
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
			}	?>
			</select></td></tr>
			<tr><td>Zone: </td><td><select name="zone_id">
			<?php
			$options = $this->getZone("all");
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['name']."</option>";
			}	?>
			</select></td></tr>

			<tr><td> Fra Kl. (ex. 08:00, ellers blank): </td><td><input type="text" name="time_from" value=""></td></tr>
			<tr><td> Til Kl.: </td><td><input type="text" name="time_to" value=""></td></tr>
			<tr><td> Fra dato (ex. 2019-05-01, eller blank): </td><td><input type="text" name="date_from" value=""></td></tr>
			<tr><td> Til dato: </td><td><input type="text" name="date_to" value=""></td></tr>
			<tr><td><input type="hidden" name="addrel" value="1"></td><td><input type="submit" name="submit" value="Send"></td></tr>
			</table>
			<?php } else {
			$datas = $this->getRelationId($id);
			echo "<table><tr><td>Rediger Adgang: <form action=\"relation.php\" method=\"post\"></td>";
			foreach ($datas as $data) {
				echo "<tr><td>Bruger:</td><td><select name=\"user_id\">";
				$options = $this->getUser("all");
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\"";
				if ($option['id'] == $data['user_id']){
				echo "selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select></td></tr>";
			echo "<tr><td>Adgang til Zone:</td><td><select name=\"zone_id\">";
				$options = $this->getZone("all");
				foreach ($options as $option) {
				echo "<option value=\"".$option['id']."\"";
				if ($option['id'] == $data['zone_id']){
				echo "selected";
				}
				echo ">".$option['name']."</option>";
				}
			echo "</select></td></tr>";
				$time = str_split($data['time_from'],5);
				echo "<tr><td>Fra kl.: </td><td><input type=\"text\" name=\"time_from\" value=\"".$time['0']."\"></td></tr>";
				$time = str_split($data['time_to'],5);
				echo "<tr><td>Til kl.: </td><td><input type=\"text\" name=\"time_to\" value=\"".$time['0']."\"></td></tr>";
				echo "<tr><td>Fra Dato: </td><td><input type=\"text\" name=\"date_from\" value=\"".$data['date_from']."\"></td></tr>";
				echo "<tr><td>Til Dato: </td><td><input type=\"text\" name=\"date_to\" value=\"".$data['date_to']."\"></td></tr>";
				echo "<tr><td><input type=\"hidden\" name=\"id\" value=\"".$data['id']."\"></td>";
				echo "<td><input type=\"hidden\" name=\"addrel\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Send\"></form></td></tr>";
				}echo "</table>";
			}
		}

	public function addNewRelation($user_id, $zone_id, $from_time, $to_time, $from_date, $to_date){
				echo $this->insertZone($user_id, $zone_id, $from_time, $to_time, $from_date, $to_date);
				$action = "Relation mellem Bruger: ".$user_id." og Zone: ".$zone_id." oprettet!";
				$this->insertLog($user_id,$zone_id,"0","0","0",$action);


		}

	public function deleteRelationView($id){
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
	public function updateRelationView($id, $user_id, $zone_id, $time_from, $time_to, $date_from, $date_to){
			echo $this->updateRelation($id, $user_id, $zone_id, $time_from, $time_to, $date_from, $date_to);
			}




}




?>
