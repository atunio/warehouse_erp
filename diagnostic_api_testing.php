<?php
die;
// Start session to store the token
use SendGrid\Response;

session_start();
if (!isset($_SESSION['token'])) {
	athenticate_phonecheck();
} else {
	// https://clientapiv2.phonecheck.com/cloud/CloudDB/v2/GetAllDevices // all devices using data with apikey
	// $url = "https://clientapiv2.phonecheck.com/cloud/cloudDB/GetDeviceInfo"; // Device info using IMEI & User
	// $url = "https://api.phonecheck.com/v2/master/imei/device-info/$imei?detailed=true";

	$imei = "F9FRFN0HGHKH"; // Replace with actual IMEI or other identifier

	echo "<br><br> ALL MAC : <br>";
	$parm1 = $parm2 = $parm3 = $parm4 = $parm5 = "";
	$url = "https://api.phonecheck.com/v2/master/mac-all-device-info?page=0&size=500&startDate=2020-01-01&endDate=2024-01-10&sort=updatedAt&direction=desc";   // Latest
	$device_detail_array = phone_check_api_call_using_token($url, $parm1, $parm2, $parm3, $parm4, $parm5);
	echo json_encode($device_detail_array, true);



	// echo "<br><br>MAC Single Device Info : <br>";
	// $parm1 = $parm2 = $parm3 = $parm4 = $parm5 = "";
	// $url = "https://api.phonecheck.com/v2/master/serial/mac-device-info?serial=$mac_serial";   // Latest
	// $device_detail_array = phone_check_api_call_using_token($url, $parm1, $parm2, $parm3, $parm4, $parm5);
	// echo json_encode($device_detail_array, true);


	echo "<br><br>Device info using IMEI & User with Key: <br>";
	$apiKey = "3b6dff0d-ecbb-424a-baf1-c6bbfffdbef2";
	$username = "Ctinno7"; // Replace with your StationID username
	$data = [
		'Apikey' 		=> $apiKey,
		'Username' 		=> $username,
		'IMEI' 	=> $imei
	];

	$url = "https://clientapiv2.phonecheck.com/cloud/cloudDB/GetDeviceInfo"; // Device info using IMEI & User with Key
	$all_devices_info = call_phone_check_using_data($url, $data);
	print_r($all_devices_info);

	echo "<br><br>Device info Latest using IMEI with authentication code : <br>";
	$parm1 = $parm2 = $parm3 = $parm4 = $parm5 = "";
	$url = "https://api.phonecheck.com/v2/master/imei/device-info/$imei?detailed=true";   // Latest
	$device_detail_array = phone_check_api_call_using_token($url, $parm1, $parm2, $parm3, $parm4, $parm5);
	echo json_encode($device_detail_array, true);

	echo "<br><br>Device info legacy using IMEI with authentication code : <br>";
	$parm1 = $parm2 = $parm3 = $parm4 = $parm5 = "";
	$url = "https://api.phonecheck.com/v2/master/imei/device-info-legacy/$imei";
	$device_detail_array = phone_check_api_call_using_token($url, $parm1, $parm2, $parm3, $parm4, $parm5);
	echo json_encode($device_detail_array, true);



	echo "<br><br>All Devices Info : <br>";
	$username = "Ctinno7"; // Replace with your StationID username
	$date = "2024-10-04";  // Filter by Date (optional)
	$station = "Kai3";  // Optional
	$invoiceNo = "164312";  // Optional 
	$invoiceNo = "19200";  // Optional
	$startDate = "2024-10-08";  // Optional
	$endDate = "2024-10-04";  // Optional
	$deviceDisconnect = "2024-10-04 14:00:00";  // Optional
	$limit	= 100;  // Optional, max 500 records
	$offset	= 1;  // Optional

	$data222 = [
		'Username' 			=> $username,
		'Date' 				=> $date,
		'Station' 			=> $station,
		'Invoiceno' 		=> $invoiceNo,
		'startdate' 		=> $startDate,
		'enddate' 			=> $endDate,
		'deviceDisconnect' 	=> $deviceDisconnect,
		'limit' 			=> $limit,
		'offset' 			=> $offset
	];

	$apiKey = "3b6dff0d-ecbb-424a-baf1-c6bbfffdbef2";
	$data = [
		'Apikey' 		=> $apiKey,
		'Username' 		=> $username,
		'Invoiceno' 	=> $invoiceNo,
		'Date' 			=> $date,
		'limit' 		=> $limit,
		'offset' 		=> $offset
	];

	$all_devices_info = v2_devices_call_phonecheck($data);
	echo json_encode($all_devices_info, true);


	/*
	if (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) > 0) {
		$k = 1;
		foreach ($all_devices_info['imei'] as $data) {
			echo "<br>imei " . $k . ": " . $data;
			$k++;
		}
	}

	echo "<br>";

	if (isset($all_devices_info['udid']) && sizeof($all_devices_info['udid']) > 0) {
		$k = 1;
		foreach ($all_devices_info['udid'] as $data) {
			echo "<br>udid " . $k . ": " . $data;
			$k++;
		}
	}

	echo "<br>";


	if (isset($all_devices_info['serial']) && sizeof($all_devices_info['serial']) > 0) {
		$k = 1;
		foreach ($all_devices_info['serial'] as $data) {
			echo "<br>serial " . $k . ": " . $data;
			$k++;
		}
	}
	*/


	die;



	///*
	$imei = "R72F1QJ62X"; // Replace with actual IMEI or other identifier
	$device_detail_array = getinfo_phonecheck_imie($imei);

	print_r($device_detail_array);
	echo "<br>";
	echo "<br>";

	foreach ($device_detail_array as $key1 => $data1) {


		if (!is_array($data1)) {
			print_r($data1);
			echo "<br><br>";
			echo "<br>key1: " . $key1;
			echo "<br>data1: " . $data1;
		}
	}
	die;



	//*/
}

