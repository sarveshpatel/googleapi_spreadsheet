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
	

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<style type="text/css">

#form-container {
	width: 400px;
	margin: 100px auto;
}

input[type="text"] {
	border: 1px solid rgba(0, 0, 0, 0.15);
	font-family: inherit;
	font-size: inherit;
	padding: 8px;
	border-radius: 0px;
	outline: none;
	display: block;
	margin: 0 0 20px 0;
	width: 100%;
	box-sizing: border-box;
}

.input-error {
	border: 1px solid red !important;
}

#create-spreadsheet {
	background: none;
	width: 100%;
    display: block;
    margin: 0 auto;
    border: 2px solid #2980b9;
    padding: 8px;
    background: none;
    color: #2980b9;
    cursor: pointer;
}

</style>
</head>

<body>
<h1>ALL Spreadsheets</h1>
<?php
for($i=0;$i<$total_files;$i++){
		$_file = $datas->items[$i];
		/*
		echo "<pre>";
		print_r($_file);
		echo "</pre>";
		*/
		
		echo "<br/>";
		//echo $_file->id.' ==> '.$_file->title;
?>
<?php echo $_file->title;?>	<a href="updatesheet.php?id=<?php echo $_file->id;?>">Update</a>
<?php		
		echo "<br/>";
	}
?>
<form action="createsheet.php" method="post" id="form-container">
	<input name="title" type="text" id="spreadsheet-title" placeholder="Spreadsheet Title" autocomplete="off" />
	<button type="submit" name="submit" id="create-spreadsheet">Create Spreadsheet</button>
</div>
<br/>

<!--
<div id="form-container">
	<a  href="updatesheet.php">Update Spreadsheet</a>
</div>
<div id="form-container">
	<a  href="createsheet.php">Create Spreadsheet</a>
</div>


-->
<script>

// Send an ajax request to create spreadsheet
$("#create-spreadsheet11").on('click', function(e) {
	var blank_reg_exp = /^([\s]{0,}[^\s]{1,}[\s]{0,}){1,}$/;

	$(".input-error").removeClass('input-error');

	if(!blank_reg_exp.test($("#spreadsheet-title").val())) {
		$("#spreadsheet-title").addClass('input-error');
		return;
	}

	$("#create-spreadsheet").attr('disabled', 'disabled');
	$.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: { spreadsheet_title: $("#spreadsheet-title").val() },
        dataType: 'json',
        success: function(response) {
        	$("#create-spreadsheet").removeAttr('disabled');
        	alert('Spreadsheet created in Google Drive with with ID : ' + response.spreadsheet_id);
        },
        error: function(response) {
            $("#create-spreadsheet").removeAttr('disabled');
            alert(response.responseJSON.message);
        }
    });
});

</script>

</body>
</html>