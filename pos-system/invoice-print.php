<?php
require_once('../include/config.php');
include '../include/function-update.php';

include '../include/settings_functions.php';

$netTotal = $total = 0;
$invoice_number = $_GET['invoice_number'];
$PrinterName = $_GET['PrinterName'];

$reprintStatus = $_GET['reprintStatus'];
$titleSuffix = "";
if ($reprintStatus == 1) {
    $titleSuffix = " - REPRINT";
}

$SelectedArray = GetInvoices($link)[$invoice_number];
$InvProducts = GetInvoiceItems($link, $invoice_number);
$Products = GetProducts($link);
$Units = GetUnit($link);

$barcode = GenerateHighResolutionBarcode($invoice_number);
$textArray = [
    "invoice" => ["EN" => "Invoice", "SI" => "ඉන්වොයිසිය"],
    "invoice_number" => ["EN" => "Invoice #", "SI" => "ඉන්වොයිසි අංකය"],
    "customer" => ["EN" => "Customer", "SI" => "පාරිභෝගිකයා"],
    "date" => ["EN" => "Date", "SI" => "දිනය"],
    "cashier" => ["EN" => "Cashier", "SI" => "අයකැමි"],
    "table" => ["EN" => "Table", "SI" => "මේසය"],
    "no_of_items" => ["EN" => "No of Items", "SI" => "අයිතම ගණන"],
    "gross_total" => ["EN" => "Gross Total", "SI" => "දළ එකතුව"],
    "discount" => ["EN" => "Discount", "SI" => "වට්ටම"],
    "sub_total" => ["EN" => "Sub Total", "SI" => "උප එකතුව"],
    "charge_amount" => ["EN" => "Service Charge", "SI" => "සේවා ගාස්තුව"],
    "payable_amount" => ["EN" => "Payable Amount", "SI" => "ගෙවිය යුතු මුදල"],
    "change" => ["EN" => "Change", "SI" => "ඉතිරි මුදල"],
    "qty" => ["EN" => "Qty", "SI" => "ප්‍රමාණය"],
    "unit_price" => ["EN" => "Unit Price", "SI" => "එකක මිල"],
    "amount" => ["EN" => "Amount", "SI" => "මුදල"],
    "greeting" => ["EN" => "Thank You..! Come Again", "SI" => "ස්තූතියි! නැවත එන්න.."]
];


$inv_time = date("Y-m-d H:i:s", strtotime($SelectedArray['current_time']));
$TableID = $SelectedArray['table_id'];
if ($TableID == 0) {
    $TableName = "Take Away";
}
if ($TableID == -1) {
    $TableName = "Retail";
} else if ($TableID == -2) {
    $TableName = "Delivery";
} else if ($TableID == -3) {
    $TableName = "Delivery";
} else {
    $TableName = GetTables($link)[$SelectedArray['table_id']]['table_name'];
}

$service_charge = $SelectedArray['service_charge'];
$discountPercentage = $SelectedArray['discount_percentage'];
$close_type = $SelectedArray['close_type'];
$tendered_amount = $SelectedArray['tendered_amount'];
$InvoiceNumber = $SelectedArray['invoice_number'];
$LocationName = GetLocations($link)[$SelectedArray['location_id']]['location_name'];
$invAmount = $SelectedArray['inv_amount'];
$grand_total = $SelectedArray['grand_total'];
$invAmount = $SelectedArray['inv_amount'];
// echo $invAmount;
$customer_code = $SelectedArray['customer_code'];
$invoice_status = $SelectedArray['invoice_status'];
$discountAmount = $grand_total * ($discountPercentage / 100);
$netTotal = $grand_total - $discountAmount;

$CustomerName = GetCustomerName($link, $customer_code);
$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];
$selectedLocation =  GetLocations($link)[$SelectedArray['location_id']];


$created_by =  $SelectedArray['created_by'];
if (!empty($created_by)) {
    $LoggedStudent = GetAccounts($link)[$created_by];
    $LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
} else {
    $LoggedName = "Unknown";
}

