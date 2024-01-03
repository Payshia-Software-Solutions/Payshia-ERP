<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$rec_number = isset($_GET['rec_number']) && $_GET['rec_number'] !== '' ? $_GET['rec_number'] : null;

// Check if the required parameter is not set or has an empty value
if ($rec_number === null) {
    die("Invalid request. Please provide the 'rec_number' parameter with a non-empty value.");
}
$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];

// Rest of your code goes here...

$selectedArray = GetReceiptByNumber($link, $rec_number)[$rec_number];
$pageTitle = "Receipt  - " . $rec_number;
$reportTitle = "Receipt";

$receiptDate = $selectedArray['current_time'];
$dateTime = new DateTime($receiptDate);
$formattedDate = $dateTime->format('d/m/Y H:i:s');
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
                <h2 class="report-title"><?= strtoupper($reportTitle) ?></h2>
                <table>
                    <tr>
                        <th>Date</th>
                        <td class="text-end"><?= $formattedDate ?></td>
                    </tr>
                    <tr>
                        <th>REC Number</th>
                        <td class="text-end"><?= strtoupper($rec_number) ?></td>
                    </tr>
                </table>
            </div>

        </div>


        <div id="container" class="section-4">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Type</th>
                        <th scope="col">REC #</th>
                        <th scope="col">Ref</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalRecValue = $cashTotal = 0;
                    $rec_date = date("Y-m-d", strtotime($selectedArray['date']));

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
                        <td><?= $rec_date ?></td>
                        <td><?= $PaymentTypes[$recType]['text'] ?></td>
                        <td><?= $rec_number ?></td>
                        <td><?= $ref_id ?></td>
                        <td><?= $customer_id ?> - <?= $customerName ?></td>
                        <td class="text-end"><?= formatAccountBalance($rec_amount) ?></td>

                    </tr>


                    <!-- End of the Items -->
                    <tr>
                        <td colspan="4" class="align-top  border-top add-theme">
                            Comments & Special Instructions
                        </td>
                        <td class="text-bold no-border border-top">Total</td>
                        <td class="text-end footer-border total border-top"><?= number_format($totalRecValue, 2) ?></td>
                    </tr>

                </tbody>
            </table>


        </div>

        <div id="container" class="section-6" style="margin-top: 60px;">
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Checked by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Authorized by</p>
            </div>
            <div class="signature-box">
                <p>..................................</p>
                <p class="text-bold">Received by</p>
            </div>
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