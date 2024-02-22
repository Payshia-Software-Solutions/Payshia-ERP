<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$LocationID = $_POST['LocationID'];
$CustomerList = GetCustomers($link);
$Cities = GetCities($link);
?>

<style>
    .customer-container {
        max-height: 100vh;
        overflow-y: auto;
    }
</style>

<div class="row mt-3">
    <div class="col-12 col-md-6">
        <h4>Select Customer</h4>
    </div>
    <div class="col-12 col-md-6">
        <input type="search" id="customer-search" class="form-control" placeholder="Search Customer..">
    </div>

    <div class="customer-container">
        <hr>

        <div class="row">
            <?php
            if (!empty($CustomerList)) {
                foreach ($CustomerList as $SelectArray) {


                    if ($SelectArray['is_active'] != 1) {
                        continue;
                    }

                    if ($SelectArray['location_id'] != $LocationID && $SelectArray['location_id'] != -1) {
                        continue;
                    }



                    $CustomerName = $SelectArray['customer_first_name'] . " " . $SelectArray['customer_last_name'];
            ?>
                    <div class="col-12 col-md-6 col-md-4 mt-2 customer-column">
                        <div class="card clickable shadow-sm customer-card" onclick="SelectCustomerValue('<?= $SelectArray['customer_id'] ?>', '<?= $CustomerName ?>')">
                            <div class="card-body">
                                <h5 class="mb-0 customer-info"><?= $SelectArray['customer_first_name'] ?> <?= $SelectArray['customer_last_name'] ?> - <?= $SelectArray['phone_number'] ?></h5>
                                <p class="mb-0 text-secondary"><?= $SelectArray['address_line1'] ?>, <?= $SelectArray['address_line2'] ?>, <?= $Cities[$SelectArray['city_id']]['name_en'] ?> </p>
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
    document.getElementById("customer-search").addEventListener("input", function() {
        const searchText = this.value.toLowerCase();
        const CustomerCards = document.querySelectorAll(".customer-column");

        CustomerCards.forEach(function(CustomerCard) {
            const CustomerName = CustomerCard.querySelector(".customer-info").textContent.toLowerCase();

            if (CustomerName.includes(searchText)) {
                CustomerCard.classList.remove("d-none");
                CustomerCard.classList.add("d-block");
            } else {
                CustomerCard.classList.remove("d-block");
                CustomerCard.classList.add("d-none");
            }
        });
    });
</script>