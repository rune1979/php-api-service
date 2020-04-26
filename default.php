<?php 
//header("Access-Control-Allow-Origin: *");
include('/template/header.php');
?>
<title>SERVER ACCESS SYSTEM - GRUPPE 2</title>
<?php //include('inc/container.php');?>
<div class="container">
	<h2>RFID ACCESS API</h2>	
	<br>
	<br>
	<form action="" method="get">
		<div class="form-group">
			<label for="name">http://g2.chillerhot.com/emp/read/(empid)</label>
			<input type="text" name="url" value="http://g2.chillerhot.com/emp/read/" class="form-control" required/>
			
		</div>
		<button type="submit" name="submit" class="btn btn-default">Make API Request</button>
	</form>
	<p>&nbsp;</p>
	<?php
	if(isset($_POST['submit']))	{
		$url = $_POST['url'];				
		$client = curl_init($url);
		curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
		$response = curl_exec($client);		
		$result = json_decode($response);	
		print_r($result);		
	}
	?>	
</div>
<?php include('/template/footer.php');?>
