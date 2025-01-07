<?php
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$apiKey     = "Iu0p1MjbVtLi";    // Replace with your actual API Key
$apiSecret  = "vF1nDRFKtc2gpR9PC86pIVVI3niycppC"; // Replace with your actual Secret
// Base64 encode the API credentials
$credentials = base64_encode("$apiKey:$apiSecret");

// Shipment End Point 
// https://app.finaleinventory.com/cti/api/shipment/328966
/*

// Shipment  Parameters
{"actionUrlCancel":"/cti/api/shipment/328966/cancel","actionUrlPack":null,"actionUrlUnpack":null,"actionUrlReceive":"/cti/api/shipment/328966/receive","actionUrlShip":null,"actionUrlTransfer":null,"consolidatedItemList":[],"countPackages":null,"facilityUrlPack":null,"packDate":null,"primaryOrderUrl":"/cti/api/order/adee343","primaryReturnUrl":null,"receiveDate":null,"receiveDateEstimated":null,"destinationFacilityUrl":null,"shipmentIdUser":"adee343-1","shipDate":null,"shipDateEstimated":null,"originFacilityUrl":null,"carrierPartyUrl":null,"shipmentItemList":[{"productUrl":"/cti/api/product/P20241dd3","facilityUrl":"/cti/api/facility/10039","quantity":1,"lotId":"L_3232323"},{"productUrl":"/cti/api/product/P20241127164557","facilityUrl":"/cti/api/facility/10039","quantity":1}],"contentList":[],"trackingCode":null,"transferList":[],"shipmentTypeId":"PURCHASE_SHIPMENT","shipmentUrl":"/cti/api/shipment/328966","externalUrl":null,"statusId":"SHIPMENT_INPUT","statusIdHistoryList":[{"statusId":null,"txStamp":1732770774,"userLoginUrl":"/cti/api/userlogin/aftab.cti"}],"userFieldDataList":[],"privateNotes":null,"publicNotes":null,"sessionSecret":"NX3Hh9FyXUgpuaZ4QGHh"}: 
{
  "actionUrlCancel": "/cti/api/shipment/328966/cancel",
  "actionUrlPack": null,
  "actionUrlUnpack": null,
  "actionUrlReceive": "/cti/api/shipment/328966/receive",
  "actionUrlShip": null,
  "actionUrlTransfer": null,
  "consolidatedItemList": [],
  "countPackages": null,
  "facilityUrlPack": null,
  "packDate": null,
  "primaryOrderUrl": "/cti/api/order/adee343",
  "primaryReturnUrl": null,
  "receiveDate": null,
  "receiveDateEstimated": null,
  "destinationFacilityUrl": null,
  "shipmentIdUser": "adee343-1",
  "shipDate": null,
  "shipDateEstimated": null,
  "originFacilityUrl": null,
  "carrierPartyUrl": null,
  "shipmentItemList": [
    {
      "productUrl": "/cti/api/product/P20241dd3",
      "facilityUrl": "/cti/api/facility/10039",
      "quantity": 1,
      "lotId": "L_3232323"
    },
    {
      "productUrl": "/cti/api/product/P20241127164557",
      "facilityUrl": "/cti/api/facility/10039",
      "quantity": 1
    }
  ],
  "contentList": [],
  "trackingCode": null,
  "transferList": [],
  "shipmentTypeId": "PURCHASE_SHIPMENT",
  "shipmentUrl": "/cti/api/shipment/328966",
  "externalUrl": null,
  "statusId": "SHIPMENT_INPUT",
  "statusIdHistoryList": [
    {
      "statusId": null,
      "txStamp": 1732770774,
      "userLoginUrl": "/cti/api/userlogin/aftab.cti"
    }
  ],
  "userFieldDataList": [],
  "privateNotes": null,
  "publicNotes": null,
  "sessionSecret": "NX3Hh9FyXUgpuaZ4QGHh"
}

Update Purchase Order Parameters
https://app.finaleinventory.com/cti/api/order/p00998
{"actionUrlCancel":"/cti/api/order/p00998/cancel","actionUrlComplete":"/cti/api/order/p00998/complete","actionUrlEdit":null,"actionUrlLock":"/cti/api/order/p00998/lock","reserveAllUrl":"/cti/api/order/p00998/reserveall","contactMechList":null,"autoUnlockRelock":null,"destinationFacilityUrl":"/cti/api/facility/10000","originFacilityUrl":null,"orderAdjustmentList":[],"orderDate":"2024-11-28T07:00:00","receiveDate":null,"orderId":"p00998","orderItemList":[{"quantity":1,"productUrl":"/cti/api/product/P20241127164557","unitPrice":15},{"quantity":1,"productUrl":"/cti/api/product/P20241dd3","unitPrice":20}],"publicNotes":null,"privateNotes":null,"orderRoleList":[],"contentList":[],"orderTypeId":"PURCHASE_ORDER","orderUrl":"/cti/api/order/p00998","orderHistoryListUrl":"/cti/api/order/p00998/history/","quoteUrlList":null,"invoiceUrlList":null,"shipmentUrlList":["/cti/api/shipment/328967"],"returnUrlList":null,"varianceUrlList":null,"productPriceTypeId":null,"settlementTermId":null,"fulfillmentId":null,"requestedShippingServiceId":null,"saleSourceId":null,"referenceNumber":null,"batchUrl":null,"batchId":null,"statusId":"ORDER_CREATED","userFieldDataList":[],"invoiceList":null,"shipmentList":null,"createdDate":"2024-11-28T05:16:14","lastUpdatedDate":"2024-11-28T05:16:14","shipDate":null,"connectionRelationUrlList":[],"processingFacilityUrl":null,"sessionSecret":"NX3Hh9FyXUgpuaZ4QGHh"}: 
{
  "actionUrlCancel": "/cti/api/order/p00998/cancel",
  "actionUrlComplete": "/cti/api/order/p00998/complete",
  "actionUrlEdit": null,
  "actionUrlLock": "/cti/api/order/p00998/lock",
  "reserveAllUrl": "/cti/api/order/p00998/reserveall",
  "contactMechList": null,
  "autoUnlockRelock": null,
  "destinationFacilityUrl": "/cti/api/facility/10000",
  "originFacilityUrl": null,
  "orderAdjustmentList": [],
  "orderDate": "2024-11-28T07:00:00",
  "receiveDate": null,
  "orderId": "p00998",
  "orderItemList": [
    {
      "quantity": 1,
      "productUrl": "/cti/api/product/P20241127164557",
      "unitPrice": 15
    },
    {
      "quantity": 1,
      "productUrl": "/cti/api/product/P20241dd3",
      "unitPrice": 20
    }
  ],
  "publicNotes": null,
  "privateNotes": null,
  "orderRoleList": [],
  "contentList": [],
  "orderTypeId": "PURCHASE_ORDER",
  "orderUrl": "/cti/api/order/p00998",
  "orderHistoryListUrl": "/cti/api/order/p00998/history/",
  "quoteUrlList": null,
  "invoiceUrlList": null,
  "shipmentUrlList": [
    "/cti/api/shipment/328967"
  ],
  "returnUrlList": null,
  "varianceUrlList": null,
  "productPriceTypeId": null,
  "settlementTermId": null,
  "fulfillmentId": null,
  "requestedShippingServiceId": null,
  "saleSourceId": null,
  "referenceNumber": null,
  "batchUrl": null,
  "batchId": null,
  "statusId": "ORDER_CREATED",
  "userFieldDataList": [],
  "invoiceList": null,
  "shipmentList": null,
  "createdDate": "2024-11-28T05:16:14",
  "lastUpdatedDate": "2024-11-28T05:16:14",
  "shipDate": null,
  "connectionRelationUrlList": [],
  "processingFacilityUrl": null,
  "sessionSecret": "NX3Hh9FyXUgpuaZ4QGHh"
}
*/

