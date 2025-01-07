 

 <?php
    die;
    require 'vendor/autoload.php';

    use Google\Client;
    use Google\Service\Directory;

    $client = new Client();
    $client->setAuthConfig('new_cit-albert-diagnostic-fb7cade528c4.json');
    $client->addScope('https://www.googleapis.com/auth/admin.directory.device.chromeos.readonly');
    $client->setSubject('aftab@amizltd.com'); // Replace with a super admin's email
    // // Fetch Devices
    $service = new Directory($client);
    $results = $service->chromeosdevices->listChromeosdevices('C03254nfs');
    var_dump($results);
    foreach ($results->getChromeosdevices() as $device) {
        echo "Serial Number: " . $device->getSerialNumber();
        echo "Model: " . $device->getModel();
    }

    /*

 
        die;



        $apiKey = 'AIzaSyCSTQ2JxQThMLr4MsEKNZFDxdasA_pgMaE';
        $adminUrl = 'https://www.googleapis.com/admin/directory/v1/customer/my_customer/devices/chromeos?fields=devices(deviceId,serialNumber)';

        // Make a GET request to the Admin SDK API
        $response = file_get_contents($adminUrl . '&key=' . $apiKey);

        // Decode JSON response
        $data = json_decode($response, true);

        // Display the results
        print_r($data);
        

        die;
        
        $apiKey = 'AIzaSyCSTQ2JxQThMLr4MsEKNZFDxdasA_pgMaE'; // Replace with your API key
        $customerID = 'C03254nfs'; // Replace with your customer ID

        // Assuming you have already obtained an OAuth 2.0 access token
        $accessToken = $apiKey; // Replace with your OAuth token

        $url = "https://admin.googleapis.com/admin/directory/v1/customer/$customerID/devices/chromeos";

        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode == 200) {
                $data = json_decode($response, true);
                if (isset($data['chromeosdevices']) && is_array($data['chromeosdevices'])) {
                    foreach ($data['chromeosdevices'] as $device) {
                        echo "Serial Number: " . htmlspecialchars($device['serialNumber']) . "<br>";
                    }
                } else {
                    echo "No ChromeOS devices found or invalid response format.";
                }
            } else {
                echo "Error: HTTP status code $httpCode.";
            }
        }

        curl_close($ch); 

        die;

        $apiKey = 'AIzaSyCSTQ2JxQThMLr4MsEKNZFDxdasA_pgMaE'; // Replace with your API key

        $customerID = 'C03254nfs'; // Replace with your customer ID
        $url = "https://admin.googleapis.com/admin/directory/v1/customer/$customerID/devices/chromeos";

        $headers = [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        print_r($data);
        var_dump($data);
        foreach ($data['chromeosdevices'] as $device) {
            echo "Serial Number: " . $device['serialNumber'] . "<br>";
        }


        die;

        $apiKey = 'AIzaSyAn-gcuX33XHKuQW4bgzyTDG939jb5Z8hc'; // Replace with your API key
        $url = "https://admin.googleapis.com/admin/directory/v1/customers/my_customer";

        $headers = [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (isset($data['id'])) {
            echo "Customer ID: " . $data['id'];
        } else {
            echo "Failed to retrieve Customer ID. Check your API key and permissions.";
        }





        die;
        // For Windows
        $serialNumber = shell_exec('wmic bios get serialnumber');
        echo "Device Serial Number: " . trim($serialNumber);

        echo "<br><br>";

        // For Linux
        $serialNumber = shell_exec('dmidecode -s system-serial-number');
        echo "Device Serial Number: " . trim($serialNumber);

        die;
        echo "<br><br>";
        // Test if shell_exec is disabled
        if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', ini_get('disable_functions')))) {
            echo "shell_exec is enabled.<br>";

            // Test a simple shell command
            $output = shell_exec('echo Hello, World!');
            if ($output) {
                echo "Output from shell_exec: " . htmlspecialchars($output);
            } else {
                echo "shell_exec is enabled, but the command did not produce output.";
            }
        } else {
            echo "shell_exec is disabled on this server.";
        }

    */
