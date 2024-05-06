<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .card {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daily Revenue</h5>
                        <p class="card-text" id="dailyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Weekly Revenue</h5>
                        <p class="card-text" id="weeklyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Revenue</h5>
                        <p class="card-text" id="monthlyRevenue">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders Today</h5>
                        <p class="card-text" id="totalOrders">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="salesOverTime"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchData() {
                $.ajax({
                    url: 'dashboard_data.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#dailyRevenue').text(`PHP ${parseFloat(data.daily_revenue).toFixed(2)}`);
                        $('#weeklyRevenue').text(`PHP ${parseFloat(data.weekly_revenue).toFixed(2)}`);
                        $('#monthlyRevenue').text(`PHP ${parseFloat(data.monthly_revenue).toFixed(2)}`);
                        $('#totalOrders').text(data.total_orders);

                        var ctx = document.getElementById('salesOverTime').getContext('2d');
                        var salesOverTime = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Revenue Over Time',
                                    data: data.revenue_over_time,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    },
                    error: function() {
                        console.error('Failed to fetch dashboard data');
                    }
                });
            }

            fetchData();
            setInterval(fetchData, 60000); // Refresh every minute
        });
    </script>
</body>

</html>