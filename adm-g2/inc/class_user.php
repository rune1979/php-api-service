<?php

class Users extends Dbc{


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


	protected function getRoles(){
		$sql = "SELECT * FROM roles ORDER BY id DESC";
		$result = mysqli_query($this->dbConnect(), $sql);
		$data = array();
		while( $empRecord = mysqli_fetch_assoc($result) ) {
			$data[] = $empRecord;
			}
			return $data;
		}
	protected function getRole($id){
		$sql = "SELECT * FROM roles WHERE id='$id'";
		$result = mysqli_query($this->dbConnect(), $sql);
		//$data = array();
		if( $empRecord = mysqli_fetch_assoc($result) ) {
			$data = $empRecord;
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

	protected function insertUser($name, $cpr, $role, $rfid, $passd, $user, $passwd){
		$empQuery="INSERT INTO emp SET name='$name', cpr='$cpr', role='$role', rfid='$rfid', passd='$passd', user='$user', passwd='$passwd'";

		if(mysqli_query($this->dbConnect(), $empQuery)) {
			$message = "Employee created Successfully.";
			$status = 1;
		} else {
			$message = "Employee creation failed.";
			$status = 0;
		}
		return $message;
	}



	protected function insertLog($user_id, $lock_id, $alert, $temp, $moist, $action){
		$empQuery="INSERT INTO log SET user_id='$user_id', lock_id='$lock_id', alert='$alert', temp='$temp', moist='$moist', action='$action'";
		mysqli_query($this->dbConnect(), $empQuery);
		}

	protected function updateUser($id,$name,$cpr,$role,$rfid,$passd,$user,$passwd){
		$sql = "UPDATE emp SET name='$name', cpr='$cpr', role='$role', rfid='$rfid', passd='$passd', user='$user', passwd='$passwd' WHERE id='$id'";
		if (mysqli_query($this->dbConnect(), $sql)) {
     			$msg = "Updated user with Success!";
   		} else {
      			$msg = "Error in user update: " . mysqli_error($this->dbConnect());
   		}
		echo $msg;
		$this->insertLog($id,"0","User update","0","0",$msg);

		}
	protected function deleteUser($id){
		$sql = "DELETE FROM emp WHERE id='$id'";
		if (mysqli_query($this->dbConnect(), $sql)) {
     			$msg = "User Deleted";
   		} else {
      			$msg = "Error in user deletion: " . mysqli_error($this->dbConnect());
   		}
		echo $msg;
		$this->insertLog($id,"0","USER DELETED","0","0",$msg);

		}




}



class ViewUsers extends Users{

	public function printUserProfile($id){
			$datas = $this->getUser($id);
			//return $datas;
			echo "<table>";
			foreach ($datas as $data) {
				echo "<tr><td>Userid: </td><td> ".$data['id']."</td></tr>";
				echo "<tr><td>Navn: </td><td>".$data['name']."</td></tr>";
				echo "<tr><td>Username: </td><td>".$data['user']."</td></tr>";
				echo "<tr><td>Role: </td><td>".$data['role']."</td></tr>";
				echo "<tr><td>Medarbejder ID: </td><td>".$data['cpr']."</td></tr>";
				echo "<tr><td>RFID: </td><td>".$data['rfid']."</td></tr>";
				echo "<tr><td></td><td><a href=\"users.php?page=rediger&id=".$data['id']."\">Rediger</a></td></tr>";
				}echo "</table>";
		}

	public function showAllUsers(){
			$datas = $this->getUser("all");
			echo "<tr><th>Userid</th>";
			echo "<th>Name</th>";
			echo "<th>Username</th>";
			echo "<th>Role</th>";
			echo "<th>Emp ID</th>";
			echo "<th>Actions</th>";
			echo "<th>Access priv.</th></tr>";
			foreach ($datas as $data) {
				$roling = $this->getRole($data['role']);
				echo "<tr><td> ".$data['id']."</td>";
				echo "<td>".$data['name']."</td>";
				echo "<td>".$data['user']."</td>";
				echo "<td>".$roling['navn']."</td>";
				echo "<td>".$data['cpr']."</td>";
				echo "<td><a href=\"users.php?page=rediger&id=".$data['id']."\">Edit</a> - ";
				echo "<a href=\"users.php?page=slet&id=".$data['id']."\" onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a></td>";
				echo "<td><a href=\"relation.php?page=access&id=".$data['id']."\">Zone Access</a></td></tr>";
				}
		}



	public function printAddUserForm($user_id){
			if ($user_id == "non") {	?>
			<div class="formula"><h1>Add Person</h1>
			<form name="user" action="" onsubmit="return validateUserForm()" method="post">
			<label for="name">Name:*</label><input type="text" name="name" value="" required>
			<label for="rfid">RFID:</label><input type="text" name="rfid" value="">
			<label for="user">Username:</label><input type="text" name="user" value="">
			<label for="cpr">Employee ID:</label><input type="text" name="cpr" value="">
			<label for="role">Role:</label><select name="role" type="select">
			<?php
			$options = $this->getRoles();
			foreach ($options as $option) {
			echo "<option value=\"".$option['id']."\">".$option['navn']."</option>";
			}	?>
		</select>

			<label for="role" title="Does the user need web access?">Web Access:</label><input type="checkbox" name="cb" checked title="Does the user need web access?">

			<label for="passwd">Web password:</label><input type="text" name="passwd" value="">

			<label for="passd" title="The password digits the user have to type to get access">Door password:</label><input type="text" name="passd" value="" title="The password digits the user have to type to get access">
			<input type="hidden" name="adduser" value="1">
			<input type="submit" name="submit" value="Send">
		  </div>
			<?php } else {
			$datas = $this->getUser($user_id);
			echo "<div class=\"formula\"><h1>Edit User</h1><form action=\"\" method=\"post\">";
			foreach ($datas as $data) {
				echo "<label for=\"name\">Name:*</label><input type=\"text\" name=\"name\" value=\"".$data['name']."\" required>";
				echo "<label for=\"rfid\">RFID:</label><input type=\"text\" name=\"rfid\" value=\"".$data['rfid']."\">";
				echo "<label for=\"user\">Username:</label><input type=\"text\" name=\"user\" value=\"".$data['user']."\">";
				echo "<label for=\"cpr\">Employee ID:</label><input type=\"text\" name=\"cpr\" value=\"".$data['cpr']."\">";
				echo "<label for=\"role\">Role:</label><select type=\"select\" name=\"role\">";
					$options = $this->getRoles();
					foreach ($options as $option){
					echo "<option value=\"".$option['id']."\"";
					if ($option['id'] == $data['role']) {
					echo " selected";
					}
					echo ">".$option['navn']."</option>";
					}
				echo "</select></br></br>";
				echo "</br><label for=\"passwd\">Web password:</label><input type=\"password\" name=\"passwd\" value=\"".$data['passwd']."\" required>";
				echo "<label for=\"passd\">Door password:</label><input type=\"text\" name=\"passd\" value=\"".$data['passd']."\" required>";
				echo "<input type=\"hidden\" name=\"id\" value=\"".$data['id']."\">";
				echo "<input type=\"hidden\" name=\"adduser\" value=\"2\"><input type=\"submit\" name=\"submit\" value=\"Send\"></form>";
			}echo "</div>";
			}
		}

	public function addNewUser($name, $cpr, $role, $rfid, $passd, $user, $passwd){
			$check_user = $this->checkUser($user);
			$passwd_enc = password_hash($passwd, PASSWORD_DEFAULT);
			$rfid_enc = password_hash($rfid, PASSWORD_DEFAULT);
			if ($check_user == '1'){
				$action = "Bruger eksistere allerede - Kan ikke oprettes!";
				echo $action;
				$this->insertLog($user,"0","0","0","0",$action);
			} else {
				echo $this->insertUser($name, $cpr, $role, $rfid_enc, $passd, $user, $passwd_enc);
				$action = "Bruger ".$user." oprettet!";
				echo $action;
				$this->insertLog($user,"0","0","0","0",$action);
			}

		}

	public function deleteUserView($id){
			echo $this->deleteUser($id);
			}

	public function updateUserView($id, $name, $cpr, $role, $rfid, $passd, $user, $passwd){
			if (strlen($passwd) < 20){
				$passwd = password_hash($passwd, PASSWORD_DEFAULT);
						}
			if (strlen($rfid) < 20){
			$rfid = password_hash($rfid, PASSWORD_DEFAULT);}
			echo $this->updateUser($id,$name,$cpr,$role,$rfid,$passd,$user,$passwd);
			}




}




?>
