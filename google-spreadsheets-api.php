<?php

class GoogleSpreadsheetsApi
{
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
		$url = 'https://accounts.google.com/o/oauth2/token';			
		
		$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to receieve access token');
			
		return $data;
	}

	public function CreateSpreadsheet($spreadsheet_title, $access_token) {
		$curlPost = array('properties' => array('title' => $spreadsheet_title));
		
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, 'https://sheets.googleapis.com/v4/spreadsheets');		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));	
		$data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to create spreadsheet');

		return array('spreadsheet_id' => $data['spreadsheetId'], 'spreadsheet_url' => $data['spreadsheetUrl']);
	}

	public function UpdateSpreadsheetProperties($spreadsheet_id, $spreadsheet_title, $access_token) {
		$curlPost = array('name' => $spreadsheet_title);
		
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/drive/v3/files/' . $spreadsheet_id);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));	
		$data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		if($http_code != 200) 
			throw new Exception('Error : Failed to update spreadsheet properties');
	}

	public function DeleteSpreadsheet($spreadsheet_id, $access_token) {
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/drive/v3/files/' . $spreadsheet_id);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));		
		$data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		if($http_code != 204) 
			throw new Exception('Error : Failed to delete spreadsheet');
	}
	
	
}

?>