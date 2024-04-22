<?php require 'partials/_nav.php'; ?>
<?php include 'partials/_dbconnect.php'; ?>
<?php include 'partials/_orderItemModal.php'; ?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title>Your Order</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        .footer {
            position: fixed;
            bottom: 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            margin: 30px auto;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-wrapper .btn {
            float: right;
            color: #333;
            background-color: #fff;
            border-radius: 3px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-wrapper .btn:hover {
            color: #333;
            background: #f2f2f2;
        }

        .table-wrapper .btn.btn-primary {
            color: #fff;
            background: #03A9F4;
        }

        .table-wrapper .btn.btn-primary:hover {
            background: #03a3e7;
        }

        .table-title .btn {
            font-size: 13px;
            border: none;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        .table-title {
            color: #fff;
            background: #4b5366;
            padding: 16px 25px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 80px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.view {
            width: 30px;
            height: 30px;
            color: #2196F3;
            border: 2px solid;
            border-radius: 30px;
            text-align: center;
        }

        table.table td a.view i {
            font-size: 22px;
            margin: 2px 0 0 1px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        table {
            counter-reset: section;
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }

        .card.order-card {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .card.order-card:hover {
            background-color: #f8f9fa;
        }
    </style>

</head>

<body>
    <?php if ($loggedin) {
        function updateOrderStatus($conn, $userId)
        {
            $currentTime = time();
            $stmt = $conn->prepare("SELECT orderId, orderDate FROM orders WHERE userId = ? AND orderStatus NOT IN (6)");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($order = $result->fetch_assoc()) {
                $orderTime = strtotime($order['orderDate']);
                $timePassed = $currentTime - $orderTime;

                if ($timePassed > 1200) { // 20 minutes * 60 seconds
                    $updateStmt = $conn->prepare("UPDATE orders SET orderStatus = 6 WHERE orderId = ?");
                    $updateStmt->bind_param("s", $order['orderId']);
                    $updateStmt->execute();
                }
            }
        }
        updateOrderStatus($conn, $userId);
    }
    ?>

    <?php if ($loggedin) : ?>

        <div class="container mt-4">
            <h1 class="text-center">My Orders</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#claimable">Claimable</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cancelled">Cancelled</a>
                </li>
            </ul>

            <div class="tab-content">
                <br>
                <div id="claimable" class="tab-pane fade show active">
                    <div class="row">
                        <?php
                        $stmt = $conn->prepare("SELECT orderId, amount, orderDate, UNIX_TIMESTAMP(orderDate) AS timestamp FROM orders WHERE userId = ? AND orderStatus NOT IN (6) ORDER BY orderDate DESC");
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $currentTime = time();
                        while ($order = $result->fetch_assoc()) {
                            $timeLeft = max(0, 20 * 60 - ($currentTime - $order['timestamp']));
                            $minutes = floor($timeLeft / 60);
                            $seconds = $timeLeft % 60;
                            echo "
                            <div class='col-md-6 mb-4'>
                                <div class='card order-card' data-toggle='modal' data-target='#orderItem" . htmlspecialchars($order['orderId']) . "'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Order #" . htmlspecialchars($order['orderId']) . "</h5>
                                        <p class='card-text'>Total: PHP " . number_format($order['amount'], 2) . "</p>
                                        <p class='card-text countdown' data-time-left='$timeLeft'><strong>Time left to claim:</strong> <span>$minutes:$seconds</span></p>
                                    </div>
                                </div>
                            </div>";
                        }
                        if ($result->num_rows === 0) {
                            echo '
                        <div class="text-center w-100">
                            <p>No claimable orders found.</p>
                            <p><a href="/viewCart.php" class="btn btn-primary">Checkout at Cart</a>  or  <a href="/index.php" class="btn btn-primary">Browse Menu</a></p>
                        </div>';
                        }
                        ?>
                    </div>

                </div>
                <div id="cancelled" class="tab-pane fade">
                    <div class="row">
                        <?php
                        $stmt = $conn->prepare("SELECT orderId, amount, orderDate, orderStatus FROM orders WHERE userId = ? AND orderStatus IN (5, 6) ORDER BY orderDate DESC");
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($order = $result->fetch_assoc()) {
                            $statusMessage = ($order['orderStatus'] == 5) ? 'Order Denied' : 'Order Cancelled';
                            echo "
                            <div class='col-md-6 mb-4'>
                                <div class='card order-card' data-toggle='modal' data-target='#orderItem" . htmlspecialchars($order['orderId']) . "'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Order #" . htmlspecialchars($order['orderId']) . "</h5>
                                        <p class='card-text'>Ordered on " . date('M d, Y', strtotime($order['orderDate'])) . "</p>
                                        <p class='card-text'>Total: PHP " . number_format($order['amount'], 2) . "</p>
                                        <p class='card-text text-muted'>" . $statusMessage . "</p>
                                    </div>
                                </div>
                            </div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="container" style="min-height: 610px;">
            <div class="alert alert-info my-3">
                <strong>You need to <a href="#" data-toggle="modal" data-target="#loginModal">Login</a> to view your orders.</strong>
            </div>
        </div>
    <?php endif; ?>


    <?php require 'partials/_footer.php'; ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countdownElements = document.querySelectorAll('.countdown');
            countdownElements.forEach(function(countdown) {
                var orderId = countdown.closest('.order-card').getAttribute('data-order-id');
                var timeLeft = parseInt(countdown.dataset.timeLeft, 10);
                var span = countdown.querySelector('span');

                var timerId = setInterval(function() {
                    if (timeLeft <= 0) {
                        clearInterval(timerId);
                        span.textContent = '00:00';
                        // Automatically update order status to cancelled if not already done
                        if (!countdown.closest('.order-card').classList.contains('cancelled')) {
                            cancelOrder(orderId, true); // Pass true to indicate automatic cancellation
                        }
                    } else {
                        var minutes = Math.floor(timeLeft / 60);
                        var seconds = timeLeft % 60;
                        span.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                        timeLeft--;
                    }
                }, 1000);
            });
        });


        function cancelOrder(orderId, automatic = false) {
            $.ajax({
                url: '/partials/_cancelOrder.php',
                type: 'POST',
                data: {
                    orderId: orderId,
                    automatic: automatic
                },
                success: function(response) {
                    const card = document.querySelector(`[data-order-id='${orderId}']`);
                    if (card) {
                        moveToCancelledTab(card);
                        console.log("Order canceled", response);
                    }
                },
                error: function(error) {
                    console.error("Failed to cancel order", error);
                }
            });
        }

        function moveToCancelledTab(card) {
            const cancelledTab = document.querySelector('#cancelled .row');
            if (cancelledTab) {
                card.querySelector('.countdown').remove();
                cancelledTab.appendChild(card);
            }
        }

        function pollOrderStatusUpdates(orderId) {
            setInterval(function() {
                $.ajax({
                    url: '/partials/_checkOrderStatus.php',
                    type: 'POST',
                    data: {
                        orderId: orderId
                    },
                    success: function(response) {
                        var status = response.orderStatus;
                        var statusText = response.statusText;
                        var orderCard = document.querySelector(`[data-order-id='${orderId}']`);
                        if (orderCard) {
                            var statusElement = orderCard.querySelector('.order-status');
                            if (!statusElement) {
                                statusElement = document.createElement('p');
                                statusElement.classList.add('order-status');
                                orderCard.querySelector('.card-body').appendChild(statusElement);
                            }
                            statusElement.textContent = `Status: ${statusText}`;

                            if (![0, 1, 2, 3, 4].includes(status)) {
                                var countdownElement = orderCard.querySelector('.countdown');
                                if (countdownElement) {
                                    countdownElement.remove();
                                }
                            }
                        }
                    },
                    error: function(error) {
                        console.error("Failed to check order status", error);
                    }
                });
            }, 5000);
        }

        // Call this function for each order on page load
        document.querySelectorAll('.order-card').forEach(function(card) {
            var orderId = card.getAttribute('data-order-id');
            pollOrderStatusUpdates(orderId);
        });
    </script>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>