function v2_devices_call_phonecheck($data)
{
	// https://phonecheck.atlassian.net/wiki/spaces/KB/pages/2271772692/Phonecheck+API+to+Get+All+Devices+-+V2
	// Set the API endpoint URL (US version, switch to EU URL if needed)
	$url = "https://clientapiv2.phonecheck.com/cloud/CloudDB/v2/GetAllDevices";
	// API key and other parameters (replace with your actual values)

	// Create the data array for the POST request

	// Initialize a cURL session
	$ch = curl_init();

	// Set the cURL options for a POST request
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/json"
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	// Execute the cURL request and get the response
	$response = curl_exec($ch);

	// Check for errors in the cURL execution
	if ($response === false) {
		echo "cURL Error: " . curl_error($ch);
	} else {
		// Decode the JSON response
		$data = json_decode($response, true);

		$all_imeis 		= array();
		$all_serials 	= array();
		$all_udids		= array();

		$c = 1;
		// Check if data is received successfully
		if (isset($data) && !empty($data)) {
			foreach ($data as $key1 => $data1) {
				foreach ($data1 as $key2 => $data2) {
					// echo "<br>";
					// echo "<br>Key2: " . $key2;
					// echo "<br>data2: " . $data2;
					// echo "<br>";

					if ($key2 == 'IMEI') {
						$all_imeis[] = $data2;
						$c++;
					} else if ($key2 == 'Serial') {
						$all_serials[] = $data2;
					} else if ($key2 == 'UDID') {
						$all_udids[] = $data2;
					}
				}
			}
		} else {
			echo "No data received or error in response.\n";
		}
	}
	// Close the cURL session
	curl_close($ch);

	$responseArray = [
		'imei' => $all_imeis,
		'serial' => $all_serials,
		'udid' => $all_udids
	];


	return $responseArray;

	/*

	$url = "https://api.phonecheck.com/v2/devices";  // Replace with actual API endpoint
	$apiKey = "3b6dff0d-ecbb-424a-baf1-c6bbfffdbef2";

	$headers = [
		"Authorization: Bearer $apiKey",
		"Content-Type: application/json"
	];

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		echo 'Error: ' . curl_error($ch);
	} else {
		echo 'Response: ' . $response;
	}

	curl_close($ch);
	*/
}

function athenticate_phonecheck()
{
	// API URL
	$url = "https://api.phonecheck.com/v2/auth/master/login";

	// Your master username and password
	$username = "ctinnovationsnyc"; // Replace with actual username
	$password = "ucxt3wzthx"; // Replace with actual password

	// Prepare the request body
	$data = json_encode([
		"username" => $username,
		"password" => $password
	]);

	// Initialize cURL session
	$ch = curl_init($url);

	// Set cURL options
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json'
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	// Execute the cURL request and get the response
	$response = curl_exec($ch);

	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Request Error: ' . curl_error($ch);
		curl_close($ch);
		exit();
	}

	// Close the cURL session
	curl_close($ch);

	// Decode the response
	$responseData = json_decode($response, true);

	// Check if the response contains a token
	if (isset($responseData['token'])) {
		// Store the token in session
		$_SESSION['token'] = $responseData['token'];

		// echo "<br>Login successful, token stored in session.";
		// echo "<br><br>token:" . $responseData['token'];
	} else {
		// Handle errors or invalid credentials
		if (isset($responseData['err'])) {
			echo "Error: " . $responseData['err'];
		} elseif (isset($responseData['msg'])) {
			echo "Error: " . $responseData['msg'];
		} else {
			echo "Unexpected error.";
		}
	}
}

function getinfo_phonecheck_imie($imei)
{
	// echo "<br><br>Token2: " . $_SESSION['token'];

	// Set the IMEI or other identifier

	// API URL with the IMEI
	$url = "https://api.phonecheck.com/v2/master/imei/device-info/$imei?detailed=true";

	$ch = curl_init($url);
	// Set cURL options for GET request
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'token_master: ' . $_SESSION['token'], // Send token in token_master key
		'Content-Type: application/json'
	]);
	// Execute the cURL request and get the response
	$response = curl_exec($ch);
	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Request Error: ' . curl_error($ch);
		curl_close($ch);
		exit();
	}
	// Close the cURL session
	curl_close($ch);
	// Decode the response
	$responseData = json_decode($response, true);
	// Check if the response contains device information
	if (isset($responseData)) {
		// Display device information
		return $responseData;
	} else {
		echo "No device information found.";
	}
}


function phone_check_api_call_using_token($url, $parm1, $parm2, $parm3, $parm4, $parm5)
{
	$ch = curl_init($url);
	// Set cURL options for GET request
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'token_master: ' . $_SESSION['token'], // Send token in token_master key
		'Content-Type: application/json'
	]);
	// Execute the cURL request and get the response
	$response = curl_exec($ch);
	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Request Error: ' . curl_error($ch);
		curl_close($ch);
		exit();
	}
	// Close the cURL session
	curl_close($ch);
	// Decode the response
	$responseData = json_decode($response, true);
	// Check if the response contains device information
	if (isset($responseData)) {
		// Display device information
		return $responseData;
	} else {
		echo "No device information found.";
	}
}


function call_phone_check_using_data($url, $data)
{
	// Initialize a cURL session
	$ch = curl_init();

	// Set the cURL options for a POST request
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/json"
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	// Execute the cURL request and get the response
	$response = curl_exec($ch);

	// Check for errors in the cURL execution
	if ($response === false) {
		echo "cURL Error: " . curl_error($ch);
	} else {

		return $response;
	}
}
