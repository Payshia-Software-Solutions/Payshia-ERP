<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$locationID = $_POST['location_id'];
$customerId = $_POST['customerId'];
$CustomerInvoices =  GetInvoicesByCustomer($link, $customerId);

echo $locationID;
?>

<option value="">Select Invoice</option>
<?php
if (!empty($CustomerInvoices)) {
    foreach ($CustomerInvoices as $selectedArray) {
        echo $selectedArray['location_id'];
        if ($selectedArray['location_id'] != $locationID) {
            continue;
        }

        if ($selectedArray['invoice_status'] != '2') {
            continue;
        }
?>
        <option value="<?= $selectedArray['invoice_number'] ?>"><?= $selectedArray['invoice_number'] ?> - <?= $selectedArray['inv_amount'] ?> - <?= $selectedArray['invoice_date'] ?></option>
<?php
    }
}
