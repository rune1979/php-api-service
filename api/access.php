<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include("../inc/config.php");
include("../inc/Rest.php");
$api = new Rest();
switch($requestMethod) {
	case 'GET':
		$empId = '';	
		if($_GET['rfid']) {
			$rfid = $_GET['rfid'];
			$unit_id = $_GET['unit_id'];
			$api->getAccessNew($rfid, $unit_id);
		} else {
    			//echo "Have a good night!";
			header("HTTP/1.0 405 Method Not Allowed");
			}
		break;
	default:
	header("HTTP/1.0 405 Method Not Allowed");
	break;
}
?>
