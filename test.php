<?php
session_start();
header('Content-type: application/json');
require_once('google-spreadsheets-api.php');
require_once('settings.php');

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$sapi = new GoogleSpreadsheetsApi();
		
		// Get the access token 
		$data = $sapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
		
		// Save the access token as a session variable
		$_SESSION['access_token'] = $data['access_token'];

		
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}

if(isset($_SESSION['access_token'])){
try {
	//$spreadsheet_title = 'Test '.rand();
	$spreadsheet_id = '1eX0wgldXeDWA4_Z90wsMhQneMrQGEZSew3hkDxDl5eI';
	$sapi = new GoogleSpreadsheetsApi();
	$spreadsheet_title = 'sab '.rand();
	$ranges = '{"majorDimension":"ROWS","range":"A1:C3","values":[[11,12,"d"],[12,13,"55"],[13,14,"df"]]}';
	// Create spreadsheet
	// Spreadsheets are created in Google Drive
	$spreadsheet_data = $sapi->UpdateSpreadsheetProperties($spreadsheet_id, $ranges,$_SESSION['access_token']);
	print_r($spreadsheet_data);
	echo json_encode([ 'spreadsheet_id' => $spreadsheet_data['spreadsheet_id'] ]);
}
catch(Exception $e) {
	header('Bad Request', true, 400);
    echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() ));
}
}
?>