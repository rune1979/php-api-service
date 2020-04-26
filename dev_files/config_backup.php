<?php


class Dbc{
	private $servername = "localhost";
	private $username = "db_user_to_change";
	private $password = "pass_to_change";
	private $dbname = "db_name_to_change";

	protected function dbConnect(){
		$conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if($conn->connect_error){
                echo "Error failed to connect to MySQL: " . $conn->connect_error;
            }else{
                return $conn;
            }

	}
	protected function prpConnect(){
		$dsn = 'mysql:host=' . $this->servername . ';dbname=' . $this->dbname;
		$pdo = new PDO($dsn, $this->username, $this->password);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $pdo;
	}
}

//URL Website
$url = "https://website.com";
//Side titel
$title = "COMPANY";
//Footer tekst
$ejerskab = "All rights reserved - ".$title;



?>
