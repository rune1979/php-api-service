<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";
$sub_title = "Zone";
$sub_sub_title = "- Overview";
$sub_links = "<a href=\"zone.php?page=view_cont\">View all Content</a> | <a href=\"zone.php?page=addcontent\">Add Content</a> | <a href=\"zone.php?page=tilfoj\">Add Zone</a>";

if (isset($_GET["page"]) || isset($_POST["submit"])) {unset($fac_menu);} else {$fac_menu = "1";}
include("../template/header.php");

include "inc/class_zone.php";


// Start session




// Variabler til header og footer


// Inkluder Header

//Object
//$standard = new ViewBasic();
$zones = new ViewZone();

if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {
		echo $zones->printAddZoneForm("non");

	}
	if($_GET["page"] == "addcontent") {
		echo $zones->printAddContentForm("non");

	}

	?> <div class="notion"> <?php

	if($_POST["addzone"] == "1") {
		echo $zones->addNewZone($_POST['name'], $_POST['description'], $_POST['facility'], $_POST['zone_type'], $_POST['zone_content']);

	}
	if($_POST["addzone"] == "2") {
		echo $zones->updateNewZone($_POST['id'], $_POST['name'], $_POST['description'], $_POST['facility'], $_POST['zone_type'], $_POST['zone_content'], $_POST['old_fac_id']);

	}
	if($_POST["addcont"] == "1") {
		echo $zones->addNewContent($_POST['name'], $_POST['latin'], $_POST['description'], $_POST['zone_type']);

	}
	if($_POST["addcont"] == "2") {
		echo $zones->updateNewContent($_POST['id'], $_POST['name'], $_POST['latin'], $_POST['description'], $_POST['zone_type']);

	}

	if($_GET["page"] == "slet") {

	}

	?> </div> <?php

	if($_GET["page"] == "rediger") {
		echo $zones->printAddZoneForm($_GET['id']);
	}
	if($_GET["page"] == "rediger_cont") {
		echo $zones->printAddContentForm($_GET['id']);
	}
	if($_GET["page"] == "view_cont") {
		?>
		<div class="listing">
		<table width="100%">
		<?php
		echo $zones->showAllContents();
		?>
		</table>
		</div>
		<?php
	}
} else {
	?>
	<div class="listing">
	<table width="100%">
	<?php
		echo $zones->showZones($_SESSION["facility_select"]);
	?>
	</table>
	</div>
	<?php
}
// Inkluder Footer her
include("../template/footer.php");
?>