echo date("Y-m-d H:i:s");
echo "<br>";
///////////////////////////////////// Add Location In Finale START /////////////////////////////////
$productId = "P20241203165533P20241203165533P2202" . date("YmdHis");
// $productId = "P20241203165533P20241203165533P220220241205113101";
$data = [
    "productId"         => $productId,
    "internalName"      => "internalName " . date("YmdHis"),
    "longDescription"   => "longDescription " . date("YmdHis")
];
// Sub Location
$productUrl = addSetupInFinale("/cti/api/product/", $productId, "productId", "productUrl", $data);
///////////////////////////////////// Add Location In Finale END /////////////////////////////////

echo "Product Added productId: " . $productId;
echo "<br> productUrl: " . $productUrl;
echo "<br><br>";

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////Example for creating a lookup with the product ID ////////////////////////////////////////////
$apiUrl = "https://app.finaleinventory.com/cti/api/scanlookup";
$serial_no = "S" . date('YmdHis');
$serial_no = "S20241205113213";
$data1 = [
    "scanKey"       => $serial_no,
    "productUrl"    => $productUrl,
    "lotId"         => $serial_no,
    "scanTypeId"    => "UNSPECIFIED_TEXT"
];
$response = sendPostRequestFinale($data1, $apiUrl);
// Handle the response
if (isset($response['scanKey']) && $response['scanKey'] != "") {
    echo "Lookup created successfully. LotID: " . $serial_no;
} else {
    echo "Failed to create lookup. LotID: " . $serial_no;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br>";
echo date("Y-m-d H:i:s");
echo "<br><br>";
die;

// $productId = "P20241202185653";
// $productId = "P20241203165533";
$productId = "P" . date('YmdHis');
$productId = "P20241203165533P20241203165533P2" . date("YmdHis");

$apiUrl = "https://app.finaleinventory.com/cti/api/product/";
$data = [
    "productId"         => $productId,
    "internalName"      => "internalName " . date("YmdHis"),
    "longDescription"   => "longDescription " . date("YmdHis")
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['statusId']) && $response['statusId'] != "") {
    echo "Product Moved to Finale, Product ID: " . $productId;
} else {
    echo $response['msg'];
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<br><br>";
var_dump($response);
echo "<br><br>";

/*
$apiUrl = "https://app.finaleinventory.com/cti/api/product/";
$productId = "P" . date('YmdHis');
// $productId = "P20241202185653";
// $productId = "P20241203165533";
$data = [
    "productId"         => $productId,
    "internalName"      => "internalName " . date("YmdHis"),
    "longDescription"   => "longDescription " . date("YmdHis")
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['statusId']) && $response['statusId'] != "") {
    echo "Product Moved to Finale, Product ID: " . $productId;
} else {
    echo $response['msg'];
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br>";


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////Example for creating a lookup with the product ID ////////////////////////////////////////////
$apiUrl = "https://app.finaleinventory.com/cti/api/scanlookup";
$productUrl = "/cti/api/product/" . $productId;
$serial_no = "S" . date('YmdHis');
$data1 = [
    "scanKey"       => $serial_no,
    "productUrl"    => $productUrl,
    "lotId"         => $serial_no,
    "scanTypeId"    => "UNSPECIFIED_TEXT"
];
$response = sendPostRequestFinale($data1, $apiUrl);
// Handle the response
if (isset($response['scanKey']) && $response['scanKey'] != "") {
    echo "Lookup created successfully. LotID: " . $serial_no;
} else {
    echo "Failed to create lookup. LotID: " . $serial_no;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br>";
*/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Create Purchaes Order ////////////////////////////////////////////////////////////

/*
$apiUrl = "https://app.finaleinventory.com/cti/api/order/";
$orderId = "O" . date('YmdHis'); // $orderId = "O20241127171324";
$data = [
    "orderId"                   => $orderId,
    "orderDate"                 => '2024-11-28T07:00:00.000Z',
    "orderTypeId"               => "PURCHASE_ORDER",
    "statusId"                  => "ORDER_CREATED",
    "orderUrl"                  => "/cti/api/order/" . $orderId,
    "shipmentList"  => [
        ["shipmentTypeId" => "PURCHASE_SHIPMENT"]
    ],
    "orderRoleList"  => [
        ["roleTypeId" => "SUPPLIER", "partyId" => "10198"]
    ]
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['orderUrl']) && $response['orderUrl'] != "") {
    $shipmentUrl = $response['shipmentUrlList'][0];
    echo "Update Order in Finale, OrderID: " . $orderId;
    echo "<br><br>";
}
*/


$orderId = "O20241203184602";
$apiUrl = "https://app.finaleinventory.com/cti/api/order/" . $orderId;
$data = [
    "orderId"                   => $orderId,
    "orderUrl"                  => "/cti/api/order/" . $orderId,
    "orderHistoryListUrl"       => "/cti/api/order/" . $orderId . "/history/",
    "orderItemList"             => [
        [
            "productId" => "P20241203165533",
            "quantity" => 1,
            "productUrl" => "/cti/api/product/P20241203165533",
            "unitPrice" => 10

        ],
        [
            "productId" => "P20241202185653",
            "quantity" => 1,
            "productUrl" => "/cti/api/product/P20241202185653",
            "unitPrice" => 10
        ]
    ]
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['orderUrl']) && $response['orderUrl'] != "") {
    echo "Update Order in Finale, OrderID: " . $orderId;
    echo "<br><br>";

    $shipmentUrl = $response['shipmentUrlList'][0];

    echo $shipmentUrl;
    echo "<br><br>";


    // var_dump($response);
    // echo "<br><br>";

    /// Add Shipment
    $apiUrl = "https://app.finaleinventory.com" . $shipmentUrl;
    $data = [
        "primaryOrderUrl"   => "/cti/api/order/" . $orderId,
        "shipmentIdUser"    => $orderId . "-1",
        "shipmentItemList"  => [
            [
                "productUrl" => "/cti/api/product/P20241203165533",
                "facilityUrl" => "/cti/api/facility/10039",
                "quantity" => 1,
                "lotId" => "L_1-" . date('YmdHis')
            ],
            [
                "productUrl" => "/cti/api/product/P20241202185653",
                "facilityUrl" => "/cti/api/facility/10039",
                "quantity" => 1,
                "lotId" => "L_2-" . date('YmdHis')
            ]
        ],
        "shipmentTypeId" => "PURCHASE_SHIPMENT",
        "shipmentUrl" => $shipmentUrl,
        "statusId" => "SHIPMENT_INPUT"
    ];
    $response = sendPostRequestFinale($data, $apiUrl);
    if (isset($response['shipmentUrl']) && $response['shipmentUrl'] != "") {
        echo "Shipment has been added, shipmentUrl: " . $shipmentUrl;
    } else {
        echo $response['msg'];
    }
    echo "<br><br>";
} else {
    echo $response['msg'];
}
// echo $orderId . "<br><br>"; // print_r($response);
echo "<br><br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////


