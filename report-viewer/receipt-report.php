<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

?>

<?php

$encryptedData = $_GET['encryptedData'];
$key = $_GET['key'];
$iv = $_GET['iv'];

// URL decode the parameters
$encryptedData = urldecode($encryptedData);
$key = urldecode($key);
$iv = urldecode($iv);

// Convert hex-encoded key to binary
$key = hex2bin($key);


// Convert base64-encoded IV to binary
$iv = base64_decode($iv);

try {
    // Ensure the key is the correct length (32 bytes for a 256-bit key)
    $key = substr($key, 0, 32);

    // Decrypt the data using AES-GCM
    $decryptedData = openssl_decrypt(
        base64_decode($encryptedData),
        'aes-256-gcm',
        $key,
        0,
        $iv
    );

    // Check if decryption was successful before parsing JSON
    if ($decryptedData === false) {
        throw new Exception('Decryption failed');
    }

    // Convert the JSON string to an associative array
    $dataArray = json_decode($decryptedData, true);

    // Check if JSON decoding was successful
    if ($dataArray === null) {
        throw new Exception('JSON decoding failed');
    }

    // Now you can use $dataArray['location_id'], $dataArray['fromQueryDate'], and $dataArray['toQueryDate'] in your PHP logic

    // For demonstration purposes, we're just logging the decoded values
    echo "Decoded Location ID: " . ($dataArray['location_id'] ?? 'Not available') . "<br>";
    echo "Decoded From Date: " . ($dataArray['fromQueryDate'] ?? 'Not available') . "<br>";
    echo "Decoded To Date: " . ($dataArray['toQueryDate'] ?? 'Not available') . "<br>";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . '<br>';
    echo 'Decryption Debug: Key=' . bin2hex($key) . ', IV=' . base64_encode($iv);
}
?>



<?php

// $fromQueryDate = isset($_GET['from-date-input']) && $_GET['from-date-input'] !== '' ? $_GET['from-date-input'] : null;
// $toQueryDate = isset($_GET['to-date-input']) && $_GET['to-date-input'] !== '' ? $_GET['to-date-input'] : null;
// $location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// // Check if the required parameters are not set or have empty values
// if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
//     die("Invalid request. Please provide all required parameters with non-empty values.");
// }

// Rest of your code goes here...


// var_dump($invoiceSales);

$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);
$receipts = GetReceiptsByLocation($link, $fromQueryDate, $toQueryDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];

$PaymentTypes = GetPaymentTypes();


$LocationID = $location_id;
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');


$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Receipt Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Receipt Report";

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = 0;

$invoiceSales = getInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);
$location_name = $Locations[$location_id]['location_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <!-- Favicons -->
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="icon">
    <link href="../assets/images/favicon/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/report-viewer.css">

</head>

<body>
    <div class="invoice">
        <div id="container">
            <div id="left-section">
                <h3 class="company-title"><?= $CompanyInfo['company_name'] ?></h3>
                <p><?= $CompanyInfo['company_address'] ?>, <?= $CompanyInfo['company_address2'] ?></p>
                <p><?= $CompanyInfo['company_city'] ?>, <?= $CompanyInfo['company_postalcode'] ?></p>
                <p>Tel: <?= $CompanyInfo['company_telephone'] ?>/ <?= $CompanyInfo['company_telephone2'] ?></p>
                <p>Email: <?= $CompanyInfo['company_email'] ?></p>
                <p>Web: <?= $CompanyInfo['website'] ?></p>
            </div>

            <div id="right-section">
                <h4 class="report-title-mini"><?= strtoupper($reportTitle) ?></h4>
                <table>
                    <tr>
                        <th>From Date</th>
                        <td class="text-end"><?= $formattedFromQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>To Date</th>
                        <td class="text-end"><?= $formattedToQueryDate ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td class="text-end"><?= $location_name ?></td>
                    </tr>
                </table>
            </div>

        </div>



        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">REC #</th>
                        <th scope="col">Date</th>
                        <th scope="col">Type</th>
                        <th scope="col">Ref</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalRecValue = $cashTotal = $visaTotal = 0;
                    if (!empty($receipts)) {
                        foreach ($receipts as $selectedArray) {
                            $rec_date = date("Y-m-d  H:i", strtotime($selectedArray['current_time']));

                            $ref_id = $selectedArray['ref_id'];
                            $rec_number = $selectedArray['rec_number'];
                            $customer_id = $selectedArray['customer_id'];
                            $rec_amount = $selectedArray['amount'];
                            $recType = $selectedArray['type'];

                            if ($recType == 0) {
                                $cashTotal += $rec_amount;
                            } else  if ($recType == 1) {
                                $visaTotal += $rec_amount;
                            }

                            $totalRecValue += $rec_amount;
                            $customerName = GetCustomerName($link, $customer_id);
                    ?>
                            <tr>
                                <td><?= $rec_number ?></td>
                                <td><?= $rec_date ?></td>
                                <td><?= $PaymentTypes[$recType]['text'] ?></td>
                                <td><?= $ref_id ?></td>
                                <td><?= $customer_id ?> - <?= $customerName ?></td>
                                <td class="text-end"><?= formatAccountBalance($rec_amount) ?></td>

                            </tr>

                    <?php
                        }
                    }
                    ?>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Cash Total</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cashTotal) ?></td>
                    </tr>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Visa/Master Total</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($visaTotal) ?></td>
                    </tr>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="5">Total</td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalRecValue) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <script>
            window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>