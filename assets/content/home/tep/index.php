<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/reporting-functions.php';
include '../../../include/finance-functions.php';
include '../../../include/settings_functions.php';

$UserLevel = $_POST['UserLevel'];
$StudentNumber = $_POST['LoggedUser'];

// Get today's date
$today = date('Y-m-d');
$ClassesCount = $TutorCount = $UsersCount = $ClassesCount = 0;

$Locations = GetLocations($link);
$defaultLocation = GetUserDefaultValue($link, $StudentNumber, 'defaultLocation');
$default_location_name = $Locations[$defaultLocation]['location_name'];
?>
<style>
    .location-title {
        font-weight: 700;
    }

    #date-time {
        font-size: 20px;
    }
</style>

<div class="row mt-5">
    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-chart-line icon-card"></i>
            </div>
            <div class="card-body">
                <p>Total Sales</p>
                <h1><?= formatAccountBalance(getInvoicesByDateAll($link, $today)) ?></h1>
                <div class="badge bg-success"><?= $today ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-money-bill icon-card"></i>
            </div>
            <div class="card-body">
                <p>Total Receipts</p>
                <h1><?= formatAccountBalance(getReceiptsByDateAll($link, $today)) ?></h1>
                <div class="badge bg-success"><?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-3 d-flex">
        <div class="card item-card flex-fill">
            <div class="overlay-box">
                <i class="fa-solid fa-warehouse icon-card"></i>
            </div>
            <div class="card-body">
                <p>Inventory Value</p>
                <h1 class="<?= (getAccountBalance($inventoryAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($inventoryAccountId)) ?></h1>
                <div class="badge bg-success">Up to <?= $today ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-hand-holding-dollar icon-card"></i>
            </div>
            <div class="card-body">
                <p>Accounts Receivable</p>
                <h1 class="<?= (getAccountBalance($accountsReceivableAccountId) < 0) ? 'text-danger' : '' ?>"><?= formatAccountBalance(getAccountBalance($accountsReceivableAccountId)) ?></h1>
                <div class="badge bg-success">Up to <?= $today ?></div>
            </div>
        </div>
    </div>
</div>

<?php


// Fetch data for today's sales by hourly intervals
$query = "SELECT HOUR(`current_time`) as hour, SUM(grand_total) as total_sales FROM transaction_invoice WHERE DATE(invoice_date) = '$today' AND `invoice_status` LIKE '2' AND `is_active` = 1 GROUP BY hour";
$result = mysqli_query($link, $query);

// Initialize arrays for labels and data
$HourlySalesLabels = array();
$HourlySalesDataPoints = array();

while ($row = mysqli_fetch_assoc($result)) {
    $HourlySalesLabels[] = $row['hour'] . ':00'; // Hourly labels
    $HourlySalesDataPoints[] = $row['total_sales']; // Hourly sales data
}


// Fetch total sales for all types
$totalSales = getInvoicesByDateAll($link, $today);
$totalReceipts = getReceiptsByDateAllFilterDated($link, $today);
$creditSales = $totalSales - $totalReceipts;
// Fetch data for cash and credit card payments
$query = "SELECT SUM(`amount`) as sum, type FROM transaction_receipt WHERE `type` IN (0, 1) AND DATE(`current_time`) = '$today' AND `is_active` = 1 AND `today_invoice` = 1 GROUP BY type";
$result = mysqli_query($link, $query);

// Initialize arrays for labels and data
$labels = array();
$dataPoints = array();

// Populate arrays with data
while ($row = mysqli_fetch_assoc($result)) {
    switch ($row['type']) {
        case 0:
            $labels[] = 'Cash';
            break;
        case 1:
            $labels[] = 'Credit Card';
            break;
        default:
            $labels[] = 'Other';
    }

    // Add total sales to the data points array
    $dataPoints[] = $row['sum'];
}
$labels[] = 'Credit';
$dataPoints[] = $creditSales;
?>
<div class="row mt-5">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Hourly Sales | <?= $today ?></div>
                <canvas id="salesChart" width="100" height=100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-title font-weight-bold mb-4 mt-0">Sale Analysis | <?= $today ?></div>
                <canvas id="paymentChart" width="100" height=100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <p class="text-secondary mb-0">Default Location</p>
                <h3 class="location-title mb-0 border-bottom pb-2"><?= $default_location_name ?></h3>
                <div id="date-time"></div>
            </div>
        </div>



    </div>
</div>
<script>
    var delayed;

    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($HourlySalesLabels); ?>,
            datasets: [{
                label: 'Hourly Sales',
                data: <?= json_encode($HourlySalesDataPoints); ?>,
                backgroundColor: 'rgba(88, 89, 133,0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            // animation: {
            //     onComplete: () => {
            //         delayed = true;
            //     },
            //     delay: (context) => {
            //         let delay = 0;
            //         if (context.type === 'data' && context.mode === 'default' && !delayed) {
            //             delay = context.dataIndex * 300 + context.datasetIndex * 100;
            //         }
            //         return delay;
            //     },
            // },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,

        }


    });
</script>



<script>
    var ctx2 = document.getElementById('paymentChart').getContext('2d');
    var paymentChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($dataPoints); ?>,
                backgroundColor: ['rgba(84, 67, 133, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(255, 0, 0, 1)', 'rgba(255, 255, 255, 0.7)'],
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>

<script>
    // Function to update the date and time element
    function updateDateTime() {
        const dateTimeElement = document.getElementById('date-time');
        const currentDate = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        };
        const formattedDate = currentDate.toLocaleDateString('en-US', options);

        dateTimeElement.textContent = formattedDate;
    }

    // Call the function to update the date and time immediately
    updateDateTime();

    // Set an interval to update the date and time every second (1000 milliseconds)
    setInterval(updateDateTime, 1000);
</script>