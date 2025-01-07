<?php
$authUrl = 'https://app.finaleinventory.com/cti/api/product/';

$apiKey = "Iu0p1MjbVtLi";    // Replace with your actual API Key
$apiSecret = "vF1nDRFKtc2gpR9PC86pIVVI3niycppC"; // Replace with your actual Secret

$credentials = base64_encode("$apiKey:$apiSecret"); // Encode the key:secret

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $authUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Basic $credentials"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

curl_close($ch);
