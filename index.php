<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
<form action="" method="post">
		<div class="form-group">
			<label for="name">https://florianegrosset.com/api/cv/read/(empid)</label>
			<input type="text" name="url" value="https://florianegrosset.com/api/cv/read.php?id=1" class="form-control" required/>
			
		</div>
		<button type="submit" name="submit">Make API Request</button>
	</form>
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
</body>
</html>