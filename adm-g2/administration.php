<?php
// Inkluder konfigurationsfilen
require_once '../inc/config.php';


session_start();
// Variabler til header og footer
$sub_links = "Welcome, ".$_SESSION['name']."!";
$sub_title = "Control panel";
$sub_sub_title = "- Overview";

if (isset($_GET["page"]) || isset($_POST["submit"])) {unset($fac_menu);} else {$fac_menu = "1";}
include("../template/header.php");


// Inkluder Header

?>

<div class="listing">
<h3>Alerts:</h3>
	<table width="100%" class="alert_1" id="alert_1">
	<?php
		echo $standard->showAlerts($_SESSION["facility_select"],"4","1", "#alert_1");
	?>
	</table>
</div>
<div class="listing">
<h3>Warnings:</h3>
	<table width="100%" class="alert_2" id="alert_2">
	<?php
		echo $standard->showAlerts($_SESSION["facility_select"],"3","1", "#alert_2");
	?>
	</table>
</div>

<div class="listing">
<h3>Notice:</h3>
	<table width="100%" class="alert_3" id="alert_3">
	<?php
		echo $standard->showAlerts($_SESSION["facility_select"],"2","1", "#alert_3");
	?>
	</table>
</div>

<div class="listing">
<h3>Zones:</h3>
	<table width="100%">
	<?php
		echo $standard->showZoneTemp($_SESSION["facility_select"]);
	?>
	</table>
</div>
<div class="listing">
<h3>Log:</h3>
	<table width="100%">
	<?php
		echo $standard->showAllLogs();
	?>
	</table>
</div>

<?php
// Inkluder Footer her
include("../template/footer.php");?>