die;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$apiUrl = "https://app.finaleinventory.com/cti/api/product/";
$productId = "P" . date('YmdHis');
$productId = "P20241202185653";
$data = [
    "productId"         => $productId,
    "internalName"      => "internalName " . date("YmdHis"),
    "longDescription"   => "longDescription " . date("YmdHis")
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['statusId']) && $response['statusId'] != "") {
    echo "Product Moved to Finale, Product ID: " . $productId;
} else {
    echo $response['msg'];
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<br><br>";

die;

///////////////////////////////////// Add Location In Finale START /////////////////////////////////
$setup_name = "loc134455121";
$data = [
    "facilityName" => $setup_name,
    "parentFacilityUrl" => "/cti/api/facility/10037"
];
// Sub Location
echo $finale_location_id = addSetupInFinale("/cti/api/facility/", $setup_name, "facilityName", "facilityId", $data);
///////////////////////////////////// Add Location In Finale END /////////////////////////////////

die;

$setup_name = "abc sup";
$data = [
    "groupName"         => $setup_name,
    "roleTypeIdList"    => ["SUPPLIER"],
    "statusId"          => "PARTY_ENABLED",
    // "contactName"      => $setup_name,
    // "description"       => "description " . date("YmdHis"),
    // "roleTypeIdList"    => "description " . date("YmdHis")
];
$finale_supplier_id = addSetupInFinale("/cti/api/partygroup/", $setup_name, "groupName", "partyId", $data);

die;



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Create Purchaes Order ////////////////////////////////////////////////////////////
$apiUrl = "https://app.finaleinventory.com/cti/api/order/";
$orderId = "O" . date('YmdHis'); // $orderId = "O20241127171324";
$data = [
    "orderId"                   => $orderId,
    "orderDate"                 => '2024-11-28T07:00:00.000Z',
    "orderTypeId"               => "PURCHASE_ORDER",
    "statusId"                  => "ORDER_CREATED",
    "orderUrl"                  => "/cti/api/order/" . $orderId,
    "shipmentList"  => [
        ["shipmentTypeId" => "PURCHASE_SHIPMENT"]
    ],
    "orderItemList"             => [
        [
            "quantity" => 1,
            "productUrl" => "/cti/api/product/" . $productId,
            "unitPrice" => 15
        ]
    ],
    "orderRoleList"  => [
        ["roleTypeId" => "SUPPLIER", "partyId" => "10198"]
    ]
    // "destinationFacilityUrl" => "/cti/api/facility/10000",
    // "orderHistoryListUrl"       => "/cti/api/order/" . $orderId . "history/",
    // "actionUrlCancel"           => "/cti/api/order/" . $orderId . "/cancel",
    // "actionUrlComplete"         => "/cti/api/order/" . $orderId . "/complete",
    // "actionUrlLock"             => "/cti/api/order/" . $orderId . "/lock",
    // "reserveAllUrl"             => "/cti/api/order/" . $orderId . "/reserveall",
];
$response = sendPostRequestFinale($data, $apiUrl);
if (isset($response['orderUrl']) && $response['orderUrl'] != "") {
    $shipmentUrl = $response['shipmentUrlList'][0];

    echo "Created in Finale, OrderID: " . $orderId;
    echo "<br><br>";

    /// Add Shipment
    $apiUrl = "https://app.finaleinventory.com" . $shipmentUrl;
    $data = [
        "primaryOrderUrl"   => "/cti/api/order/" . $orderId,
        "shipmentIdUser"    => $orderId . "-1",
        "shipmentItemList"  => [
            [
                "productUrl" => "/cti/api/product/" . $productId,
                "facilityUrl" => "/cti/api/facility/10039",
                "quantity" => 1,
                "lotId" => "L_1-" . date('YmdHis')
            ]
        ],
        "shipmentTypeId" => "PURCHASE_SHIPMENT",
        "shipmentUrl" => $shipmentUrl,
        "statusId" => "SHIPMENT_INPUT"
    ];
    $response = sendPostRequestFinale($data, $apiUrl);
    if (isset($response['shipmentUrl']) && $response['shipmentUrl'] != "") {
        echo "Shipment has been added, shipmentUrl: " . $shipmentUrl;

        echo "<br><br>";
        /// Receive Shipment
        $apiUrl = $apiUrl . "/receive";
        $data = [
            "receiveDate" => date('Y-m-d') . "T07:00:00.000"
        ];
        $response = sendPostRequestFinale($data, $apiUrl);
        if (isset($response['shipmentId']) && $response['shipmentId'] != "") {
            echo "Shipment has been received";
        } else {
            echo $response['msg'];
        }
    } else {
        echo $response['msg'];
    }

    echo "<br><br>";

    /// Complete PO 
    $apiUrl = "https://app.finaleinventory.com/cti/api/order/" . $orderId . "/lock";
    $data = [];
    $response = sendPostRequestFinale($data, $apiUrl);
    if (isset($response['orderId']) && $response['orderId'] != "") {
        echo "Order Commited";
    } else {
        echo $response['msg'];
    }
    echo "<br><br>";
} else {
    echo $response['msg'];
}
// echo $orderId . "<br><br>"; // print_r($response);
echo "<br><br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
