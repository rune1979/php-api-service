<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";



$sub_title = "IoT";
$sub_sub_title = "- Overview";
$sub_links = "<a href=\"iot.php?page=tilfoj\">Add IoT device</a>";

if (isset($_GET["page"]) || isset($_POST["submit"])) {unset($fac_menu);} else {$fac_menu = "1";}

include("../template/header.php");




include "inc/class_iot.php";
// Variabler til header og footer


// Inkluder Header

//Object
$device = new ViewIoT();

if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {
		echo "<div class=\"notion\">";
		echo $device->printAddIoTForm("non");
		echo "</div>";
	}

	if($_POST['add_iot_type_id'] == 1) {
		echo "<div class=\"notion\">";
		echo $device->addNewIoT($_POST['name'], $_POST['description'], $_POST['iot_type_id'],null, $_POST['zone_id'], $_POST['local_name'], $_POST['acceptable_values']);
		echo "</div>";
		header( "refresh:2;url=iot.php" );
	}
	if($_POST['add_iot_type_id'] == 2) {
		echo "<div class=\"notion\">";
		echo $device->addNewIoT($_POST['name'], $_POST['description'], $_POST['iot_type_id'], $_POST['img_url'], $_POST['zone_id'],$_POST['local_name'],$_POST['acceptable_values']);
		echo "</div>";
		header( "refresh:2;url=iot.php" );
	}

	if($_POST["add_iot"] == "2") {
		echo "<div class=\"notion\">";
		echo $device->updateIoT($_POST['id'], $_POST['name'], $_POST['description'], $_POST['img_url'], $_POST['zone'],$_POST['local_name'], $_POST['acceptable_values']);
		echo "</div>";
		header( "refresh:2;url=iot.php" );
	}
	if($_POST["add_iot"] == "1") {
		echo "<div class=\"notion\">";
		echo $device->updateIoTSettings($_POST['id'], $_POST['set_val_forced'],$_POST['set_val_once'], $_POST['set_val'], $_POST['alert_type'], $_POST['max_alert'],$_POST['min_alert'],$_POST['equal_alert'],$_POST['not_equal_alert'],$_POST['zone_id']);
		echo "</div>";
		header( "refresh:2;url=iot.php" );
	}

	if($_GET["page"] == "delete") {
		echo "<div class=\"notion\">";
		echo $device->deleteIoTView($_GET['id']);
		echo "</div>";
		header( "refresh:2;url=iot.php" );
	}

	if($_GET["page"] == "rediger") {
		echo "<div class=\"notion\">";
		echo $device->printAddIoTForm($_GET["id"]);
		echo "</div>";
	}


	if($_GET["page"] == "settings") {
		echo "<div class=\"form_container\">";
		echo $device->printIoTSettings($_GET["id"]);
		?>
		<div class="form_description"><h3>Explainer:</h3><p>-<b>"Forced value"</b> is highest priority (ignores everything else), mainly used for safety (in relation to other sensors like temperature)<br><br>
			-<b>"Set temp value"</b> is the second highest priority, but it just send the value once and then delete the field. Mainly for temporarily use like tests<br><br>
			-<b>"Time scheduleing"</b> has the third highest priority.<br><br>
			-<b>"Set default value"</b> is the fall back value.<br><br>
			if no fields are filled in no return value will be send to your equipment.
		</p></div>
		<?php
		echo "</div>";
	}

	if($_GET["page"] == "timer") {
		echo "<div class=\"listing\" id=\"timer_schedule_div\">";
		echo $device->printTimeSchedule($_GET["id"], $_GET["name"]);
	echo "</div>";
	}

} else {
	?>

	<div class="listing" id="iot_div">

	<table class="iot_auto_update" id="iot_table" width="100%">
	<?php
		echo $device->showIoT($_SESSION["facility_select"]);
	?>
	</table>
</div>

	<?php
}
// Inkluder Footer her
include("../template/footer.php");
?>
