<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';
$Products = GetProducts($link);

$LoggedUser = $_POST['LoggedUser'];
$CartProducts = GetCart($link, $LoggedUser);
?>

<div class="card mt-3 mt-md-0">
    <div class="card-body">
        <div class="row">
            <div class="col-6" style="padding-right: 0px;">
                <button class="w-100 btn btn-light"><i class="fa-solid fa-plus btn-icon"></i>Add Customer</button>
            </div>
            <div class="col-6">
                <input type="text" class="w-100 btn btn-light" value="Table 1" data-id="1" readonly>
            </div>
        </div>

        <input type="hidden" id="customer-id" value="0">
        <hr>
        <div class="item-area">

            <?php
            if (!empty($CartProducts)) {
                foreach ($CartProducts as $SelectRecord) {
                    $display_name = $Products[$SelectRecord['product_id']]['display_name'];
                    $selling_price = $SelectRecord['item_price'];
                    $item_quantity = $SelectRecord['quantity'];
                    $item_discount = $SelectRecord['item_discount'];
                    $product_id = $SelectRecord['product_id'];
            ?>
                    <div class="p-2">
                        <div class="row">
                            <div class="col-2">
                                <h4 class="product-price"><?= $item_quantity ?></h4>
                            </div>
                            <div class="col-6">
                                <h4 class="product-title"><?= $display_name ?></h4>
                            </div>
                            <div class="col-3">
                                <h4 class="product-price"><?= $selling_price ?></h4>

                            </div>
                            <div class="col-1"><i class="fa-solid fa-trash text-danger clickable" onclick="RemoveFromCart('<?= $product_id ?>')"></i></div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>


        </div>

        <div class="bg-light p-3 mt-3">
            <div class="row">
                <div class="col-12 text-end">
                    <span class="mb-0 text-end text-warning px-2">Discount</span>
                    <span class="mb-0 text-end text-warning px-2">Coupon</span>
                    <span class="mb-0 text-end text-warning px-2">Note</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-light shadow-sm mt-2">
    <div class="card-body">
        <div class="bg-light p-3">
            <div class="row">
                <div class="col-6">
                    <span class="mb-0 text-end text-secondary px-2">Sub Total</span>
                </div>
                <div class="col-6 text-end">
                    <span class="mb-0 text-end px-2">$45.00</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <span class="mb-0 text-end text-secondary px-2">Tax</span>
                </div>
                <div class="col-6 text-end">
                    <span class="mb-0 text-end px-2">$20.00</span>
                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <span class="mb-0 text-end  px-2 payable-amount">Payable Amount</span>
                </div>
                <div class="col-6 text-end">
                    <span class="mb-0 text-end px-2 payable-value">$65.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-6" style="padding-right: 0px;">
        <button class="text-white w-100 btn btn-warning hold-button btn-lg p-4"><i class="fa-solid fa-pause btn-icon"></i> Hold</button>
    </div>
    <div class="col-6">
        <button class="text-white w-100 btn btn-success hold-button btn-lg  p-4"><i class="fa-solid fa-floppy-disk btn-icon"></i> Proceed</button>
    </div>
</div>