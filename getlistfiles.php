<?php
	session_start();
	if(!isset($_SESSION['access_token'])) {
		header('Location: google-login.php');
		exit();	
	}

	$access_token = $_SESSION['access_token'];	
	$url = "https://www.googleapis.com/drive/v2/files?q=trashed%3Dfalse";

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_VERBOSE, 0);
	curl_setopt($curl, CURLOPT_HEADER, 0);			
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  $response;
	}
	$datas = json_decode($response);
	$total_files = count($datas->items);
	for($i=0;$i<$total_files;$i++){
		$_file = $datas->items[$i];
		/*
		echo "<pre>";
		print_r($_file);
		echo "</pre>";
		*/
		
		echo "<br/>";
		echo $_file->id.' ==> '.$_file->title;
		echo "<br/>";
	}
?>