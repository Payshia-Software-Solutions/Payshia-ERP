<?php
require_once('../../../../../include/config.php');
include '../../../../../include/function-update.php';

$Products = GetProducts($link);
$Units = GetUnit($link);

$ProductID = $_POST['ProductID'];
$UserName = $_POST['LoggedUser'];
$CustomerID = $_POST['CustomerID'];
$ItemPrice = $_POST['ItemPrice'];
$ItemDiscount = $_POST['ItemDiscount'];
$Quantity = $_POST['ItemQty'];
$TableID = $_POST['TableID'];
$LocationID = $_POST['LocationID'];
$printedStatus = $rawStockStatus = $holdMaterialsQty = $getHoldQty = $currentCartQty = $totalOrderQty = $getHoldQtyValue = 0;

$mainProductName = $Products[$ProductID]['product_name'];

$getHoldQty = GetHoldItemQty($link, $LocationID);
$cartItemsByLocation = GetCartByLocation($link, $LocationID);

if ($currentCartQty == "") {
    $currentCartQty = 0;
}

if ($getHoldQty == "") {
    $getHoldQty = 0;
}


$htmlOutput = '
<h4 class="border-bottom pb-1   ">' . $mainProductName . '</h4>
<table class="table table-hover table-bordered">
                <tr>
                    <th>Material</th>
                    <th>Required</th>
                    <th>Available</th>
                </tr>';


$ProductRecipe = GetItemRecipe($link, $ProductID);
if (!empty($ProductRecipe)) {
    foreach ($ProductRecipe as $SelectedArray) {
        $holdMaterialsQty = 0;
        $product_id = $SelectedArray['recipe_product'];
        $product_name = $Products[$product_id]['product_name'];
        $currentStock = GetStockBalanceByProductByLocation($link, $product_id, $LocationID);
        $requiredQuantity = $Quantity * $SelectedArray['qty'];
        $item_unit = $Units[$Products[$product_id]['measurement']]['unit_name'];

        $materialId = $product_id;
        $productListByMaterial = ProductsByMaterial($link, $materialId);


        if (!empty($productListByMaterial)) {
            foreach ($productListByMaterial as $innerArray) {

                if (!empty($getHoldQty)) {
                    $getHoldQtyValue = 0;
                    foreach ($getHoldQty as $holdItem) {
                        if ($innerArray['main_product'] == $holdItem['product_id']) {
                            $getHoldQtyValue += $holdItem['total_quantity'];
                            $holdMaterialsQty += $innerArray['qty'] * $holdItem['total_quantity'];
                        }
                    }
                }

                if (!empty($cartItemsByLocation)) {
                    $currentCartQty = 0;
                    foreach ($cartItemsByLocation as $cartItem) {
                        if ($innerArray['main_product'] == $cartItem['product_id']) {
                            $currentCartQty += $cartItem['total_quantity'];
                            $holdMaterialsQty += $innerArray['qty'] * $cartItem['total_quantity'];
                        }
                    }
                }
            }
        }


        $requiredQuantity += $holdMaterialsQty;

        $elementTag = "td";
        if ($currentStock < $requiredQuantity) {
            $rawStockStatus += 1;
            $elementTag = "th";
        } else {
            // continue;
        }

        $htmlOutput .= '<tr>
    <' . $elementTag . '>' . $product_name . '</' . $elementTag . '>
    <' . $elementTag . '>' . $requiredQuantity . $item_unit . '</' . $elementTag . '>
    <' . $elementTag . '>' . $currentStock . $item_unit . '</' . $elementTag . '>
</tr>';
    }
}

$totalOrderQty = $Quantity + $currentCartQty + $getHoldQtyValue;
$htmlOutput .= '

<h5>Total: ' . $totalOrderQty . '</h5>
<h6>(Order: ' . $Quantity . '/ Hold :' . $getHoldQtyValue . ' / Cart : ' . $currentCartQty . ')</h6>
</table>
<div class="alert alert-warning mb-0">Not Enough Raw Materials!</div>
<button class="btn btn-dark mt-2 w-100 btn-lg p-2" onclick="ClosePopUP()">Close</div>';


if ($rawStockStatus == 0) {
    $result = AddToCart($link, $ProductID, $UserName, $CustomerID, $ItemPrice, $ItemDiscount, $Quantity, $TableID, $printedStatus, $LocationID);
} else {
    $result = json_encode(array('status' => 'error', 'message' => 'Not Enough Raw Materials!', 'htmlOutput' => $htmlOutput));
}
echo $result;
