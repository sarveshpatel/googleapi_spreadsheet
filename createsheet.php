<?php
	session_start();
	if(!isset($_SESSION['access_token'])) {
		header('Location: google-login.php');
		exit();	
	}
	
	if(isset($_POST['submit']) && $_POST['title']!=''){
		$access_token = $_SESSION['access_token'];
		$url = "https://sheets.googleapis.com/v4/spreadsheets";
		$title = $_POST['title'];
		$data = '{"properties":{"title":"'.$title.'"}}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 0);
		curl_setopt($curl, CURLOPT_HEADER, 0);			
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  $response;
		}
		$dataAll = json_decode($response);
		//echo $dataAll->spreadsheetId;
		print_r($dataAll);
	}else{
		header("Location: home.php");
	}
		header("Location: home.php");
?>