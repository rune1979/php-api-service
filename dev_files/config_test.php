<?php


class Dbc{
	private $servername = "localhost";
	private $username = "remote_produce";
	private $password = "2clever";
	private $dbname = "remote_produce";

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
