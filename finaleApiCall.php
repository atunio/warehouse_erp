<?php

// URL and headers
$url = 'https://app.finaleinventory.com/cti/api/product/';
$headers = [
    'Accept: application/json',
    'Authentication: Basic OWVWV21Zc0FlVFFIOlRhVzR0ZWdiQ2FGS1pudjJFbm02UVRwVUkwNTJqVG81',
    'Content-Type: application/json'
];

// Form data as JSON
$data = [
    "sessionSecret" => "sdsdsdS942l69gTnCj9l8CPGks",
    "caliberString" => null,
    "categoryEnum" => null,
    "devicesPerCase" => null,
    "internalName" => null,
    "manufacturerName" => null,
    "manufacturerProductId" => null,
    "manufacturerCountryGeoId" => null,
    "productId" => "abctest121212",
    "productUrl" => null,
    "priceList" => null,
    "quantityUomId" => null,
    "statusId" => null,
    "userCategory" => null,
    "containerId" => null
];

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true); // This indicates a POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Attach JSON data

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Response:' . PHP_EOL;
    echo $response;
}

// Close cURL session
curl_close($ch);


/* 
require_once('vendor/autoload.php');
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://app.finaleinventory.com/cti/api/product/', [
    'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Basic OWVWV21Zc0FlVFFIOlRhVzR0ZWdiQ2FGS1pudjJFbm02UVRwVUkwNTJqVG81',
    ],
]);

echo $response->getBody();
*/