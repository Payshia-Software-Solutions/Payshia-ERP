<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$InvoiceNumber = $_POST['InvoiceNumber'];

$Invoices = GetHoldInvoices($link);
$LocationID = $_POST['LocationID'];

$PlayStatus = $_POST['PlayStatus'];

$netTotal = $total = 0;
$Products = GetProducts($link);
$Units = GetUnit($link);
$SelectedArray = GetInvoiceByNumber($link, $InvoiceNumber);

$InvProducts = GetInvoiceItems($link, $InvoiceNumber);

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


$invoice_date = date("Y-m-d", strtotime($SelectedArray['invoice_date']));
?>
<style>
    .x-button {
        display: none;
    }
</style>

<div class="col-12 d-flex mb-3">
    <div class="card table-card flex-fill shadow-sm clickable">
        <div class="card-body p-2 pb-2">

            <?php if ($PlayStatus == 1) { ?>
                <h1 class="text-center">New Order!</h1>
            <?php } ?>
            <span class="badge text-dark mt-2 bg-light"><?= $SelectedArray['invoice_number'] ?></span>
            <span class="badge mt-2 bg-primary"><?= $TableName ?></span>
            <h1 class="tutor-name mt-2">LKR <?= number_format($SelectedArray['inv_amount'], 2) ?></h1>
            <span class="tutor-name text-dark  bg-light badge mt-2"><?= $invoice_date ?></span>
            <hr>
            <?php
            if (!empty($InvProducts)) {
                foreach ($InvProducts as $SelectRecord) {
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

                    <p class="mb-0"><?php echo $print_name; ?></p>
                    <p class="text-end">@ <?php echo $item_quantity; ?></p>

            <?php
                }
            }
            ?>

        </div>
    </div>
</div>

<?php if ($PlayStatus == 1) { ?>
    <audio id="myAudio" controls autoplay style="display: none;">
        <source src="./assets/audio/order-notofication.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <script>
        var autoplayInterval; // Declare a variable to store the interval ID

        function forceAutoplay() {
            // Clear the previous interval
            clearInterval(autoplayInterval);

            var audio = document.getElementById('myAudio');
            audio.play();

            // Set a new interval
            autoplayInterval = setInterval(forceAutoplay, 6000);
        }

        $(document).ready(function() {
            forceAutoplay(); // Call the function immediately
        })
    </script>
<?php } ?>




<div class="row mt-3 mt-md-0">
    <div class="col-4 d-flex">
        <button onclick="EmptyPopup()" class=" flex-fill text-white w-100 btn btn-warning hold-button btn-lg p-4"><i class="fa-solid fa-pause btn-icon"></i> Okay</button>
    </div>
    <div class="col-4 d-flex">
        <button onclick="ReadyOrder('<?= $InvoiceNumber ?>')" class=" flex-fill text-white w-100 btn btn-success hold-button btn-lg p-4"><i class="fa-solid fa-check btn-icon"></i> Ready</button>
    </div>
    <div class="col-4 d-flex">
        <button onclick="PrintKOT ('<?= $InvoiceNumber ?>')" class="text-white w-100 btn btn-dark hold-button btn-lg p-4"><i class="fa-solid fa-print btn-icon"></i> Print KOT</button>
    </div>
</div>