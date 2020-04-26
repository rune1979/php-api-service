<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";

// Start session
session_start();
// Redirect hvis bruger ikke er logget ind....
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

// Include class file for facilities
include "inc/class_facility.php";

// Variabler til header og footer
$sub_title = "Facilities";
$sub_sub_title = "- Overview";
$sub_links = "<a href=\"facility.php?page=tilfoj\">Add New</a>";

// Inkluder Header
include("../template/header.php");
//Object
$facility = new ViewFacility();

// If there is any post/get commands
if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {
		//Add empty form
		echo $facility->printAddFacilityForm("non");

	}

	?> <div class="notion"> <?php

	// If NEW facility is added
	if($_POST["add_facility"] == "1") {
		echo $facility->addNewFacility($_POST['name'], $_POST['address'], $_POST['country'], $_POST['description'], $_POST['time_zone'], $_POST['geo'], $_POST['sec_string']);

	}
	// If there are updates to a facility
	if($_POST["add_facility"] == "2") {
		echo $facility->updateNewFacility($_POST['id'], $_POST['name'], $_POST['address'], $_POST['country'], $_POST['description'], $_POST['time_zone'], $_POST['geo'], $_POST['sec_string']);

	}
	if($_GET["page"] == "slet") {
			// We don't want any one to be able to delete yet...
	}

	?> </div> <?php

	if($_GET["page"] == "rediger") {
		echo $facility->printAddFacilityForm($_GET['id']);
	}
} else {
	?>
	<div class="listing">
	<table width="100%">
	<?php
		echo $facility->showAllFacilities();
	?>
	</table>
	</div>

	<?php
}
// Inkluder Footer her
include("../template/footer.php");
?>
