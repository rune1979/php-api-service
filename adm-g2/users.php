<?php
// Inkluder konfigurationsfilen
//require_once '../inc/config.php';
include "../inc/config.php";





// Variabler til header og footer
$sub_title = "Profiles";
$sub_links = "<a href=\"users.php?page=tilfoj\">Add New</a>";
$sub_sub_title = "";

// Inkluder Header
include("../template/header.php");
//Object
include "inc/class_user.php";
$userse = new ViewUsers();

if(isset($_GET["page"]) || isset($_POST["submit"])) {
	if($_GET["page"] == "tilfoj") {
		echo $userse->printAddUserForm("non");
	}
	if($_POST["adduser"] == "1") {
		echo $userse->addNewUser($_POST['name'], $_POST['cpr'], $_POST['role'], $_POST['rfid'], $_POST['passd'], $_POST['user'], $_POST['passwd']);
	}
	if($_POST["adduser"] == "2") {
		echo $userse->updateUserView($_POST['id'], $_POST['name'], $_POST['cpr'], $_POST['role'], $_POST['rfid'], $_POST['passd'], $_POST['user'], $_POST['passwd']);

	}
	if($_GET["page"] == "slet") {
		echo $userse->deleteUserView($_GET['id']);
	}
	if($_GET["page"] == "rediger") {
		echo $userse->printAddUserForm($_GET['id']);
	}
} else {

		?>
		<div class="listing">
		<table width="100%">
	<?php
			echo $userse->showAllUsers();
	?>
</table></div>

<?php
	}

// Inkluder Footer her
include("../template/footer.php");?>
