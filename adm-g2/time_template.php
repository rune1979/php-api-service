<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";

session_start();
// Variabler til header og footer
$sub_title = "Time Templates";
$sub_links = "<a href=\"time_template.php?page=add\">Add New Template</a>";

include "inc/class_template.php";


// CREATE OBJECT PAGE SPECIFIC
$templates = new ViewTemplate();

if (isset($_POST["submit"]) || $_GET["page"] == "edit_template") {unset($fac_menu);} else {$fac_menu = "1";}
include("../template/header.php");
// Start session
//session_start();
// Redirect hvis bruger ikke er logget ind....
//if (!isset($_SESSION['loggedin'])) {
//	header('Location: index.html');
//	exit();
//}




// Inkluder Header



if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "add") {
		echo $templates->printTemplateForm(null);
	}
	if($_GET["page"] == "edit_template") {
		echo "<div class=\"listing\" id=\"listing\">";
		echo $templates->printTimeTemplates($_GET['id']);
		echo "</div>";
	}

	if($_GET["page"] == "execute_template") {
		echo "<div class=\"listing\" id=\"listing\">";
		echo $templates->printImportTemplates($_GET['id']);
		echo "</div>";
	}

		echo "<div class=\"notion\">";
	if($_POST["addtemplate"] == "1") {
		echo $templates->add_template($_POST['user_id'], $_POST['zone_id'], $_POST['fac_id'], $_POST['name'], $_POST['description']);
	}
	if($_POST["edit"] == "1") {
		echo $templates->edit_template($_POST['id'], $_POST['user_id'], $_POST['zone_id'], $_POST['fac_id'], $_POST['name'], $_POST['description']);

	}
	if($_GET["page"] == "delete") {
		echo $templates->delete_template($_GET['id']);
	}
		echo "</div>";
	if($_GET["page"] == "edit") {
		echo $templates->printTemplateForm($_GET['id']);
	}
	if($_GET["page"] == "access") {
		?>
		<table width="100%">

		<?php
		echo $templates->showRelations($_GET['id']);
		?>

		<?php
	}
} else {

		?><div class="listing">
<table width="100%">

	<?php
			echo $templates->showTemplates($_SESSION["facility_select"]);
	?>
    		</table>
</div>
<?php
	}

// Inkluder Footer her
include("../template/footer.php");?>
