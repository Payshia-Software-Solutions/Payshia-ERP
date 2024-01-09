<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
include '../../../../include/finance-functions.php';
include '../../../../include/settings_functions.php';
$locationList = GetLocations($link);
$locationId = $_POST['default_location'];
$selectedLocation = $locationList[$locationId];


?>

<style>
    #order-table tr {
        height: auto !important
    }

    .recent-po-container {
        max-height: 70vh;
        overflow: auto;
    }
</style>

<div class="row my-3 pb-3">
    <div class="col-md-12">
        <div class="table-title font-weight-bold mb-3 mt-0">Setup Printers</div>
        <div class="alert alert-warning">
            <h5 class="border-bottom">Please Note: </h5>
            This Feature is support only when using <span class="fw-bold">JSPrintManager</span>. instead of System Default Printing
            <div class="text-secondary">
                Download Link(s) -
                <a href="https://www.neodynamic.com/downloads/jspm/" target="_blank">JSPrintManager Client App</a> |
                <a href="https://www.neodynamic.com/products/printing/js-print-manager/" target="_blank">JSPrintManager Library</a>
            </div>
        </div>
        <div class="row g-2">
            <?php
            if (!empty($locationList)) {
                foreach ($locationList as $selectedArray) {

                    $kotPrinterName = $receiptPrinterName = "Not Set";
                    if ($selectedArray['logo_path'] == 'no-image.png') {
                        $file_path = "./pos-system/assets/images/pos-logo.png";
                    } else {
                        $file_path = "./pos-system/assets/images/location/" . $selectedArray['location_id'] . "/" . $selectedArray['logo_path'];
                    }

                    $kotPrinterName = GetSetting($link, $selectedArray['location_id'], 'kot_printer');
                    $receiptPrinterName = GetSetting($link, $selectedArray['location_id'], 'receipt_printer');
                    $reportPrinterName = GetSetting($link, $selectedArray['location_id'], 'report_printer');

                    $kotPrintMethod = GetSetting($link, $selectedArray['location_id'], 'kotPrintMethod');
                    $receiptPrintMethod  = GetSetting($link, $selectedArray['location_id'], 'receiptPrintMethod');
                    $reportPrintMethod  = GetSetting($link, $selectedArray['location_id'], 'reportPrintMethod');


            ?>

                    <?php
                    if ($selectedArray['pos_status'] == 1) {
                    ?>
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body">
                                    <div class="table-title bg-secondary font-weight-bold mb-3 mt-0 shadow-sm">KOT Printer</div>
                                    <div class="row g-2">

                                        <div class="col-md-12">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="text-center border-bottom pb-2 mb-2">
                                                        <img style="max-height: 80px;" class="logo-image  border-bottom pb-2" src="<?= $file_path ?>" onerror="this.src='./pos-system/assets/images/pos-logo.png';">
                                                        <h4 class="mt-1"><?= $selectedArray['location_name'] ?></h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-0 text-dark">KOT Printer : <span class="fw-bolder"><?= $kotPrinterName ?></span></p>
                                            <p class="mb-0 text-dark">Method : <span class="fw-bolder"><?= $kotPrintMethod ?></span></p>
                                        </div>

                                        <div class="bg-light p-3 rounded-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label class="text-secondary">Select Printer</label>
                                                            <select id="kotPrinter-<?= $selectedArray['location_id'] ?>" name="kotPrinter-<?= $selectedArray['location_id'] ?>" class="form-control w-100 kotPrinter">
                                                                <option value="default">System Default</option>
                                                                <option>Please Wait...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 text-end">
                                                            <button onclick="UpdateSetting('kot_printer', document.getElementById('kotPrinter-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Printer</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label class="text-secondary">Select Method</label>
                                                            <select id="kotPrinterMethod-<?= $selectedArray['location_id'] ?>" name="kotPrinterMethod-<?= $selectedArray['location_id'] ?>" class="form-control w-100 printMethod">
                                                                <option <?= ($kotPrintMethod == 'Popup Window') ? 'selected' : '' ?> value="Popup Window">Popup Window</option>
                                                                <option <?= ($kotPrintMethod == 'New Tab') ? 'selected' : '' ?> value="New Tab">New Tab</option>
                                                                <option <?= ($kotPrintMethod == 'Same Window') ? 'selected' : '' ?> value="Same Window">Same Window</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-12 text-end">
                                                            <button onclick="UpdateSetting('kotPrintMethod', document.getElementById('kotPrinterMethod-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Method</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body">

                                    <div class="table-title bg-success font-weight-bold mb-3 mt-0 shadow-sm">Receipt Printer</div>
                                    <div class="row g-2">

                                        <div class="col-md-12">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="text-center border-bottom pb-2 mb-2">
                                                        <img style="max-height: 80px;" class="logo-image border-bottom pb-2" src="<?= $file_path ?>" onerror="this.src='./pos-system/assets/images/pos-logo.png';">
                                                        <h4 class="mt-1"><?= $selectedArray['location_name'] ?></h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-0 text-dark">Receipt Printer : <span class="fw-bolder"><?= $receiptPrinterName ?></span></p>
                                            <p class="mb-0 text-dark">Method : <span class="fw-bolder"><?= $receiptPrintMethod ?></span></p>
                                        </div>

                                        <div class="bg-light p-3 rounded-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label class="text-secondary">Select Printer</label>
                                                            <select id="receiptPrinter-<?= $selectedArray['location_id'] ?>" name="receiptPrinter-<?= $selectedArray['location_id'] ?>" class="form-control w-100 receiptPrinter">
                                                                <option value="default">System Default</option>
                                                                <option>Please Wait...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 text-end">
                                                            <button onclick="UpdateSetting('receipt_printer', document.getElementById('receiptPrinter-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Printer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label class="text-secondary">Select Method</label>
                                                            <select id="receiptPrintMethod-<?= $selectedArray['location_id'] ?>" name="receiptPrintMethod-<?= $selectedArray['location_id'] ?>" class="form-control w-100 printMethod">
                                                                <option <?= ($receiptPrintMethod == 'Popup Window') ? 'selected' : '' ?> value="Popup Window">Popup Window</option>
                                                                <option <?= ($receiptPrintMethod == 'New Tab') ? 'selected' : '' ?> value="New Tab">New Tab</option>
                                                                <option <?= ($receiptPrintMethod == 'Same Window') ? 'selected' : '' ?> value="Same Window">Same Window</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 text-end">
                                                            <button onclick="UpdateSetting('receiptPrintMethod', document.getElementById('receiptPrintMethod-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Method</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="col-md-3 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">

                                <div class="table-title bg-primary font-weight-bold mb-3 mt-0 shadow-sm">Report Printer</div>
                                <div class="row g-2">

                                    <div class="col-md-12">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="text-center border-bottom pb-2 mb-2">
                                                    <img style="max-height: 80px;" class="logo-image border-bottom pb-2" src="<?= $file_path ?>" onerror="this.src='./pos-system/assets/images/pos-logo.png';">
                                                    <h4 class="mt-1"><?= $selectedArray['location_name'] ?></h4>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="mb-0 text-dark">Receipt Printer : <span class="fw-bolder"><?= $reportPrinterName ?></span></p>
                                        <p class="mb-0 text-dark">Method : <span class="fw-bolder"><?= $reportPrintMethod ?></span></p>
                                    </div>
                                    <div class="bg-light p-3 rounded-3">

                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="row g-2">
                                                    <div class="col-md-12">
                                                        <label class="text-secondary">Select Printer</label>
                                                        <select id="reportPrinter-<?= $selectedArray['location_id'] ?>" name="reportPrinter-<?= $selectedArray['location_id'] ?>" class="form-control w-100 receiptPrinter">
                                                            <option value="default">System Default</option>
                                                            <option>Please Wait...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 text-end">
                                                        <button onclick="UpdateSetting('report_printer', document.getElementById('reportPrinter-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm  rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Printer</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row g-2">
                                                    <div class="col-md-12">
                                                        <label class="text-secondary">Select Method</label>
                                                        <select id="reportPrintMethod-<?= $selectedArray['location_id'] ?>" name="reportPrintMethod-<?= $selectedArray['location_id'] ?>" class="form-control w-100 printMethod">
                                                            <option <?= ($receiptPrintMethod == 'Popup Window') ? 'selected' : '' ?> value="Popup Window">Popup Window</option>
                                                            <option <?= ($receiptPrintMethod == 'New Tab') ? 'selected' : '' ?> value="New Tab">New Tab</option>
                                                            <option <?= ($receiptPrintMethod == 'Same Window') ? 'selected' : '' ?> value="Same Window">Same Window</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 text-end">
                                                        <button onclick="UpdateSetting('reportPrintMethod', document.getElementById('reportPrintMethod-<?= $selectedArray['location_id'] ?>').value, '<?= $selectedArray['location_id'] ?>')" type="button" class="btn btn-dark btn-sm  rounded-3 w-100"><i class="fa-solid fa-floppy-disk"></i> Save Method</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

            <?php
                }
            }
            ?>

        </div>



    </div>
</div>

<script>
    var clientPrinters = null;
    var printerClasses = ['kotPrinter', 'receiptPrinter']; // Add more class names as needed

    // WebSocket settings
    JSPM.JSPrintManager.auto_reconnect = true;
    JSPM.JSPrintManager.start();
    JSPM.JSPrintManager.WS.onStatusChanged = function() {
        // Get client installed printers
        JSPM.JSPrintManager.getPrintersInfo().then(function(printersList) {
            clientPrinters = printersList;

            // Loop through the array of classes
            for (var i = 0; i < printerClasses.length; i++) {
                var options = '';
                for (var j = 0; j < clientPrinters.length; j++) {
                    options += '<option value="' + clientPrinters[j].name + '">' + clientPrinters[j].name + '</option>';
                }

                options += '<option value="default">System Default</option>';
                // Update HTML content of select elements with corresponding class
                var elements = document.getElementsByClassName(printerClasses[i]);
                for (var k = 0; k < elements.length; k++) {
                    elements[k].innerHTML = options;
                }
            }
        });
    };
</script>