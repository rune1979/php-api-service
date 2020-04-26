<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>NOVEFA Administration</title>
		<link rel="stylesheet" href="../template/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"><!-- ikoner -->
	</head>
	<body>
		<div class="login">
			<h1>NOVEFA Administration</h1>
			<form action="inc/authoriser.php" method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>
<?php

if (isset($_GET['setup'])){
	$file_pointer1 = "../setup.php";
	$file_pointer2 = "../db_setup.sql";

	chmod($file_pointer1, 0777);
	chmod($file_pointer2, 0777);
	// Use unlink() function to delete a file
	unlink($file_pointer1);
	unlink($file_pointer2);

}

?>
