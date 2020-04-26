<?php
// Inkluder konfigurationsfilen
require_once '../inc/config.php';
//include "inc/class.php";
// Start session
session_start();
// Redirect hvis bruger ikke er logget ind...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

// Variabler til header og footer
$sub_title = "Profil";

// Inkluder Header
include("../template/header.php");

$get_user = new ViewBasic();
?>

<div class="listing">
<h3>Personal Profile:</h3>
<?php
if(isset($_GET["uid"])) {
    	echo $get_user->printUserProfile($_GET["uid"]);
    } else {
	echo $get_user->printUserProfile($_SESSION['id']);
}
?>
</div>
<?php
// Inkluder Footer her
include("../template/footer.php");?>
