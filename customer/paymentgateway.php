<?php
if ($_POST) {
    $price = $_POST['price'] * 100; 
    $api_key = "sk_test_Rbp7F9Y6dvwb6KCc7er9PgGd"; 
    $auth_header = "Authorization: Basic " . base64_encode(string: $api_key . ":");

    $curl = curl_init();

    curl_setopt_array($curl, options: [
        CURLOPT_URL => "https://api.paymongo.com/v1/links",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(value: [
            'data' => [
                'attributes' => [
                    'amount' => $price,
                    'description' => 'Online Payment'
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            $auth_header, 
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $data = json_decode($response, true);

    if ($http_status !== 200) {
        echo "<h3>PayMongo API Error (HTTP Status: $http_status)</h3>";
        echo "<pre>" . print_r($data, true) . "</pre>";
        exit();
    }

    if (isset($data['data']['attributes']['checkout_url'])) {
        $checkout_url = $data['data']['attributes']['checkout_url'];
    } else {
        die("Error: Could not retrieve payment URL. <pre>" . print_r($data, true) . "</pre>");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment...</title>
    <script>
        setTimeout(function () {
            window.location.href = "<?= isset($checkout_url) ? $checkout_url : 'error.php' ?>";
        }, 2000); 
    </script>
</head>
<body>
    <h3>Redirecting to the payment page...</h3>
</body>
</html>
