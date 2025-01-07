<?php


// Start session to store the token
session_start();


function execute_curl_request($cursor = null)
{
	$url = 'https://api.eu-west-1.blancco.cloud/v1/report/export';
	$headers = [
		'Content-Type: application/json',
		'X-BLANCCO-API-KEY: eda3e0f8-fb01-4658-bf0f-fe774b72a741'
	];

	$data = [
		'filter' => [
			'date' => [
				'gte' => '2024-09-01T00:00:00Z'
			]
		],
		'format' => 'XML',
		'container' => 'NONE',
		'size' => 1
	];

	if ($cursor) {
		$url .= "?cursor=" . urlencode($cursor);
	}

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$response_headers = substr($response, 0, $header_size);
	$response_body = substr($response, $header_size);

	curl_close($ch);

	return [
		'http_code' => $http_code,
		'response_body' => $response_body,
		'response_headers' => $response_headers
	];
}

function check_and_print_response($response)
{
	if ($response['http_code'] == 200) {
		echo $response['response_body'];

		// Parse headers to find cursor
		preg_match('/x-blancco-cursor: (\S+)/', $response['response_headers'], $matches);
		$cursor_header = isset($matches[1]) ? $matches[1] : null;

		return [$cursor_header, true];
	} else {
		echo "Request failed with status code: " . $response['http_code'];
		return [null, false];
	}
}

$cursor = null;
$success_count = 0;
$failure_count = 0;

while (true) {
	$response = execute_curl_request($cursor);

	if ($success_count == 0) {
		echo "\n--------------------------------------------------\n";
	}

	list($new_cursor, $success) = check_and_print_response($response);

	if ($success) {
		$success_count++;
	} else {
		$failure_count++;
	}

	if ($new_cursor) {
		$cursor = $new_cursor;
	} else {
		break;
	}
}

echo "\n--------------------------------------------------\n";
echo "All matching reports exported. Summary of exported reports:\n";
echo "Successful exports: $success_count\n";
echo "Failed exports: $failure_count\n";
