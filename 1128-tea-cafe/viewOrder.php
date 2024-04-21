<?php require 'partials/_nav.php'; ?>
<?php include 'partials/_dbconnect.php';
?>

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
    </style>

</head>

<body>

    <?php
    if ($loggedin) {
    ?>
        <div class="container mt-4">
            <div class="row">
                <?php
                // Fetch user orders
                $ordersSql = "SELECT orderId, amount, orderDate FROM `orders` WHERE `userId` = ? AND `orderStatus` != 6 ORDER BY `orderDate` DESC";
                $stmt = $conn->prepare($ordersSql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $ordersResult = $stmt->get_result();
                while ($order = $ordersResult->fetch_assoc()) {
                    $timeLeft = 20 * 60 - (time() - strtotime($order['orderDate']));
                    $timeLeft = max($timeLeft, 0); // Ensure the time left is not negative
                    // Format the remaining time
                    $minutes = floor($timeLeft / 60);
                    $seconds = $timeLeft % 60;
                    echo '
                        <div class="col-md-6 mb-4">
                            <div class="card order-card" data-order-id="' . htmlspecialchars($order['orderId']) . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($order['orderId']) . '</h5>
                                    <p class="card-text countdown" data-time-left="' . $timeLeft . '"><strong>Time left to redeem</strong><br><span>' . sprintf("%02d:%02d", $minutes, $seconds) . '</span></p>
                                </div>
                                
                            </div>
                        </div>';
                }

                ?>
            </div>
        </div>
    <?php
    } else {
        echo '<div class="container" style="min-height : 610px;">
        <div class="alert alert-info my-3">
            <font style="font-size:22px"><center>Check your Order. You need to <strong><a class="alert-link" data-toggle="modal" data-target="#loginModal">Login</a></strong></center></font>
        </div></div>';
    }
    ?>

    <?php require 'partials/_footer.php'; ?>

    <script>
        function startCountdown(duration, display) {
            var timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                    // Handle expiration of the countdown
                }
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.countdown').forEach(function(countdown) {
                var orderId = countdown.closest('.order-card').getAttribute('data-order-id');
                var span = countdown.querySelector('span');
                var timeLeft = parseInt(countdown.getAttribute('data-time-left'), 10);

                if (isNaN(timeLeft)) {
                    console.error('Invalid time left value');
                    span.textContent = "00:00";
                    return;
                }

                var timerId = setInterval(function() {
                    if (timeLeft <= 0) {
                        clearInterval(timerId);
                        span.textContent = "00:00";
                        countdown.innerHTML = '<button onclick="removeOrder(\'' + orderId + '\')">Remove</button>';
                        cancelOrder(orderId); // This will call the back-end to update the status to canceled
                        return;
                    }

                    // Update countdown
                    var minutes = parseInt(timeLeft / 60, 10);
                    var seconds = timeLeft % 60;
                    span.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    timeLeft--;
                }, 1000);
            });
        });

        function cancelOrder(orderId) {
            $.ajax({
                url: '/partials/_cancelOrder.php',
                type: 'POST',
                data: {
                    orderId: orderId
                },
                success: function(response) {
                    const card = document.querySelector(`[data-order-id='${orderId}']`);
                    if (card) {
                        card.remove();
                        reflowLayout();
                    }
                    console.log("Order canceled", response);
                },
                error: function(error) {
                    console.error("Failed to cancel order", error);
                }
            });
        }


        function reflowLayout() {
            const container = document.getElementById('orders-container');
            const allCards = container.querySelectorAll('.order-card');

            function removeOrder(orderId) {
                document.querySelector('[data-order-id="' + orderId + '"]').remove();
            }
        }
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>

</html>