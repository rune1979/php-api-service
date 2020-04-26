<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>API Administration</title>
		<link rel="stylesheet" href="template/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"><!-- ikoner -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
		//$("div1").hide();
  //$("#check_settings").click(function(){
    $("#div1").hide(0).delay(300).fadeIn();
    $("#div2").hide(0).delay(700).fadeIn();
    $("#div3").hide(0).delay(1000).fadeIn();
    $("#div4").hide(0).delay(1400).fadeIn();
    $("#div5").hide(0).delay(2000).fadeIn();
		$("#div6").hide(0).delay(2100).fadeIn();
  //});
});
</script>
</head>

<body>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/////
// STEP 1
/////
if (empty($_POST['step']) && empty($_GET['step'])){
	echo "<div class=\"formula\"><h1>Setup Step 1</h1>";
	echo "<form action=\"\" method=\"post\">";
	// NEXT Step
	echo "<input type=\"hidden\" name=\"step\" value=\"2\">";
	$error=false;
	$php_version=phpversion();
	if($php_version<5)
	{
	  $error=true;
	  $php_error="PHP version is $php_version - too old!";
		echo $php_error;
		echo "<div class=\"setup\" id=\"div1\"><label>".$php_error."</label><i class=\"fas fa-exclamation-circle\"></i></div>";
	} else {
	  //echo "PHP version: ". $php_version .". Fine!";
		echo "<div class=\"setup\" id=\"div1\" ><label>PHP version: ". $php_version .".</label><i class=\"far fa-check-square\"></i></div>";
	}

	// declare function
	function find_SQL_Version() {
	  $output = shell_exec('mysql -V');
	  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	  return @$version[0]?$version[0]:-1;
	}

	$mysql_version=find_SQL_Version();
	if($mysql_version<5)
	{
	  if($mysql_version==-1){
			$mysql_error="MySQL version will be checked at the next step.";
			echo "<div class=\"setup\" id=\"div2\"><label>".$mysql_error."</label></div>";
		} else {
			$error=true;
			$mysql_error="MySQL version is $mysql_version. Version 5 or newer is required.";
			echo "<div class=\"setup\" id=\"div2\"><label>".$mysql_error."</label><i class=\"fas fa-exclamation-circle\"></i></div>";
		}
	} else {
	  echo "<div class=\"setup\" id=\"div2\"><label> MySQL version: ".$mysql_version."</label><i class=\"far fa-check-square\"></i></div>";
	}


	if(!function_exists('mail'))
	{
	  $mail_error="PHP Mail function is not enabled! It is recommended";
		echo "<div class=\"setup\" id=\"div3\"><label>".$mail_error."</label><i class=\"fas fa-exclamation-circle\"></i></div>";
	} else {
	  echo "<div class=\"setup\" id=\"div3\"><label>Mail function is enabled</label><i class=\"far fa-check-square\"></i></div>";
	}

	if( ini_get("safe_mode") )
	{
	  $error=true;
	  $safe_mode_error="Please switch of PHP Safe Mode";
		echo "<div class=\"setup\" id=\"div4\"><label>".$safe_mode_error."</label><i class=\"fas fa-exclamation-circle\"></i></div>";
	} else {
	  echo "<div class=\"setup\" id=\"div4\"><label>Safe Mode switched Off</label><i class=\"far fa-check-square\"></i></div>";
	}

	$_SESSION['myscriptname_sessions_work']=1;
	if(empty($_SESSION['myscriptname_sessions_work']))
	{
	  $error=true;
	  $session_error="Sessions must be enabled!";
		echo "<div class=\"setup\" id=\"div5\"><label>".$session_error."</label><i class=\"fas fa-exclamation-circle\"></i></div>";
	} else {
	  echo "<div class=\"setup\" id=\"div5\"><label>Sessions turned On</label><i class=\"far fa-check-square\"></i></div>";
	}

	echo "<div id=\"div6\">";
	if($error == false) echo "<span style='color:green;'>Everything seems - OK!<input type=\"submit\" value=\"Next\"></span></div>";
	else echo "<span style='color:red;'>There is some issues that needs to be fixed, before we can continue!<input type=\"submit\" onclick=\"location.reload();\" value=\"Check again!\"></span></div>";

	echo "</div></form>";
} elseif ($_POST['step'] == 2 || $_GET['step'] == 2) {

	/////
	// STEP 2
	/////
	echo "<div class=\"formula\"><h1>Setup Step 2 - Database Access</h1>";

	echo "<form action=\"\" method=\"post\">";
	/////
	// CHECK IF THIS IS THE FIRST ATTEMPT
	/////
	if (empty($_POST['db_name'])){
		echo "The system has been tested with MySQL Databases (recommended). It should be database independent, but setup errors might occur.";
		echo "<input type=\"hidden\" name=\"step\" value=\"2\">";
		echo "<label>DB host</label><input type=\"text\" name=\"host\" value=\"localhost\">";
		echo "<label>DB name</label><input type=\"text\" name=\"db_name\" value=\"Your_DB_name\">";
		echo "<label>DB user</label><input type=\"text\" name=\"user\" value=\"DB_Username\">";
		echo "<label>DB pass</label><input type=\"text\" name=\"pass\" value=\"DB_Password\">";
		echo "<input type=\"submit\" value=\"Test\">";
	} else {
		$db_error=false;
		// PDOStatement
		$dsn = 'mysql:dbname='.$_POST['db_name'].';host='.$_POST['host'].'';
		try {
    	$dbh = new PDO($dsn, $_POST['user'], $_POST['pass']);
		} catch (PDOException $e) {
	    echo 'Connection failed: ' . $e->getMessage();
			echo "<br>We can't reach any db with these initials.";
			$db_error=true;
		}

		if($db_error == false) {
			echo "<input type=\"hidden\" name=\"step\" value=\"3\">";
			echo "<input type=\"hidden\" name=\"host\" value=\"".$_POST['host']."\">";
			echo "<input type=\"hidden\" name=\"db_name\" value=\"".$_POST['db_name']."\">";
			echo "<input type=\"hidden\" name=\"user\" value=\"".$_POST['user']."\">";
			echo "<input type=\"hidden\" name=\"pass\" value=\"".$_POST['pass']."\">";
			echo "<span style='color:green;'>Everything seems - OK!<input type=\"submit\" value=\"Next\"></span>";
		}	else echo "<span style='color:red;'>There is some issues that needs to be fixed, before we can continue!(Make sure you have a database setup with a priviledged user.)<input type=\"submit\" onclick=\"location.reload();\" value=\"Check again!\"></span>";

	}

	echo "</form>";


} elseif ($_POST['step'] == 3) {
////
// STEP 3
////
echo "<div class=\"formula\"><h1>Setup Step 3 - inc/config.php</h1>";
echo "<form action=\"\" method=\"post\">";
echo "<input type=\"hidden\" name=\"host\" value=\"".$_POST['host']."\">";
echo "<input type=\"hidden\" name=\"db_name\" value=\"".$_POST['db_name']."\">";
echo "<input type=\"hidden\" name=\"user\" value=\"".$_POST['user']."\">";
echo "<input type=\"hidden\" name=\"pass\" value=\"".$_POST['pass']."\">";
$patterns = array();
$patterns[1] = "/localhost/";
$patterns[2] = "/db_user_to_change/";
$patterns[3] = "/pass_to_change/";
$patterns[4] = "/db_name_to_change/";
$replacement = array();
$replacement[1] = $_POST['host'];
$replacement[2] = $_POST['db_name'];
$replacement[3] = $_POST['pass'];
$replacement[4] = $_POST['user'];

$file_name = "inc/config_test.php";
$getting_file_contents = file_get_contents($file_name);

if ($getting_file_contents == true) {
  $replace_data_in_file = preg_replace($patterns, $replacement, $getting_file_contents);
  $writing_replaced_data = file_put_contents($file_name, $replace_data_in_file);

  if ($writing_replaced_data == true) {
    echo("<div class=\"setup\" id=\"div1\"><label>Data in the file changed!</label></div>");
		chmod($file_name, 0666);
		echo "<input type=\"hidden\" name=\"step\" value=\"4\">";
		echo "<div id=\"div2\"><span style='color:green;'>Ready to populate the Database - ".$_POST['db_name']."<input type=\"submit\" value=\"Next\"></span></div>";
  } else {
		echo "<input type=\"hidden\" name=\"step\" value=\"4\">";
    echo("<div class=\"setup\" id=\"div1\"><label>Cannot change creditential info in the inc/config.php file! Please do it manually or set right permission to file.</label></div>");
		echo "<div id=\"div2\"><span style='color:red;'>I have changed permissions to file.<input type=\"submit\" onclick=\"location.reload();\" value=\"Check again!\"></span></div>";
		echo "<div id=\"div2\"><span style='color:green;'>Continue - I have set the right initials manually.<input type=\"submit\" value=\"Next\"></div></span>";
  }
}
else {
  exit("<div class=\"setup\" id=\"div1\"><label>Unable to read inc/config.php file! Please set the right permissions.</label></div>");
}

echo "</form></div>";

} elseif ($_POST['step'] == 4) {
echo "<div class=\"formula\"><h1>Setup Step 4 - DB population</h1>";

echo "<form action=\"\" method=\"post\">";

echo "<div class=\"setup\"><label>Choose your login credidentials:</label></div><br>";
echo "<label>Admin Username</label><input type=\"text\" name=\"username\" value=\"admin\">";
echo "<label>Admin Password</label><input type=\"text\" name=\"password\" value=\"password\">";
echo "<label>Admin E-mail</label><input type=\"text\" name=\"email\" value=\"your@email.com\">";
echo "<input type=\"hidden\" name=\"step\" value=\"5\">";
echo "<input type=\"hidden\" name=\"host\" value=\"".$_POST['host']."\">";
echo "<input type=\"hidden\" name=\"db_name\" value=\"".$_POST['db_name']."\">";
echo "<input type=\"hidden\" name=\"user\" value=\"".$_POST['user']."\">";
echo "<input type=\"hidden\" name=\"pass\" value=\"".$_POST['pass']."\">";
echo "<input type=\"submit\" value=\"Next\">";

echo "</form></div>";
} elseif ($_POST['step'] == 5) {

	/* PDO connection start */
	$dsn = 'mysql:host=' . $_POST['host'] . ';dbname=' . $_POST['db_name'];
	$conn = new PDO($dsn, $_POST['user'], $_POST['pass']);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->exec("SET CHARACTER SET utf8");
	/* PDO connection end */

	// config file
	$filename = 'db_setup.sql';
	$maxRuntime = 8; // less then your max script execution limit

	$deadline = time()+$maxRuntime;
	$progressFilename = $filename.'_filepointer'; // tmp file for progress
	$errorFilename = $filename.'_error'; // tmp file for erro

	($fp = fopen($filename, 'r')) OR die('failed to open file:'.$filename);

	// check for previous error
	if( file_exists($errorFilename) ){
			die('<pre> previous error: '.file_get_contents($errorFilename));
	}

	// activate automatic reload in browser
	//echo '<html><head> <meta http-equiv="refresh" content="'.($maxRuntime+2).'"><pre>';

	// go to previous file position
	$filePosition = 0;
	if( file_exists($progressFilename) ){
			$filePosition = file_get_contents($progressFilename);
			fseek($fp, $filePosition);
	}

	$queryCount = 0;
	$query = '';
	while( $deadline>time() AND ($line=fgets($fp, 1024000)) ){
			if(substr($line,0,2)=='--' OR trim($line)=='' ){
					continue;
			}

			$query .= $line;
			if( substr(trim($query),-1)==';' ){

					$igweze_prep= $conn->prepare($query);

					if(!($igweze_prep->execute())){
							$error = 'Error performing query \'<strong>' . $query . '\': ' . print_r($conn->errorInfo());
							file_put_contents($errorFilename, $error."\n");
							exit;
					}
					$query = '';
					file_put_contents($progressFilename, ftell($fp)); // save the current file position for
					$queryCount++;
			}
	}
	echo "<div class=\"formula\">";
	if( feof($fp) ){
			echo 'Database successfully established!';
	}else{
			echo ftell($fp).'/'.filesize($filename).' '.(round(ftell($fp)/filesize($filename), 2)*100).'%'."\n";
			echo $queryCount.' queries processed! please reload or wait for automatic browser refresh!';
	}
	//Encrypt
	include "inc/config_test.php";
	class IoT extends Dbc{
		// INSERT NEW IOT insertIoT
		public function insert($user, $passwd, $email, $role){
			$sql = "INSERT INTO emp (user, passwd, email, role) VALUES (?,?,?,?)";
			$stmt = $this->prpConnect()->prepare($sql);
			if ($stmt->execute([$user, $passwd, $email, $role])){
				echo "New IoT device added to zone!";
			}
				// ADDING RECORD TO LOG
	}	}
	$device = new IoT();
	$passwd_enc = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$device->insert($_POST['username'],$passwd_enc,$_POST['email'],"1");
	echo "</div>";



	header("Location: adm-g2/index.php?setup=del");

}

?>


</div>
</body>
</html>
