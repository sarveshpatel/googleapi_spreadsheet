<?php
session_start();
header('Content-type: application/json');

require_once('google-spreadsheets-api.php');

try {
	$spreadsheet_title = $_POST['spreadsheet_title'];

	$sapi = new GoogleSpreadsheetsApi();

	// Create spreadsheet
	// Spreadsheets are created in Google Drive
	$spreadsheet_data = $sapi->CreateSpreadsheet($spreadsheet_title, $_SESSION['access_token']);
	
	echo json_encode([ 'spreadsheet_id' => $spreadsheet_data['spreadsheet_id'] ]);
}
catch(Exception $e) {
	header('Bad Request', true, 400);
    echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() ));
}

?>