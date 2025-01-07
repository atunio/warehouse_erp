<?php
$apiUrl = "https://app.finaleinventory.com/cti/api/product/";

$apiKey = "Iu0p1MjbVtLi";    // Replace with your actual API Key
$apiSecret = "vF1nDRFKtc2gpR9PC86pIVVI3niycppC"; // Replace with your actual Secret

// Prepare the product data (add any fields required by your API)
$productData = [
    "productId" => "abctest121216",

    /*
    "internalName" => "New Product 5",
    "manufacturerName" => 'Manufac 2',
    "quantity" => 15,
    "priceList" => [
        [
            "currencyUomId" => "USD",
            "productPriceTypeId" => "LIST_PRICE",
            "price" => 700
        ]
    ]
    */

];

// Base64 encode the API credentials
$credentials = base64_encode("$apiKey:$apiSecret");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Basic $credentials"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData)); // Send product data as JSON

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;  // Print the API response (success or failure)
}

curl_close($ch);
