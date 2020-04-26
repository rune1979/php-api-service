<?php
/* Database og session start */
session_start();

$servername = "localhost";
$username = "admin_g2chiller";
$password = "MaaGodt7913";
$dbname = "admin_g2_chillerhot";
$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if ( !isset($_POST['username'], $_POST['password']) ) {
	// Vi modtager ikke brugernavn eller password.
	die ('Udfyld venligst begge felter!');
}

if ($stmt = $conn->prepare('SELECT id, passwd, role FROM emp WHERE user = ?')) {
	// Bind parameter (s = string, i = int, b = blob, osv.), brugernavnet er en string, så vi benytter "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Vi gemmer retur resultatet.
	$stmt->store_result();
}


if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $passwd, $role);
	$stmt->fetch();
	// Konto eksisterer og vi verificerer password.
	// Note: Brug password_hash i registrering og benyt følgende for verification.
	if (password_verify($_POST['password'], $passwd)) {
	//if ($_POST['password'] === $passwd) {
		// Verifikation ok! Bruger er logget ind!
		// Opretter en session så systemet kan huske brugeren i andre sammenhænge.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		$_SESSION['role'] = $role;
    $_SESSION["facility_select"] = "0";
		echo 'Velkommen ' . $_SESSION['name'] . '!';
		header('Location: ../administration.php');
	} else {
		echo 'Forkert password!';
	}
} else {
	echo 'Forkert brugernavn!';
}
$stmt->close();

?>
