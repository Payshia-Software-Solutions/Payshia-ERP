<?php
// Fetch data for current month's sales by daily intervals
$query = "SELECT DATE(`invoice_date`) as day, SUM(grand_total) as total_sales 
          FROM transaction_invoice 
          WHERE YEAR(invoice_date) = YEAR(CURDATE()) 
            AND MONTH(invoice_date) = MONTH(CURDATE()) 
            AND `invoice_status` LIKE '2' 
            AND `is_active` = 1 
            AND `location_id` LIKE '$defaultLocation'  
          GROUP BY day";
$result = mysqli_query($link, $query);

// Initialize arrays for labels and data
$MonthlySalesLabels = array();
$MonthlySalesDataPoints = array();
$CumulativeSalesDataPoints = array();

$totalCumulativeSales = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $day = date('j', strtotime($row['day'])); // Extract day from the date
    $MonthlySalesLabels[] = $day; // Daily labels with only the day
    $totalCumulativeSales += $row['total_sales'];
    $MonthlySalesDataPoints[] = $row['total_sales']; // Daily sales data
    $CumulativeSalesDataPoints[] = $totalCumulativeSales; // Cumulative sales data
}

?>
<div class="table-title font-weight-bold mb-4 mt-0">Daily Sales - <?= $default_location_name ?> | <?= date('M') ?></div>
<canvas id="monthlySalesChart" width="100" height="50"></canvas>

<script>
    var delayed;

    var ctx = document.getElementById('monthlySalesChart').getContext('2d');
    var monthlySalesChart = new Chart(ctx, {
        type: 'line',
        cubicInterpolationMode: 'monotone',
        data: {
            labels: <?= json_encode($MonthlySalesLabels); ?>,
            datasets: [{
                label: 'Daily Sales',
                data: <?= json_encode($MonthlySalesDataPoints); ?>,
                backgroundColor: 'rgba(88, 89, 133,0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                pointRadius: 5, // Adjust point radius as needed
                lineTension: 0.4 // Set line tension to 0.1 for a smoother curve
            }, {
                label: 'Cumulative Sales',
                data: <?= json_encode($CumulativeSalesDataPoints); ?>,
                backgroundColor: 'rgba(255, 34, 46, 0.3)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true,
                pointStyle: 'rectRounded',
                pointRadius: 5, // Adjust point radius as needed
                lineTension: 0.4 // Set line tension to 0.1 for a smoother curve
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'logarithmic',
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>