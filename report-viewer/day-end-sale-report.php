<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$date = isset($_GET['date_input']) && $_GET['date_input'] !== '' ? $_GET['date_input'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($date === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

// Rest of your code goes here...


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$LocationID = 1;
$otherTypesSale = 0;
$dateTime = new DateTime($date);
$formattedDate = $dateTime->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');
$LocationName = $Locations[$LocationID]['location_name'];

$pageTitle = "Day End Sale Report - " . $date;
$reportTitle = "Day End Sale Report";

$creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = 0;

$Locations = GetLocations($link);
$invoiceSales = getInvoicesByDate($link, $date, $location_id);
$receipts =  getReceiptsByDate($link, $date, $location_id);
$CreditCollection =  getReceiptsCollection($link, $date, $location_id);

$returnAmounts = GetUnsettledReturnValuesTotal($link, $date, $date, $location_id);
$refundAmount = GetRefundsByDateRangeTotal($date, $date, $location_id);


if (isset($receipts[0])) {
    $cashReceipts = $receipts[0];
}

if (isset($receipts[1])) {
    $creditCardReceipts = $receipts[1];
}

if (!empty($receipts)) {
    foreach ($receipts as $type => $amount) {
        if ($type == 0 || $type == 1) {
            continue;
        }

        $otherTypesSale += $amount;
    }
}


$dayReceipts = $cashReceipts - $CreditCollection;
$returnAmount = $returnAmounts['return_amount'];
$settledReturnAmount = $returnAmounts['total_settled_amount'];
$unsettled_amount = $returnAmounts['unsettled_amount'];

$creditSales = $invoiceSales - ($dayReceipts + $creditCardReceipts + $settledReturnAmount + $otherTypesSale);
$cashInHand = $cashReceipts - $refundAmount;
$location_name = $Locations[$location_id]['location_name'];

if ($creditSales < 0) {
    $creditSales = 0;
}
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
                <h2 class="report-title-mini"><?= strtoupper($reportTitle) ?></h2>
                <table>
                    <tr>
                        <th>Date</th>
                        <td class="text-end"><?= $formattedDate ?></td>
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
                <tbody>
                    <tr>
                        <th class="text-start">Invoice Sale Summary</th>
                        <th>Amount</th>
                        <th>Total</th>
                    </tr>

                    <tr>
                        <td>Total Invoice Value</td>
                        <td class="text-end"><?= formatAccountBalance($invoiceSales) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Return Settlement Value</td>
                        <td class="text-end"><?= formatAccountBalance(-$settledReturnAmount) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="border-bottom text-end text-bold-extra">Net Day End Sale Value</td>
                        <td class="border-bottom text-end text-bold-extra"><?= formatAccountBalance($invoiceSales - $settledReturnAmount) ?></td>

                    </tr>

                    <tr>
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <th class="text-start">Payment Summary</th>
                        <th>Amount</th>
                        <th>Total</th>
                    </tr>

                    <tr>
                        <td>Cash Sale Value</td>
                        <td class="text-end"><?= formatAccountBalance($dayReceipts) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Credit Card Sale Value</td>
                        <td class="text-end"><?= formatAccountBalance($creditCardReceipts) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Credit Sale Value</td>
                        <td class="text-end"><?= formatAccountBalance($creditSales) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="">Other Sale Value</td>
                        <td class="text-end"><?= formatAccountBalance($otherTypesSale) ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="border-bottom text-end text-bold-extra">Total Sale</td>
                        <td class="border-bottom text-end text-bold-extra"><?= formatAccountBalance($dayReceipts + $creditCardReceipts + $creditSales + $otherTypesSale) ?></td>

                    </tr>


                    <tr>
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <th class="text-start">Collection Summary</th>
                        <th>Amount</th>
                    </tr>

                    <tr>
                        <td>Credit Collection Value</td>
                        <td class="text-end"><?= formatAccountBalance($CreditCollection) ?></td>
                    </tr>


                    <tr>
                        <td>Refund Amount</td>
                        <td class="text-end"><?= formatAccountBalance(-$refundAmount) ?></td>
                    </tr>

                    <tr>
                        <td class="">Unsettled Return Value</td>
                        <td class="text-end"><?= formatAccountBalance(-$unsettled_amount) ?></td>
                    </tr>


                    <tr>
                        <td class="border-bottom">Cash in Hand Value</td>
                        <td class="border-bottom text-end text-bold-extra"><?= formatAccountBalance($cashInHand) ?></td>
                    </tr>



                </tbody>
            </table>
        </div>
        <!-- <div id="container" class="section-4">



            <table>
                <tbody>

                    <tr>
                        <td rowspan="6" class="border-bottom  border-top text-bold-extra"><?= $location_name ?></td>
                        <td class="text-bold-extra  border-top" scope="col">Total Sale</td>
                        <td class="text-bold-extra  border-top" scope="col">Settled Return</td>
                        <td class="text-bold-extra  border-top" scope="col">Unsettled Return</td>
                        <td class="text-bold-extra  border-top" scope="col">Net Sale</td>
                    </tr>

                    <tr>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($invoiceSales) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($settledReturnAmount) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($unsettled_amount) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($invoiceSales - $returnAmount) ?></td>
                    </tr>


                    <tr>
                        <td class="text-bold-extra" scope="col">Cash Sale</td>
                        <td class="text-bold-extra" scope="col">Credit Card Sale</td>
                        <td class="text-bold-extra" scope="col">Credit Sale</td>
                        <td class="text-bold-extra" scope="col">Other Sale</td>
                    </tr>

                    <tr>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($dayReceipts) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($creditCardReceipts) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($creditSales) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($otherTypesSale) ?></td>
                    </tr>


                    <tr>
                        <td class="text-bold-extra" scope="col">Credit Collection</td>
                        <td class="text-bold-extra" scope="col">Refund</td>
                        <td colspan="2" class="text-bold-extra" scope="col">Cash In Hand</td>
                    </tr>

                    <tr>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($CreditCollection) ?></td>
                        <td class="text-end border-bottom" scope="col"><?= formatAccountBalance($refundAmount) ?></td>
                        <td colspan="2" class="text-end border-bottom" scope="col"><?= formatAccountBalance($cashInHand) ?></td>
                    </tr>


                </tbody>
            </table>
        </div> -->

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