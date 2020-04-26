<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "inc/class.php";
session_start();
// Redirect hvis bruger ikke er logget ind....
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['name'])) {
	header('Location: index.html');
	exit();
}
$standard = new ViewBasic();
?>

<?php
if(isset($_POST['submitfacility'])){ //check if form was submitted
	$selected_facility = $_POST['facility'];
	$_SESSION["facility_select"] = $_POST['facility'];
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<link href="<?php echo $url; ?>/template/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!--	<script src="http://code.jquery.com/jquery-latest.js"></script>
		 Her kan man se alle ikoner man kan benytte: https://fontawesome.com/icons?d=gallery -->
		<script type="text/javascript" src="js/scripts.js"></script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><img id="logo" src="<?php echo $url; ?>/template/logo.png" onerror="this.onerror=null; this.alt='<?php echo $title; ?>'" width="192" height="47"></h1>
				<a href="administration.php"><i class="fas fa-home"></i>Home</a>
				<a href="facility.php"><i class="fas fa-igloo"></i>Facility</a>
				<a href="zone.php"><i class="fas fa-grip-horizontal"></i>Zone</a>
				<a href="iot.php"><i class="fas fa-fingerprint"></i>IoT</a>
				<a href="time_template.php"><i class="fas fa-copy"></i>Time Temp</a>
				<a href="users.php"><i class="fas fa-user-circle"></i>Users</a>
				<a href="log.php"><i class="fas fa-clipboard-list"></i>Logs</a>
				<a href="profile.php"><i class="fas fa-cog"></i>Settings</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sign out</a>
			</div>
		</nav>

		<div class="head">
			<div class="headblocks"><?=$sub_title?> <?=$sub_sub_title?></div>
			<div class="headblocks"><?php
			if (isset($fac_menu)) {
				echo $standard->getFacilities($_SESSION["facility_select"]);
			}
			?> </div>
			<div class="headblocks" align="right"> <?=$sub_links?> </div>
		</div>
