<option value="">Select Customer</option>
<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';
$LocationID = $_POST['location_id'];
// $CustomerList = GetLocationCustomers($link, $LocationID);
$CustomerList = GetActiveCustomers($link);
if (!empty($CustomerList)) {
    foreach ($CustomerList as $SelectArray) {

        $CustomerName = $SelectArray['customer_first_name'] . " " . $SelectArray['customer_last_name'];
?>
        <option value="<?= $SelectArray['customer_id'] ?>"><?= $SelectArray['customer_first_name'] ?> <?= $SelectArray['customer_last_name'] ?> - <?= $SelectArray['phone_number'] ?></option>

<?php
    }
}
?>