$LanguageMode = GetSetting($link, $SelectedArray['location_id'], 'invoiceLang');
if ($LanguageMode == "EN") {
    $fontClass = '';
} else if ($LanguageMode == "SI") {
    $fontClass = 'style=" font-family: \'Noto Serif Sinhala\', serif !important; font-weight:700;"';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/print-invoice-1.0.css" />
    <title><?= $InvoiceNumber ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Sinhala:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@200;300&display=swap');
    </style>

</head>

<body>


    <div class="inv" id="inv">
        <div class="logo-box" style="margin-bottom: 20px;">
            <img class="logo-image" src="./assets/images/<?= $selectedLocation['logo_path'] ?>">
        </div>

        <p class="address">#<?= $selectedLocation['address_line1'] ?>, <?= $selectedLocation['address_line2'] ?>, <?= $selectedLocation['city'] ?></p>
        <p class="telephone">Tel : <?= $selectedLocation['phone_1'] ?> / <?= $selectedLocation['phone_2'] ?></p>
        <p class="telephone">Email : info@transitaradhana.com</p>
        <hr />


        <h2 class="company" <?= $fontClass ?>><?= $textArray['invoice'][$LanguageMode] ?><?= $titleSuffix ?></h2>

        <div class="InvoiceID" <?= $fontClass ?>><?= $textArray['invoice_number'][$LanguageMode] ?> : <span class="invoice_number"><?php echo $InvoiceNumber; ?></span></div>
        <div class="Customer" <?= $fontClass ?>><?= $textArray['customer'][$LanguageMode] ?> : <span class="cus_name"><?php echo $CustomerName; ?></span></div>
        <div class="dateContainer" <?= $fontClass ?>><?= $textArray['date'][$LanguageMode] ?> : <span class="date"><?php echo $inv_time; ?></span></div>
        <div class="Customer" <?= $fontClass ?>><?= $textArray['cashier'][$LanguageMode] ?> : <span class="cus_name"><?= $LoggedName ?></span></div>
        <div class="Customer" <?= $fontClass ?>><?= $textArray['table'][$LanguageMode] ?> : <span class="cus_name"><?= $TableName ?></span></div>
        <hr />

        <table>
            <thead>
                <tr>
                    <th class="headerth" <?= $fontClass ?>><?= $textArray['qty'][$LanguageMode] ?></th>
                    <th class="headerth" <?= $fontClass ?>><?= $textArray['unit_price'][$LanguageMode] ?></th>
                    <th class="headerth" <?= $fontClass ?>><?= $textArray['amount'][$LanguageMode] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($InvProducts)) {
                    foreach ($InvProducts as $SelectRecord) {
                        $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                        $print_name = $Products[$SelectRecord['product_id']]['print_name'];
                        $name_si = $Products[$SelectRecord['product_id']]['name_si'];
                        $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                        $selling_price = $SelectRecord['item_price'];
                        $item_quantity = $SelectRecord['quantity'];
                        $item_discount = $SelectRecord['item_discount'];
                        $product_id = $SelectRecord['product_id'];

                        $line_total = ($selling_price - $item_discount) * $item_quantity;
                        $total += $line_total;

                        if ($LanguageMode == "SI") {
                            $print_name = $name_si;
                        }
                ?>
                        <tr>
                            <td colspan="3" <?= $fontClass ?>><?= $print_name; ?></td>
                        </tr>
                        <tr class="selected" <?= $fontClass ?>>
                            <td <?= $fontClass ?>><?php echo $item_quantity; ?></td>
                            <td class="text-right" <?= $fontClass ?>><?php echo number_format($selling_price, 2); ?></td>
                            <td class="text-right" <?= $fontClass ?>><?php echo number_format($line_total, 2); ?></td>
                        </tr>

                <?php
                    }
                }
                ?>


            </tbody>
        </table>
        <hr />
        <table class="totals">
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $textArray['no_of_items'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?= count($InvProducts) ?></th>
            </tr>
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $textArray['gross_total'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($total, 2); ?></th>
            </tr>
            <tr>
                <th <?= $fontClass ?>><?= $textArray['discount'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo $discountPercentage; ?>%</th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($discountAmount, 2); ?></th>
            </tr>

            <tr>
                <th colspan="2" <?= $fontClass ?>>
                    <?= $textArray['sub_total'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($total - $discountAmount, 2); ?></th>
            </tr>
        </table>
        <hr>
        <table class="totals">
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $textArray['charge_amount'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($service_charge, 2); ?></th>
            </tr>
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $textArray['payable_amount'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($total - $discountAmount + $service_charge, 2); ?></th>
            </tr>
        </table>

        <table class="totals">
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $PaymentTypes[$close_type]['text'] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($tendered_amount, 2); ?></th>
            </tr>
            <tr>
                <th colspan="2" <?= $fontClass ?>><?= $textArray['change'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($tendered_amount - $netTotal, 2); ?></th>
            </tr>
        </table>
        <hr />
        <div class="bill-foooter" <?= $fontClass ?>><?= $textArray['greeting'][$LanguageMode] ?></div>
        <div class="credits" style="margin-top:10px">Software by Payshia </div>
        <img class="logo-image" src="./assets/images/pos-logo.png" style="width: 25mm; margin-top:10px;">
        <div class="credits">0770481363 | www.payshia.com</div>

    </div>

    <script>
        function calculateFilledHeightInMillimeters(element) {
            var elementHeightInPixels = element.offsetHeight;
            var millimetersPerPixel = 0.264583; // This is an approximate value for common screen dpi (96dpi)
            var filledHeightInMillimeters = (elementHeightInPixels * millimetersPerPixel).toFixed(2);

            return filledHeightInMillimeters;
        }

        document.addEventListener("DOMContentLoaded", function() {
            var invElement = document.getElementById("inv");
            var filledHeight = calculateFilledHeightInMillimeters(invElement);

            filledHeight = parseFloat(filledHeight) + 30; // Parse to an integer and then add 40

            // Update the @page size in your style tag
            var styleTag = document.createElement("style");
            styleTag.innerHTML = `
            @media print {
                @page {
                    size: 78mm ${filledHeight}mm; /* Set the calculated height */
                    margin: 0;
                    /* Adjust margins as needed */
                }
            }
        `;
            document.head.appendChild(styleTag);
            <?php if ($PrinterName == "default") { ?>
                // Print the page
                window.print();

                // Close the window after printing
                window.onafterprint = function() {
                    window.close();
                };
            <?php } ?>

        });
    </script>

</body>

</html>