<?php
//API interface POST requests
$requestMethod = $_SERVER['REQUEST_METHOD'];
include("../inc/config.php");
include("../inc/new_rest.php");

$api = new RestView();

// TAKE IN REQUEST
switch($requestMethod) {
	case 'POST':
		if($_POST['fac_hash']) {
			$local_name = $_POST['local_name'];
			$cur_val = $_POST['cur_val'];
			$fac_hash = $_POST['fac_hash'];
			$api->IoTConnect($local_name, $cur_val, $fac_hash, $_SERVER['REMOTE_ADDR']);
		} else {
    	echo "Have a good night!";
			print_r($_POST);
			echo var_dump($_POST);
			//header("HTTP/1.0 405 Method Not Allowed");
			}
		break;
	default:
	echo "Have a good night! No Case";
	//header("HTTP/1.0 405 Method Not Allowed");
	break;
}
?>
