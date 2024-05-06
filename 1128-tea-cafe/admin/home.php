<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <br />
        <br />
        <br />
        <h2>Dashboard</h2>
        <div class="row">
            <div class="col-md-4">
                <h4>Total Orders Today</h4>
                <div id="totalOrders"></div>
            </div>
            <div class="col-md-4">
                <h4>Total Revenue Today</h4>
                <div id="totalRevenue"></div>
            </div>
            <div class="col-md-4">
                <h4>Top Selling Products Today</h4>
                <ul id="topProducts"></ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $.getJSON('partials/_dashboardData.php', function(data) {
                $('#totalOrders').text(data.total_orders + ' Orders');
                $('#totalRevenue').text('PHP ' + parseFloat(data.total_revenue).toFixed(2));

                let productsList = '';
                data.top_products.forEach(function(product) {
                    productsList += `<li>${product.prodName} - ${product.quantity_sold} Sold</li>`;
                });
                $('#topProducts').html(productsList);
            });
        });
    </script>
</body>

</html>