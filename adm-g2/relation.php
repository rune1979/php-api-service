<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";

// Variabler til header og footer
$sub_title = "Relations";
$sub_links = "<a href=\"relation.php?page=tilfoj\">Tilføj </a> <a href=\"relation.php\"> Se alle relationer</a>";
$sub_sub_title = "";

if (isset($_GET["page"]) || isset($_POST["submit"])) {unset($fac_menu);} else {$fac_menu = "1";}

include("../template/header.php");
// Start session
session_start();
// Redirect hvis bruger ikke er logget ind....
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

include "inc/class_relation.php";



// Inkluder Header

//Object
$userse = new ViewRelation();

if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {
		echo $userse->printRelationForm("non");
	}
	if($_POST["addrel"] == "1") {
		echo "<div class=\"notion\">";
		echo $userse->addNewRelation($_POST['user_id'], $_POST['zone_id'], $_POST['time_from'], $_POST['time_to'], $_POST['date_from'], $_POST['date_to']);
		echo "</div>";
		header( "refresh:2;url=relation.php" );
	}
	if($_POST["addrel"] == "2") {
		echo "<div class=\"notion\">";
		echo $userse->updateRelationView($_POST['id'], $_POST['user_id'], $_POST['zone_id'], $_POST['time_from'], $_POST['time_to'], $_POST['date_from'], $_POST['date_to']);
		echo "</div>";
		header( "refresh:2;url=relation.php" );
	}
	if($_GET["page"] == "slet") {
		echo "<div class=\"notion\">";
		echo $userse->deleteRelationView($_GET['id']);
		echo "</div>";
		header( "refresh:2;url=relation.php" );
	}
	if($_GET["page"] == "rediger") {
		echo $userse->printRelationForm($_GET['id']);
	}
	if($_GET["page"] == "access") {
		?>
		<div class="listing">
		<table width="100%">
		<?php

		echo $userse->showRelations($_GET['id']);
		?>
	</table></div>
		<?php
	}
} else {

		?>
		<div class="listing">
<table width="100%">
	<?php
			echo $userse->showRelations("all");
	?>
</table></div>

<?php
	}

// Inkluder Footer her
include("../template/footer.php");?>
