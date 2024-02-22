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

$pageTitle = "Date Wise Sale Summary Report - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Date Wise Sale Summary";

$cumulativeInvoiceAmount = $cumulativeDiscountAmount = $cumulativeServiceCharge = $cumulativeSaleAmount  = $cumulativeInvoiceCount = $cumulativeReturn = $cumulativeSubTotalAmount = $cumulativeUnSettledReturn = $cumulativeCashSale = $cumulativeCreditSale = $cumulativeOtherSale = $cumulativeCreditCollection = $cumulativeSettledReturn  = $cumulativeCardSale = 0;

$invoiceSales = GetDateWiseSaleReport($fromQueryDate, $toQueryDate, $location_id);
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
    <style>
        @media print {

            /* Ensure landscape orientation for printing */
            @page {
                size: A4 landscape;
            }
        }
    </style>

</head>

<body class="landscape">
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
                        <th scope="col">PCs</th>
                        <th scope="col">Invoice Value</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Charge</th>
                        <th scope="col">Settled Return</th>
                        <th scope="col">Total Sale</th>
                        <th scope="col">Cash</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Card Payment</th>
                        <th scope="col">Other</th>
                        <th scope="col">Credit Collection</th>
                        <th scope="col">Unsettled Return</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($invoiceSales)) {
                        foreach ($invoiceSales as $selectedArray) {

                            $invoice_date = $selectedArray['date'];
                            $totalSubTotalAmount = $selectedArray['total_invoice_amount'];
                            $totalInvoiceCount = $selectedArray['total_invoices'];
                            $totalSaleAmount = $selectedArray['total_sales_amount'];
                            $totalDiscountAmount = $selectedArray['total_discount_amount'];
                            $totalServiceCharge = $selectedArray['total_service_charge'];

                            $creditCardReceipts = $cashReceipts = $creditSales = $invoiceSales = $refundAmount = $cashInHand = $otherTypesSale = 0;
                            $receipts =  getReceiptsByDate($link, $invoice_date, $location_id);
                            $CreditCollection =  getReceiptsCollection($link, $invoice_date, $location_id);
                            $returnAmounts = GetUnsettledReturnValuesTotal($link, $invoice_date, $invoice_date, $location_id);

                            $receiptsInfo = getReceiptsByDateRangeByType($link, $invoice_date, $invoice_date, $location_id);


                            $inRangeCashReceipts = $AllCashReceipts = $inRangeCashCollection = 0;
                            $inRangeCardReceipts = $AllCardReceipts = $inRangeCardCollection = 0;
                            if (isset($receiptsInfo['Cash'])) {
                                $inRangeCashReceipts = $receiptsInfo['Cash']['inRangeReceipts'];
                                $AllCashReceipts = $receiptsInfo['Cash']['AllReceipts'];
                                $inRangeCashCollection = $AllCashReceipts - $inRangeCashReceipts;
                            }

                            if (isset($receiptsInfo['Visa/Master'])) {
                                $inRangeCardReceipts = $receiptsInfo['Visa/Master']['inRangeReceipts'];
                                $AllCardReceipts = $receiptsInfo['Visa/Master']['AllReceipts'];
                                $inRangeCardCollection = $AllCashReceipts - $inRangeCashReceipts;
                            }

                            $inRangeOtherReceipts = $AllOtherReceipts = 0;

                            if (!empty($receiptsInfo)) {
                                foreach ($receiptsInfo as $receiptArray) {
                                    $paymentType = $receiptArray['PaymentType'];
                                    if ($paymentType === "Cash" || $paymentType === "Visa/Master") {
                                        continue;
                                    }

                                    $inRangeOtherReceipts += $receiptArray['inRangeReceipts'];
                                    $AllOtherReceipts += $receiptArray['AllReceipts'];
                                }
                            }



                            $inRangeOtherCollection = $AllOtherReceipts - $inRangeOtherReceipts;
                            $totalReceipts = $AllCashReceipts + $creditCardReceipts + $otherTypesSale;
                            $CreditCollection = $inRangeCardCollection + $inRangeCashCollection + $inRangeOtherCollection;
                            $dayReceipts = $inRangeCardReceipts + $inRangeOtherReceipts + $inRangeCashReceipts;

                            $returnAmount = $returnAmounts['return_amount'];
                            $settledReturnAmount = $returnAmounts['total_settled_amount'];
                            $unsettled_amount = $returnAmounts['unsettled_amount'];

                            $totalSaleAmount = $selectedArray['total_sales_amount'] - $settledReturnAmount;
                            $creditSales = $selectedArray['total_sales_amount'] - ($dayReceipts + $settledReturnAmount);
                            $cashInHand = $AllCashReceipts - $refundAmount;


                            $cumulativeInvoiceCount += $totalInvoiceCount;
                            $cumulativeSubTotalAmount += $totalSubTotalAmount;
                            $cumulativeDiscountAmount += $totalDiscountAmount;
                            $cumulativeServiceCharge += $totalServiceCharge;
                            $cumulativeSaleAmount += $totalSaleAmount;
                            $cumulativeSettledReturn += $settledReturnAmount;
                            $cumulativeUnSettledReturn += $unsettled_amount;

                            $cumulativeCashSale += $dayReceipts;
                            $cumulativeCreditSale += $creditSales;
                            $cumulativeCardSale += $creditCardReceipts;
                            $cumulativeOtherSale += $otherTypesSale;
                            $cumulativeCreditCollection += $CreditCollection;

                            if ($totalInvoiceCount != 0 || $totalSubTotalAmount != 0 || $totalDiscountAmount != 0 || $totalServiceCharge != 0 || $settledReturnAmount != 0 || $totalSaleAmount != 0 || $inRangeCashReceipts != 0 || $creditSales != 0 || $inRangeCardReceipts != 0 || $inRangeOtherReceipts != 0 || $CreditCollection != 0 || $unsettled_amount != 0) :

                    ?>

                                <tr>

                                    <td><?= $invoice_date ?></td>
                                    <td class="text-end"><?= formatAccountBalance($totalInvoiceCount) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($totalSubTotalAmount) ?></td>
                                    <td class="text-end"><?= formatAccountBalance(-$totalDiscountAmount) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($totalServiceCharge) ?></td>
                                    <td class="text-end"><?= formatAccountBalance(-$settledReturnAmount) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($totalSaleAmount) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($inRangeCashReceipts) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($creditSales) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($inRangeCardReceipts) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($inRangeOtherReceipts) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($CreditCollection) ?></td>
                                    <td class="text-end"><?= formatAccountBalance($unsettled_amount) ?></td>
                                </tr>
                    <?php endif;
                        }
                    }
                    ?>
                    <tr>
                        <td scope="col" class="text-end border-bottom text-bold-extra"></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeInvoiceCount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeSubTotalAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeDiscountAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeServiceCharge) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance(-$cumulativeSettledReturn) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeSaleAmount) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeCashSale) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeCreditSale) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeCardSale) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeOtherSale) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeCreditCollection) ?></td>
                        <td scope="col" class="text-end border-bottom text-bold-extra"><?= formatAccountBalance($cumulativeUnSettledReturn) ?></td>
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