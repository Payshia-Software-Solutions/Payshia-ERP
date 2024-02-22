<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$isActive = $_POST['isActive'];
$updateKey = $_POST['updateKey'];

$Locations = GetLocations($link);
$Products = GetProducts($link);
$Units = GetUnit($link);

?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">


            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Product Info</button>
                    <button class="nav-link" id="nav-materials-tab" data-bs-toggle="tab" data-bs-target="#nav-materials" type="button" role="tab" aria-controls="nav-materials" aria-selected="false">Materials</button>
                    <button class="nav-link" id="nav-production-tab" data-bs-toggle="tab" data-bs-target="#nav-production" type="button" role="tab" aria-controls="nav-production" aria-selected="false">Production</button>
                </div>
            </nav>

            <form id="production-form" method="post">

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

                        <h5 class="mb-2 mt-3 border-bottom pb-2">Production Sheet Information</h5>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label mb-0">Select Date</label>
                                <input type="date" class="form-control" name="productionDate" id="productionDate" required value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-0">Target Quantity (<span class="unitName">Unit Not Set</span>)</label>
                                <input oninput="$('#targetQtySnap').val(this.value)" type="number" class="form-control" name="targetQuantity" id="targetQuantity" required placeholder="Enter Target Quantity">
                            </div>



                            <div class="col-md-6">
                                <label class="form-label mb-0">Select Product</label>
                                <select class="form-control" name="select_product" id="select_product" required autocomplete="off" onchange="productSelectionChanged(this)">
                                    <option value="">Select Product</option>
                                    <?php
                                    if (!empty($Products)) {
                                        foreach ($Products as $SelectedArray) {
                                            if ($SelectedArray['active_status'] != 1) {
                                                continue;
                                            }

                                            if ($SelectedArray['recipe_type'] != "2") {
                                                continue;
                                            }

                                            $unitId = $SelectedArray['measurement'];
                                            $unit = $Units[$unitId]['unit_name'];
                                    ?>
                                            <option data-id-unit="<?= $unit ?>" value="<?= $SelectedArray['product_id'] ?>"><?= $SelectedArray['product_name'] ?> - <?= $SelectedArray['cost_price'] ?> - <?= $SelectedArray['product_code'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-0">Batch Number</label>
                                <input type="number" class="form-control" name="batchNumber" id="batchNumber" readonly placeholder="Batch Number">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label mb-0">Select Location</label>
                                <select class="form-control" name="locationId" id="locationId" required autocomplete="off">
                                    <option value="">Select Location</option>
                                    <?php
                                    if (!empty($Locations)) {
                                        foreach ($Locations as $SelectedArray) {
                                            if ($SelectedArray['is_active'] != 1) {
                                                continue;
                                            }
                                    ?>

                                            <option value="<?= $SelectedArray['location_id'] ?>"><?= $SelectedArray['location_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label mb-0">Remarks</label>
                                <input type="text" class="form-control" name="ProductionRemarks" id="ProductionRemarks" placeholder="Enter Production Remarks Here!">
                            </div>


                        </div>



                    </div>
                </div>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade" id="nav-materials" role="tabpanel" aria-labelledby="nav-materials-tab" tabindex="0">
                        <h5 class="pb-2 mt-3 mb-2">Please Select Product and Quantity</h5>
                    </div>
                </div>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade" id="nav-production" role="tabpanel" aria-labelledby="nav-production-tab" tabindex="0">

                        <h5 class="mb-2 mt-3 border-bottom pb-2">Please Enter Production Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-8">Target Quantity (<span class="unitName">Unit Not Set</span>)</div>
                                    <div class="col-4">
                                        <input type="number" class="form-control" readonly name="targetQtySnap" id="targetQtySnap">
                                    </div>

                                    <div class="col-8">Production Quantity (<span class="unitName">Unit Not Set</span>)</div>
                                    <div class="col-4">
                                        <input step="0.001" oninput="CalculateExcessLossQty()" type="number" class="form-control" name="productionQty" id="productionQty">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <h6>Summary</h6>
                                <div class="row">
                                    <div class="col-12">
                                        Production Excess/Loss
                                        <h4 id="excessLossQty">Not set</h4>

                                        Cost Per KG this Production
                                        <h4 id="costPerKg">Not Set</h4>
                                        <input type="hidden" id="costPerKgValue" name="costPerKgValue">
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-end">
                                <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                                <button onclick="SaveProduction()" class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#select_product').select2({
            width: 'resolve'
        });
    });

    function productSelectionChanged(select) {
        var selectedOption = select.options[select.selectedIndex];
        var selectedUnit = selectedOption.getAttribute('data-id-unit');

        var unitNameSpans = document.querySelectorAll('.unitName');
        unitNameSpans.forEach(function(span) {
            span.textContent = selectedUnit;
        });

        var targetQuantity = $('#targetQuantity').val();
        GetReceiptContent(select.value, targetQuantity);
    }

    function CalculateExcessLossQty() {
        $('#excessLossQty').html('Please Wait...');

        var targetQty = parseFloat($('#targetQtySnap').val()); // Assuming targetQty is a numeric value
        var productionQty = parseFloat($('#productionQty').val()); // Assuming productionQty is a numeric value

        // Check if the input values are valid numbers
        if (isNaN(targetQty) || isNaN(productionQty)) {
            OpenAlert('error', 'Error!', 'Please enter valid numeric values for target quantity and production quantity.')

            return; // Exit the function
        }

        // Calculate excess loss quantity
        var excessLossQty = productionQty - targetQty;
        var excessLossQtyText; // Declare the variable here

        if (excessLossQty > 0) {
            excessLossQtyText = "Excess";
        } else if (excessLossQty < 0) {
            excessLossQtyText = "Loss";
        } else {
            excessLossQtyText = "Fine";
        }

        $('#excessLossQty').html(Math.abs(excessLossQty.toFixed(2)) + ' ' + excessLossQtyText);
        CalculateCostPerKG()
    }

    function CalculateCostPerKG() {
        $('#costPerKg').html('Please Wait...');

        var targetQty = parseFloat($('#targetQtySnap').val()); // Assuming targetQty is a numeric value
        var productionQty = parseFloat($('#productionQty').val()); // Assuming productionQty is a numeric value

        // Check if the element with ID ProductionTotalCost exists on the screen
        var ProductionTotalCostElement = $('#ProductionTotalCost');
        if (ProductionTotalCostElement.length > 0) {
            var ProductionTotalCostString = ProductionTotalCostElement.html();
            var ProductionTotalCost = parseFloat(ProductionTotalCostString.replace(/,/g, ''));

            if (!isNaN(productionQty) && !isNaN(ProductionTotalCost) && productionQty !== 0) {
                var CostPerKG = ProductionTotalCost / productionQty;
                $('#costPerKg').html(CostPerKG.toFixed(2));
                $('#costPerKgValue').val(CostPerKG.toFixed(2));
            } else {
                // Handle division by zero or invalid input values
                $('#costPerKg').html("Invalid input or division by zero");
            }
        } else {
            // Handle the case when the element is not found
            $('#costPerKg').html("Production Total Cost not found on the screen");
        }
    }
</script>