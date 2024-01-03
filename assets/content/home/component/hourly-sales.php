<?php
// Fetch data for today's sales by hourly intervals
$query = "SELECT HOUR(`current_time`) as hour, SUM(grand_total) as total_sales FROM transaction_invoice WHERE DATE(invoice_date) = '$today' AND `invoice_status` LIKE '2' AND `is_active` = 1 AND `location_id` LIKE '$defaultLocation'  GROUP BY hour";
$result = mysqli_query($link, $query);

// Initialize arrays for labels and data
$HourlySalesLabels = array();
$HourlySalesDataPoints = array();

while ($row = mysqli_fetch_assoc($result)) {
    $HourlySalesLabels[] = $row['hour'] . ':00'; // Hourly labels
    $HourlySalesDataPoints[] = $row['total_sales']; // Hourly sales data
}

?>
<canvas id="salesChart" width="100" height=100"></canvas>

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