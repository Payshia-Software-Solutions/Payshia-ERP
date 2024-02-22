<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';

$CompanyInfo = GetCompanyInfo($link);
$Units = GetUnit($link);
$Products = GetProducts($link);
$Locations = GetLocations($link);
$ExpensesTypes =  GetExpensesTypes();
$stockSellingValue = $totalStockValue = $totalEndingStockValue = 0;

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : null;
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : null;
$location_id = isset($_GET['location_id']) && $_GET['location_id'] !== '' ? $_GET['location_id'] : null;

// Check if the required parameters are not set or have empty values
if ($fromQueryDate === null || $toQueryDate === null || $location_id === null) {
    die("Invalid request. Please provide all required parameters with non-empty values.");
}

$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');

$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');


$locationName = $Locations[$location_id]['location_name'];
$pageTitle = "Profit & Loss Statement - " . $locationName . " - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "P&L Statement";


$revenueDetails = getRevenueInfoByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);
$grnDetails = GetGRNTotalByRange($fromQueryDate, $toQueryDate, $location_id);
$expensesListTotal = GetExpensesListTotal($fromQueryDate, $toQueryDate, $location_id);
$creditSalesInfo = getCreditInvoicesByDateRangeAll($link, $fromQueryDate, $toQueryDate, $location_id);
$salesReturn = GetReturnValueByRange($link, $fromQueryDate, $toQueryDate, $location_id);
$receiptsInfo = getReceiptsByDateRange($link, $fromQueryDate, $toQueryDate, $location_id);


$revenue = $revenueDetails['revenue'];
$salesDiscounts = $revenueDetails['total_discount'];
$creditSales = $creditSalesInfo['totalCreditSales'];
$inRangeReceiptValue = $receiptsInfo['inRangeReceipts'];
$creditCollection = $receiptsInfo['AllReceipts'] - $inRangeReceiptValue;
$totalRevenue = $revenue - $salesDiscounts - $salesReturn - $creditSales + $creditCollection;

// Get Beginning Inventory Value
$StartStockBalances = GetStockBalancesByLocationToDate($link, $location_id, $fromQueryDate);
if (!empty($StartStockBalances)) {
    foreach ($StartStockBalances as $product_id => $stockBalance) {
        $stockValue = $stockBalance * $Products[$product_id]['cost_price'];
        $totalStockValue += $stockValue;
    }
}
$beginningInventory = $totalStockValue;

// Get Closing Inventory Value
$EndingStockBalances = GetStockBalancesByLocationToDate($link, $location_id, $toQueryDate);
if (!empty($EndingStockBalances)) {
    foreach ($EndingStockBalances as $product_id => $stockBalance) {
        $stockValue = $stockBalance * $Products[$product_id]['cost_price'];
        $totalEndingStockValue += $stockValue;
    }
}
$endingInventory = $totalEndingStockValue;


$purchaseValue = $grnDetails['total_grn_value'];
$purchaseReturn = 0;
$purchaseDiscounts = 0;
$totalCOGS = $beginningInventory + $purchaseValue - $purchaseDiscounts - $purchaseReturn - $endingInventory;
$grossProfitLoss = $totalRevenue - $totalCOGS;

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
        .add-bold-text {
            font-weight: 800;
            font-size: 18px;
        }
    </style>
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
                <h5 class="report-title-mini"><?= strtoupper($reportTitle) ?></h5>
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
                        <th>Location Name</th>
                        <td class="text-end"><?= $locationName ?></td>
                    </tr>
                </table>
            </div>

        </div>

        <p style="font-weight:600;margin-top:10px; margin-bottom:0px">Report is generated on <?= $reportDate ?></p>
        <div id="container" class="section-3">
            <table style="margin-top: 5px;">
                <tbody>
                    <tr>
                        <th colspan="3" style="font-size: 20px; font-weight:700">Revenue</th>
                    </tr>
                    <tr>
                        <td>Revenue</td>
                        <td class="text-end"><?= formatAccountBalance($revenue) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td>Sales Discounts</td>
                        <td class="text-end"><?= formatAccountBalance(-$salesDiscounts) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td>Sales Return</td>
                        <td class="text-end"><?= formatAccountBalance(-$salesReturn) ?></td>
                        <td class="text-end"></td>
                    </tr>

                    <tr>
                        <td>Credit Sales</td>
                        <td class="text-end"><?= formatAccountBalance(-$creditSales) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td>Credit Collection</td>
                        <td class="text-end"><?= formatAccountBalance($creditCollection) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td class="text-bold-extra ">Total Revenue</td>
                        <td class="text-end"></td>
                        <td class="text-end text-bold-extra "><?= formatAccountBalance($totalRevenue) ?></td>
                    </tr>


                    <tr>
                        <th colspan="3" style="font-size: 20px; font-weight:700">Cost of Sales</th>
                    </tr>
                    <tr>
                        <td>Beginning Inventory</td>
                        <td class="text-end"><?= formatAccountBalance($beginningInventory) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td>Purchase Value</td>
                        <td class="text-end"><?= formatAccountBalance($purchaseValue) ?></td>
                        <td class="text-end"></td>
                    </tr>

                    <tr>
                        <td>Purchase Discounts</td>
                        <td class="text-end"><?= formatAccountBalance(-$purchaseDiscounts) ?></td>
                        <td class="text-end"></td>
                    </tr>

                    <tr>
                        <td>Purchase Returns</td>
                        <td class="text-end"><?= formatAccountBalance(-$purchaseReturn) ?></td>
                        <td class="text-end"></td>
                    </tr>

                    <tr>
                        <td>Ending Inventory</td>
                        <td class="text-end"><?= formatAccountBalance(-$endingInventory) ?></td>
                        <td class="text-end"></td>
                    </tr>
                    <tr>
                        <td class="text-bold-extra ">Total COGS</td>
                        <td class="text-end"></td>
                        <td class="text-end text-bold-extra "><?= formatAccountBalance($totalCOGS) ?></td>
                    </tr>

                    <tr>
                        <td class="text-bold-extra add-theme add-bold-text">Gross Profit/Loss</td>
                        <td class="text-end add-theme"></td>
                        <td class="text-end add-theme add-bold-text"><?= formatAccountBalance($grossProfitLoss) ?></td>
                    </tr>



                    <tr>
                        <th colspan="3" style="font-size: 20px; font-weight:700">Expenses</th>
                    </tr>
                    <?php
                    $totalExpenses = 0;
                    if (!empty($ExpensesTypes)) {
                        foreach ($ExpensesTypes as $expenseType) {
                            $expenseAmount = 0;
                            $expenseTypeId = $expenseType['id'];
                            if (isset($expensesListTotal[$expenseTypeId])) {
                                $expenseAmount = $expensesListTotal[$expenseTypeId]['expense_amount'];
                            }

                            $totalExpenses += $expenseAmount;

                    ?>
                            <tr>
                                <td><?= $expenseType['type'] ?></td>
                                <td class="text-end"><?= formatAccountBalance(-$expenseAmount) ?></td>
                                <td class="text-end"></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                    <tr>
                        <td class="text-bold-extra ">Total Expenses</td>
                        <td class="text-end"></td>
                        <td class="text-end text-bold-extra "><?= formatAccountBalance(-$totalExpenses) ?></td>
                    </tr>


                    <tr>
                        <td class="text-bold-extra add-theme add-bold-text">Net Profit/Loss</td>
                        <td class="text-end add-theme"></td>
                        <td class="text-end add-theme add-bold-text"><?= formatAccountBalance($grossProfitLoss - $totalExpenses) ?></td>
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
            // window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };
        </script>
    </div>

</body>

</html>