<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/finance-functions.php';
include '../include/reporting-functions.php';


$Locations = GetLocations($link);
$CompanyInfo = GetCompanyInfo($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

$balanceForward = 0;

$generateDAte = new DateTime();
$reportDate = $generateDAte->format('d/m/Y H:i:s');

$fromQueryDate = isset($_GET['fromQueryDate']) && $_GET['fromQueryDate'] !== '' ? $_GET['fromQueryDate'] : null;
$toQueryDate = isset($_GET['toQueryDate']) && $_GET['toQueryDate'] !== '' ? $_GET['toQueryDate'] : null;
$customerId = isset($_GET['customerId']) && $_GET['customerId'] !== '' ? $_GET['customerId'] : null;

$Customer = GetCustomersByID($link, $customerId);
$fromDate = new DateTime($fromQueryDate);
$formattedFromQueryDate = $fromDate->format('d/m/Y');

$toDate = new DateTime($toQueryDate);
$formattedToQueryDate = $toDate->format('d/m/Y');


$pageTitle = "Customer Statement - " . $Customer['customer_first_name'] . " " . $Customer['customer_last_name'] . " - " . $fromQueryDate . " - " . $toQueryDate;
$reportTitle = "Customer Statement";

$customerStatementArray = CustomerStatement($customerId, $link, $fromQueryDate, $toQueryDate);
$balanceForwardArray = getPreviousBalanceForward($customerId, $link, $fromQueryDate);

$forwardDebitAmount = $balanceForwardArray['Total_Invoice'];
$forwardCreditAmount = $balanceForwardArray['Total_Receipt'] + $balanceForwardArray['Total_Return'];
$balanceForward = $forwardDebitAmount - $forwardCreditAmount;

$PaymentTypes = GetPaymentTypes();
$receipts = GetReceipts($link);
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
                        <th>Customer</th>
                        <td class="text-end"><?= $Customer['customer_first_name'] ?> <?= $Customer['customer_last_name'] ?></td>
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
                        <th scope="col">Reference</th>
                        <th scope="col">Debit</th>
                        <th scope="col">Credit</th>
                        <th scope="col">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="text-end text-bold-extra">B/F</td>
                        <td class=" text-end text-bold-extra"><?= number_format($forwardDebitAmount, 2) ?></td>
                        <td class="text-end text-bold-extra"><?= number_format($forwardCreditAmount, 2) ?></td>
                        <td class="text-end text-bold-extra"><?= number_format($balanceForward, 2) ?></td>
                    </tr>
                    <?php
                    if (!empty($customerStatementArray)) {
                        foreach ($customerStatementArray as $selectedArray) {

                            $debitAmount = $creditAmount =  0;
                            $transactionType = $selectedArray['Transaction_Type'];
                            $transactionDate = date("Y-m-d", strtotime($selectedArray['Transaction_Date']));

                            if ($transactionType == 'Invoice') {
                                $debitAmount = $selectedArray['Amount'];
                                $balanceForward += $debitAmount;
                            } else {
                                $creditAmount = $selectedArray['Amount'];
                                $balanceForward -= $creditAmount;
                            }

                            $forwardDebitAmount += $debitAmount;
                            $forwardCreditAmount += $creditAmount;
                            $recordLocation = $Locations[$selectedArray['Location_ID']]['location_name'];

                            if ($transactionType == "Receipt") {
                                $recNumber = $selectedArray['Reference_Number'];
                                $receiptInfo = $receipts[$recNumber];
                                $referenceText = $PaymentTypes[$receiptInfo['type']]['text'] . " " . $transactionType;
                            } else {
                                $referenceText = $transactionType;
                            }
                    ?>
                            <tr>
                                <td><?= $transactionDate ?></td>
                                <td><?= $referenceText ?> - <?= $selectedArray['Reference_Number'] ?> @ <?= $recordLocation ?></td>

                                <td class="text-end"><?= number_format($debitAmount, 2) ?></td>
                                <td class="text-end"><?= number_format($creditAmount, 2) ?></td>
                                <td class="text-end"><?= number_format($balanceForward, 2) ?></td>
                            </tr>
                    <?php
                        }
                    } ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="border-bottom text-end text-bold-extra">B/F</td>
                        <td class="border-bottom text-end text-bold-extra"><?= number_format($forwardDebitAmount, 2) ?></td>
                        <td class="border-bottom text-end text-bold-extra"><?= number_format($forwardCreditAmount, 2) ?></td>
                        <td class="border-bottom text-end text-bold-extra"><?= number_format($balanceForward, 2) ?></td>
                    </tr>
                </tfoot>

            </table>