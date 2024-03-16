<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : null;
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = $location_id;
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');


$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Credit Sale Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Credit Sale Report";

$subTotal = $discountAmount = $serviceCharge = $grandTotal  = $returnTotal = $totalSettled = $totalBalance =  0;

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
                        <th scope="col">Date</th>
                        <th scope="col">Invoice #</th>
                        <th scope="col">Customer</th>
                        <!-- <th scope="col">Ref #</th> -->
                        <th scope="col">Sub Total</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Charge</th>
                        <th scope="col">Grand Total</th>
                        <th scope="col">Settled</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Agin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Current date
                    $current_date = date("Y-m-d");
                    if (!empty($invoiceSales)) {
                        foreach ($invoiceSales as $selectedArray) {

                            $referenceText = "";
                            $returnSettlement =  GetInvoiceSettlement($selectedArray['invoice_number']);

                            $invoice_date = date("Y-m-d H:i", strtotime($selectedArray['current_time']));
                            $invoice_date = date("Y-m-d", strtotime($selectedArray['current_time']));
                            $ref_hold = $selectedArray['ref_hold'];
                            $CustomerID = $selectedArray['customer_code'];
                            $Customer = GetCustomersByID($link, $CustomerID);


                            // Calculating the difference between the current date and the invoice date
                            $datetime1 = new DateTime($invoice_date);
                            $datetime2 = new DateTime($current_date);
                            $interval = $datetime1->diff($datetime2);
                            $aging_days = $interval->days;

                            if ($ref_hold == '0') {
                                // $referenceText = "Take Away";
                                $referenceText = "Direct";
                            } else if ($ref_hold == '-1') {
                                // $referenceText = "Retail";
                                $referenceText = "Direct";
                            } else if ($ref_hold == '-2') {
                                // $referenceText = "Delivery";
                                $referenceText = "Direct";
                            } else if ($ref_hold == "") {
                                // $referenceText = "None";
                                $referenceText = "Direct";
                            } else {
                                $referenceText = $ref_hold;
                            }

                            $invoice_number = $selectedArray['invoice_number'];
                            $paymentValue = GetReceiptsValueByInvoice($link, $invoice_number);
                            $returnSettlement =  GetInvoiceSettlement($selectedArray['invoice_number']);

                            $invoiceValue = $selectedArray['grand_total'];
                            $settlementAmount = $paymentValue + $returnSettlement;
                            $balanceAmount = $invoiceValue - $settlementAmount;

                            if ($balanceAmount <= 0) {
                                continue;
                            }

                            $subTotal += $selectedArray['inv_amount'];
                            $discountAmount += $selectedArray['discount_amount'];
                            $serviceCharge += $selectedArray['service_charge'];

                            $returnTotal += $returnSettlement;
                            $grandTotal += ($selectedArray['grand_total']);
                            $totalSettled += $settlementAmount;
                            $totalBalance += $balanceAmount;

                    ?>
                            <tr>
                                <td style="white-space: nowrap;"><?= $invoice_date ?></td>
                                <td><?= $selectedArray['invoice_number'] ?></td>
                                <td><?= $Customer['customer_first_name'] ?> <?= $Customer['customer_last_name'] ?></td>
                                <!-- <td><?= $referenceText ?></td> -->
                                <td class="text-end"><?= formatAccountBalance($selectedArray['inv_amount']) ?></td>
                                <td class="text-end"><?= formatAccountBalance($selectedArray['discount_amount']) ?></td>
                                <td class="text-end"><?= formatAccountBalance($selectedArray['service_charge']) ?></td>
                                <td class="text-end"><?= formatAccountBalance($selectedArray['grand_total']) ?></td>
                                <td class="text-end"><?= formatAccountBalance($settlementAmount) ?></td>
                                <td class="text-end"><?= formatAccountBalance($balanceAmount) ?></td>
                                <td class="text-end"><?= $aging_days ?></td>
                            </tr>

                    <?php
                        }
                    }
                    ?>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra" colspan="3"></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($subTotal) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($discountAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($serviceCharge) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($grandTotal) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalSettled) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($totalBalance) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"></td>
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