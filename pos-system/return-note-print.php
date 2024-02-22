<?php
require_once('../include/config.php');
include '../include/function-update.php';

include '../include/settings_functions.php';
$displayStatus = $fontClass = "";
$LanguageMode = "EN";
$netTotal = $total = 0;
$rtnNumber = $_GET['rtnNumber'];
$PrinterName = $_GET['PrinterName'];

$reprintStatus = $_GET['reprintStatus'];
$titleSuffix = "";
if ($reprintStatus == 1) {
    $titleSuffix = " - REPRINT";
}


$SelectedArray = GetReturns()[$rtnNumber];
$InvProducts = GetReturnItemsPrint($rtnNumber);
$Products = GetProducts($link);
$Units = GetUnit($link);

$barcode = GenerateHighResolutionBarcode($rtnNumber);
$textArray = [
    "invoice" => ["EN" => "Return Note", "SI" => "ආපසු ගැනුම් සටහන"],
    "invoice_number" => ["EN" => "RTN #", "SI" => "ආපසු ගැනුම් අංකය"],
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
    "unit_price" => ["EN" => "Price", "SI" => "එකක මිල"],
    "amount" => ["EN" => "Amount", "SI" => "මුදල"],
    "greeting" => ["EN" => "Thank You..! Come Again", "SI" => "ස්තූතියි! නැවත එන්න.."]
];


$inv_time = date("Y-m-d H:i:s", strtotime($SelectedArray['created_at']));


$InvoiceNumber = $SelectedArray['ref_invoice'];
$LocationName = GetLocations($link)[$SelectedArray['location_id']]['location_name'];
$selectedLocation =  GetLocations($link)[$SelectedArray['location_id']];

// echo $invAmount;
$customer_code = $SelectedArray['customer_id'];
$CustomerName = GetCustomerName($link, $customer_code);
$created_by =  $SelectedArray['updated_by'];

if (!empty($created_by)) {
    $LoggedStudent = GetAccounts($link)[$created_by];
    $LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
} else {
    $LoggedName = "Unknown";
}

$LanguageMode = GetSetting($link, $SelectedArray['location_id'], 'invoiceLang');
if ($LanguageMode == "" || isset($LanguageMode)) {
    $LanguageMode = "EN";
}
if ($LanguageMode == "EN") {
    $fontClass = '';
} else if ($LanguageMode == "SI") {
    $fontClass = 'style=" font-family: \'Noto Serif Sinhala\', serif !important; font-weight:700;"';
}


if ($selectedLocation['logo_path'] == 'no-image.png') {
    $file_path = "./assets/images/pos-logo.png";
} else {
    $file_path = "./assets/images/location/" . $selectedLocation['location_id'] . "/" . $selectedLocation['logo_path'];
}

