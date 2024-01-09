<?php
require_once('../include/config.php');
include '../include/function-update.php';

$netTotal = $total = 0;
$rec_number = $_GET['rec_number'];
$PrinterName = $_GET['PrinterName'];
$Receipt =  GetReceiptByNumber($link, $rec_number)[$rec_number];

$type = $Receipt['type'];
$is_active = $Receipt['is_active'];
$date = $Receipt['date'];
$current_time = $Receipt['current_time'];
$amount = $Receipt['amount'];
$created_by = $Receipt['created_by'];
$ref_id = $Receipt['ref_id'];
$location_id = $Receipt['location_id'];


$SelectedInvoiceArray = GetInvoiceByNumber($link, $ref_id);
$customer_code = $SelectedInvoiceArray['customer_code'];

$process_time = date("Y-m-d H:i:s", strtotime($current_time));


$PaymentTypes = [
    ["id" => "0", "text" => "Cash"],
    ["id" => "1", "text" => "Visa/Master"],
    ["id" => "2", "text" => "Cheque"],
    ["id" => "3", "text" => "GV"]
];


$created_by =  $Receipt['created_by'];
if (!empty($created_by)) {
    $LoggedStudent = GetAccounts($link)[$created_by];
    $LoggedName =  $LoggedStudent['first_name'] . " " . $LoggedStudent['last_name'];
} else {
    $LoggedName = "Unknown";
}

$selectedLocation =  GetLocations($link)[$location_id];
$CustomerName = GetCustomerName($link, $customer_code);


if ($selectedLocation['logo_path'] == 'no-image.png') {
    $file_path = "./assets/images/pos-logo.png";
} else {
    $file_path = "./assets/images/location/" . $selectedLocation['location_id'] . "/" . $selectedLocation['logo_path'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/print-invoice-1.0.css" />
    <title><?= $rec_number ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+Sinhala:wght@100;200;300;400;500;600;700;800;900&family=Poppins:wght@200;300&display=swap');

        .d-none {
            display: none !important;
        }

        @page {
            size: 80mm;
            /* Set the page size to 80mm */
            margin: 0;
            /* Adjust margins as needed */
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../node_modules/jsprintmanager/JSPrintManager.js"></script>
</head>

<body>


    <div class="inv" id="inv">
        <div class="logo-box" style="margin-bottom: 20px;">
            <img class="logo-image" src="<?= $file_path ?>">
        </div>

        <p class="address">#<?= $selectedLocation['address_line1'] ?>, <?= $selectedLocation['address_line2'] ?>, <?= $selectedLocation['city'] ?></p>
        <p class="telephone">Tel : <?= $selectedLocation['phone_1'] ?> / <?= $selectedLocation['phone_2'] ?></p>
        <p class="telephone">Email : info@transitaradhana.com</p>
        <hr />
        <h2 class="company">Receipt</h2>
        <div class="InvoiceID">Receipt # : <span class="invoice_number"><?php echo $rec_number; ?></span></div>
        <div class="Customer">Invoice # : <span class="invoice_number"><?php echo $ref_id; ?></span></div>
        <div class="Customer">Customer : <span class="cus_name"><?php echo $CustomerName; ?></span></div>
        <div class="dateContainer">Date : <span class="date"><?php echo $process_time; ?></span></div>
        <div class="Customer">Cashier : <span class="cus_name"><?php echo $LoggedName; ?></span></div>
        <hr />

        <table>
            <thead>
                <tr>
                    <th class="headerth">Type</th>
                    <th class="headerth">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="selected">
                    <td><?= $PaymentTypes[$type]['text'] ?></td>
                    <td class="text-right"><?= number_format($amount, 2); ?></td>
                </tr>


            </tbody>
        </table>
        <hr />
        <div class="bill-foooter">Thank You..! Come Again</div>
        <div class="credits" style="margin-top:10px">Software by UniERP </div>
        <img class="logo-image" src="./assets/images/pos-logo-line.png" style="width: 25mm; margin-top:10px;">
        <div class="credits">0770481363 | www.unierp.com</div>

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

            // // Print the page
            // window.print();

            // // Close the window after printing
            // window.onafterprint = function() {
            //     window.close();
            // };

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
                            scale: 3
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

                            var myImageFile = new JSPM.PrintFile(imgBase64Content, JSPM.FileSourceType.Base64, '<?= $rec_number ?>.png', 1);
                            //add file to print job
                            cpj.files.push(myImageFile);



                            //Send print job to printer!
                            cpj.sendToClient();

                            setTimeout(function() {
                                // window.location.href = 'https://demo.payshia.com/pos-system/?last_invoice=true&display_invoice_number=<?= $rec_number ?>&location_id=<?= $location_id ?>';
                                window.close();
                            }, 1000); // 1000 milliseconds = 1 second

                        });


                    }
                };
            <?php } ?>
        });
    </script>

</body>

</html>