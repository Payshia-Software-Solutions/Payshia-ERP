<?php
require_once('../include/config.php');
include '../include/function-update.php';
include '../include/settings_functions.php';

$netTotal = $total = 0;
$invoice_number = $_GET['invoice_number'];
$SelectedArray = GetInvoices($link)[$invoice_number];
$InvProducts = GetInvoiceItems($link, $invoice_number);
$Products = GetProducts($link);
$Units = GetUnit($link);
$stewards = GetStewards($link);
$PrinterName = $_GET['PrinterName'];
$forceStatus = $_GET['forceStatus'];

$reprintStatus = $_GET['reprintStatus'];
$titleSuffix = "";
if ($reprintStatus == 1) {
    $titleSuffix = " - REPRINT";
}

$stewardId = $SelectedArray['steward_id'];
if ($stewardId === "0") {
    $stewardName = "Default";
} else {
    $stewardName = $stewards[$stewardId]['first_name'] . " " . $stewards[$stewardId]['last_name'];
}


$inv_time = date("Y-m-d H:i:s", strtotime($SelectedArray['current_time']));
$TableID = $SelectedArray['table_id'];
if ($TableID == 0) {
    $TableName = "Take Away";
} else if ($TableID == -1) {
    $TableName = "Retail";
} else if ($TableID == -2) {
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
$customer_code = $SelectedArray['customer_code'];
$invoice_status = $SelectedArray['invoice_status'];
$discountAmount = $invAmount * ($discountPercentage / 100);
$netTotal = $invAmount - $discountAmount;

$created_by =  $SelectedArray['created_by'];
if (!empty($created_by)) {
    $LoggedStudent = GetAccounts($link)[$created_by];
    $LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
} else {
    $LoggedName = "Unknown";
}

$selectedLocation =  GetLocations($link)[$SelectedArray['location_id']];

$CustomerName = GetCustomerName($link, $customer_code);

if ($selectedLocation['logo_path'] == 'no-image.png') {
    $file_path = "./assets/images/pos-logo.png";
} else {
    $file_path = "./assets/images/location/" . $selectedLocation['location_id'] . "/" . $selectedLocation['logo_path'];
}

$guestReceiptLogoStatus = GetSetting($link, $selectedLocation['location_id'], 'kotLogoStatus');
if ($guestReceiptLogoStatus == 1) {
    $displayStatus = "d-block";
} else {
    $displayStatus = "d-none";
}

?>
<!DOCTYPE html>
<html lang="en" style="margin: 0; padding:0">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/print-invoice-1.1.css" />
    <title><?= $InvoiceNumber ?></title>
    <style>
        .d-none {
            display: none !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../node_modules/jsprintmanager/JSPrintManager.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
</head>

<body id="body" style="margin: 0; padding:0">
    <div class="inv" id="inv">
        <div class="logo-box <?= $displayStatus ?>" style="margin-bottom: 20px;">
            <img class="logo-image" src="<?= $file_path ?>">
        </div>

        <p class="address <?= $displayStatus ?>">#<?= $selectedLocation['address_line1'] ?>, <?= $selectedLocation['address_line2'] ?>, <?= $selectedLocation['city'] ?></p>
        <p class="telephone <?= $displayStatus ?>">Tel : <?= $selectedLocation['phone_1'] ?> / <?= $selectedLocation['phone_2'] ?></p>
        <p class="telephone <?= $displayStatus ?>">Email : info@transitaradhana.com</p>
        <hr class="<?= $displayStatus ?>" />

        <h2 class="company">KOT <?= $titleSuffix ?></h2>
        <div class="InvoiceID">KOT # : <span class="invoice_number" style="font-weight: 800;"><?php echo $InvoiceNumber; ?></span></div>
        <div class="Customer">Table : <span class="cus_name"><?= $TableName ?></span></div>
        <div class="Customer">Customer : <span class="cus_name"><?php echo $CustomerName; ?></span></div>
        <div class="dateContainer">Date : <span class="date"><?php echo $inv_time; ?></span></div>
        <div class="Customer">Steward : <span class="cus_name"><?= $stewardName ?></span></div>
        <div class="Customer">Cashier : <span class="cus_name"><?= $LoggedName ?></span></div>
        <hr />

        <table style="width: 100%;">
            <thead>
                <tr>
                    <th class="headerth">Qty</th>
                    <th class="headerth">Unit Price</th>
                    <th class="headerth">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($InvProducts)) {
                    foreach ($InvProducts as $SelectRecord) {

                        $printed_status = $SelectRecord['printed_status'];
                        if ($printed_status == 1 && $invoice_status == 1 && $forceStatus != 1) {
                            continue;
                        }
                        $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                        $print_name = $Products[$SelectRecord['product_id']]['print_name'];
                        $item_unit = $Units[$Products[$SelectRecord['product_id']]['measurement']]['unit_name'];
                        $selling_price = $SelectRecord['item_price'];
                        $item_quantity = $SelectRecord['quantity'];
                        $item_discount = $SelectRecord['item_discount'];
                        $product_id = $SelectRecord['product_id'];

                        $line_total = ($selling_price - $item_discount) * $item_quantity;
                        $total += $line_total;
                ?>
                        <tr>
                            <td colspan="4"><?php echo $print_name; ?></td>
                        </tr>
                        <tr class="selected">
                            <td><?php echo $item_quantity; ?></td>
                            <td class="text-right"><?php echo number_format($selling_price - $item_discount, 2); ?></td>
                            <td class="text-right"><?php echo number_format($line_total, 2); ?></td>
                        </tr>

                <?php
                    }
                }
                ?>


            </tbody>
        </table>
        <hr />

        <?php
        // Force Display
        $displayStatus = 1;
        ?>
        <div class="bill-foooter <?= $displayStatus ?>">Thank You..! Come Again</div>
        <div class="credits <?= $displayStatus ?>" style="margin-top:10px">Software by <?= $SiteTitle ?> </div>

        <img class="logo-image <?= $displayStatus ?>" src="./assets/images/payshia-logo-p.png" style="width: 8mm; margin-top:10px;">
        <div class="credits">077 0 481 363 | www.payshia.com</div>

    </div>

    <?php
    $printStatus = 1;
    $updatePrintedStatus = UpdatePrintedStatus($link, $invoice_number, $printStatus);
    ?>

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

            // Print the page
            // window.print();

            // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };

        });
    </script>

    <script>

    </script>