$invoiceLogoStatus = GetSetting($link, $selectedLocation['location_id'], 'invoiceLogoStatus');
if ($invoiceLogoStatus == 1) {
    $displayStatus = "d-block";
} else {
    $displayStatus = "d-none";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/print-invoice-1.1.css" />
    <title><?= $InvoiceNumber ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Sinhala:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@200;300&display=swap');

        .d-none {
            display: none !important;
        }

        @page {
            size: 80mm;
            /* Set the page size to 80mm */
            margin: none !important;
            padding: 0px;
            /* Adjust margins as needed */
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../node_modules/jsprintmanager/JSPrintManager.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
</head>

<body>


    <div class="inv" id="inv">
        <div class="logo-box" style="margin-bottom: 20px;">
            <img class="logo-image <?= $displayStatus ?>" src="<?= $file_path ?>" onerror="this.src='./assets/images/pos-logo.png';">
        </div>

        <p class="address <?= $displayStatus ?>">#<?= $selectedLocation['address_line1'] ?>, <?= $selectedLocation['address_line2'] ?>, <?= $selectedLocation['city'] ?></p>
        <p class="telephone <?= $displayStatus ?>">Tel : <?= $selectedLocation['phone_1'] ?> / <?= $selectedLocation['phone_2'] ?></p>
        <p class="telephone <?= $displayStatus ?>">Email : info@transitaradhana.com</p>
        <hr class="<?= $displayStatus ?>" />

        <h2 class="company" <?= $fontClass ?>><?= $textArray['invoice'][$LanguageMode] ?><?= $titleSuffix ?></h2>

        <div class="InvoiceID" <?= $fontClass ?>><?= $textArray['invoice_number'][$LanguageMode] ?> : <span class="invoice_number"><?= $rtnNumber; ?></span></div>
        <div class="Customer" <?= $fontClass ?>>REF : <span class=""><?= $InvoiceNumber; ?></span></div>
        <div class="Customer" <?= $fontClass ?>><?= $textArray['customer'][$LanguageMode] ?> : <span class="cus_name"><?php echo $CustomerName; ?></span></div>
        <div class="dateContainer" <?= $fontClass ?>><?= $textArray['date'][$LanguageMode] ?> : <span class="date"><?php echo $inv_time; ?></span></div>
        <div class="Customer" <?= $fontClass ?>><?= $textArray['cashier'][$LanguageMode] ?> : <span class="cus_name"><?= $LoggedName ?></span></div>


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
                        $selling_price = $SelectRecord['item_rate'];
                        $item_quantity = $SelectRecord['item_qty'];
                        $product_id = $SelectRecord['product_id'];

                        $line_total = ($selling_price) * $item_quantity;
                        $total += $line_total;

                        if ($LanguageMode == "SI") {
                            $print_name = $name_si;
                        }
                ?>
                        <tr>
                            <td colspan="4" <?= $fontClass ?>><?= $print_name; ?> - <?php echo number_format($selling_price, 2); ?></td>
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
                <th colspan="2" <?= $fontClass ?>>
                    <?= $textArray['sub_total'][$LanguageMode] ?></th>
                <th class="text-right" <?= $fontClass ?>><?php echo number_format($total, 2); ?></th>
            </tr>
        </table>
        <hr>


        <div class="bill-foooter" <?= $fontClass ?>><?= $textArray['greeting'][$LanguageMode] ?></div>
        <div class="credits <?= $displayStatus ?>" style="margin-top:10px">Software by Payshia </div>
        <img class="logo-image <?= $displayStatus ?>" src="./assets/images/payshia-logo-p.png" style="width: 8mm; margin-top:10px;">
        <div class="credits <?= $displayStatus ?>">0770481363 | www.payshia.com</div>

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
            <?php } else { ?>
                JSPM.JSPrintManager.auto_reconnect = true;
                JSPM.JSPrintManager.start();
                JSPM.JSPrintManager.WS.onStatusChanged = function() {
                    if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
                        // Use html2canvas to convert the content to an image
                        html2canvas(document.getElementById('inv'), {
                            scale: 2
                        }).then(function(canvas) {
                            //Create a ClientPrintJob
                            var cpj = new JSPM.ClientPrintJob();

                            var myPrinter = new JSPM.InstalledPrinter('<?= $PrinterName ?>');
                            myPrinter.paperName = '80(72.1) x 297 mm';

                            cpj.clientPrinter = myPrinter;
                            //Set content to print... 
                            var b64Prefix = "data:image/png;base64,";
                            var imgBase64DataUri = canvas.toDataURL("image/png");
                            var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);



                            var myImageFile = new JSPM.PrintFile(imgBase64Content, JSPM.FileSourceType.Base64, '<?= $invoice_number ?>.png', 1);
                            //add file to print job
                            cpj.files.push(myImageFile);

                            //Send print job to printer!
                            cpj.sendToClient();


                            // // Create a download link
                            // var a = document.createElement('a');
                            // a.href = imgBase64DataUri;
                            // a.download = myImageFile;
                            // a.style.display = 'none';
                            // document.body.appendChild(a);

                            // // Trigger a click event on the link to initiate download
                            // a.click();

                            // // Remove the link from the DOM
                            // document.body.removeChild(a);


                            setTimeout(function() {
                                // window.location.href = 'https://demo.payshia.com/pos-system/?last_invoice=true&display_invoice_number=<?= $invoice_number ?>&location_id=<?= $SelectedArray['location_id'] ?>';
                                window.close();
                            }, 5000); // 1000 milliseconds = 1 second

                        });


                    }
                };
            <?php } ?>

        });
    </script>

    <!-- 80(72.1) x 210 mm -->
    <!-- 80(72.1) x 297 mm -->

</body>

</html>