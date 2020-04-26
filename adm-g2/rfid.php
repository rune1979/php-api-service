<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";

$sub_title = "IoT";
$sub_sub_title = "- Overview";
$sub_links = "<a href=\"rfid.php?page=tilfoj\">Add New IoT</a>";
include("../template/header.php");
// Start session


include "inc/class_rfid.php";

// Variabler til header og footer


// Inkluder Header

//Object
$zones = new ViewRfid();

if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {

		echo $zones->printAddRfidForm("non");

	}

	if($_POST["addrfid"] == "1") {
		echo $zones->addNewRfid($_POST['name'], $_POST['description'], $_POST['zone']);

	}
	if($_POST["addrfid"] == "2") {
		echo $zones->updateRfidView($_POST['id'], $_POST['name'], $_POST['description'], $_POST['zone']);

	}
	if($_GET["page"] == "slet") {
		echo $zones->deleteRfidView($_POST['name'], $_POST['description'], $_POST['zone']);
	}
	if($_GET["page"] == "rediger") {
		echo $zones->printAddRfidForm($_GET["id"]);
	}
} else {
	?>

	<div class="listing">
	<table width="100%">
	<?php
		echo $zones->showAllRfid();
	?>
	</table>
</div>

	<?php
}
// Inkluder Footer her
include("../template/footer.php");
?>