</body>


</html>

<script>
    console.log("PrinterName:KOT-Printer,InvoiceNumber:<?= $invoice_number ?>");

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
                }
            }
        `;
        document.head.appendChild(styleTag);
    });

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

                    setTimeout(function() {
                        // window.location.href = 'https://demo.payshia.com/pos-system/?last_invoice=true&display_invoice_number=<?= $invoice_number ?>&location_id=<?= $SelectedArray['location_id'] ?>';
                        window.close();
                    }, 1000); // 1000 milliseconds = 1 second

                });


            }
        };
    <?php } ?>
</script>

<!-- <script>
        // Function to print the invoice to a specific printer
        function printToPrinter(printerName) {
            var styleTag = document.createElement("style");
            styleTag.innerHTML = `
        @media print {
            @page {
                size: auto;
                margin: 0;
                printer: "${printerName}";
            }
        }
    `;
            document.head.appendChild(styleTag);


            var invElement = document.getElementById("inv");
            // Set the desired printer as the default printer using a CSS style
            var css = `
                @media print {
                    @page {
                        size: auto;
                        margin: 0;
                        /* Set the printer name here */
                        printer: "${printerName}";
                    }
                }
            `;

            var printNewWindow = window.open("", "", "width=600, height=600");

            // Check if the printWindow is not null before attempting to access its properties
            if (printNewWindow) {
                printNewWindow.document.write("<html><head><title>Print</title><style>" + css + "</style></head><body>");
                printNewWindow.document.write(invElement.innerHTML);
                printNewWindow.document.write("</body></html>");
                printNewWindow.document.close();

                // Print and close the window
                printNewWindow.print();
                printNewWindow.close();
            } else {
                console.error("Failed to open the print window. Check your browser's pop-up settings.");
            }
        }


        document.addEventListener("DOMContentLoaded", function() {
            // Call the printToPrinter function for each printer
            printToPrinter("Microsoft Print to PDF");
            printToPrinter("Microsoft XPS Document Writer");
        });
    </script> -->