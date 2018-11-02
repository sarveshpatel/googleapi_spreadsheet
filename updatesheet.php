<?php
	session_start();
	if(!isset($_SESSION['access_token'])) {
		header('Location: google-login.php');
		exit();	
	}
	if(isset($_GET['id']) && $_GET['id']!=''){
		$access_token = $_SESSION['access_token'];
		$id = $_GET['id'];
		$range = 'A:Z';
		$url = "https://sheets.googleapis.com/v4/spreadsheets/".$id."/values/".$range."?valueInputOption=RAW";
		$ranges = '{"majorDimension":"ROWS","range":"A:Z",
		"values":[
					["Title","FirstName","LastName","Email","Phone","Address","Mobile","Status","Password","ModifyDate","CreatedDate"],
					[121,131,"551"],
					[131,14,"df"],
					[131,14,"df"],
					[131,14,"df"],
					[131,14,"df"],
					[131,14,"df"]
				]
		}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 0);
		curl_setopt($curl, CURLOPT_HEADER, 0);			
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $ranges);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  $response;
		}
		print_r($response);
	}
	header("Location: home.php");
?>