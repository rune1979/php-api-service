<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";

$sub_title = "Log";
$sub_sub_title = "- Overview";
$sub_links = "";

if (isset($_GET["page"]) || isset($_POST["submit"])) {unset($fac_menu);} else {$fac_menu = "1";}

include("../template/header.php");
// Start session


//include "inc/class.php";

// Variabler til header og footer


// Inkluder Header

//Object
$log = new ViewBasic();

if(isset($_GET["page"])) {
	if($_GET["page"] == "temp") {

		echo $log->printAddZoneForm();

	}

	if($_POST["addzone"] == "1") {
		echo $log->addNewZone($_POST['name'], $_POST['description']);

	}
	if($_GET["page"] == "slet") {

	}
	if($_GET["page"] == "rediger") {

	}
} else {
	?>

	<div class="listing">
	<table width="100%">
	<?php
		echo $standard->showAllLogs($_SESSION["facility_select"]);
	?>
	</table>
	</div>
	<?php
}
// Inkluder Footer her
include("../template/footer.php");
?>
