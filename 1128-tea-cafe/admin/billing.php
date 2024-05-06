<?php
include 'partials/_dbconnect.php';

$alertMessage = "";
$showModal = false;
if (isset($_POST['processOrder']) && !empty($_POST['orderId'])) {
    $orderId = $_POST['orderId'];
    $sql = "SELECT orders.*, users.nickname FROM orders JOIN users ON orders.userId = users.userId WHERE orders.orderId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $orderDetails = $stmt->get_result()->fetch_assoc();

    if ($orderDetails) {
        if ($orderDetails['orderStatus'] == '2') {
            $alertMessage = "Order already processed";
        } elseif ($orderDetails['orderStatus'] != '1') {
            $alertMessage = "Order is inactive";
        } else {
            $showModal = true;
        }
    } else {
        $alertMessage = "Order ID does not exist.";
    }
}

if (!empty($alertMessage)) {
    echo "<script>alert('$alertMessage');</script>";
}

$sql = "SELECT o.orderId, o.amount, o.orderStatus, LPAD(q.queueNumber, 3, '0') AS queueNumber, o.orderDate 
        FROM orders o
        LEFT JOIN Queue q ON o.orderId = q.orderId
        ORDER BY o.orderDate DESC";

$result = $conn->query($sql);
$incomingOrders = [];
$preparingOrders = [];
$readyForPickupOrders = [];

while ($row = $result->fetch_assoc()) {
    if ($row['orderStatus'] == '1') {
        $incomingOrders[] = $row;
    } elseif ($row['orderStatus'] == '2') {
        $preparingOrders[] = $row;
    } elseif ($row['orderStatus'] == '3') {
        $readyForPickupOrders[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script>
        $(document).ready(function() {
            console.log("jQuery is ready");

            $('#processOrderButton').prop('disabled', true);

            $('#orderIdInput').on('input', function() {
                var inputLength = $(this).val().trim().length;
                $('#processOrderButton').prop('disabled', inputLength === 0);

                console.log("Input length:", inputLength);

                function fetchData() {
                    $.ajax({
                        url: 'partials/_fetchUpdates.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            updateOrders(data);
                        },
                        error: function() {
                            console.error('Failed to fetch data');
                        }
                    });
                }

                function updateOrders(data) {
                    const incomingContainer = $('#incomingOrdersContainer');
                    const preparingContainer = $('#preparingOrdersContainer');
                    const readyContainer = $('#readyForPickupContainer');

                    incomingContainer.empty();
                    preparingContainer.empty();
                    readyContainer.empty();

                    data.forEach(order => {
                        const orderElement = `<div class="card mb-3">
                <div class="card-body">
                    <h5>Order ID: ${order.orderId}</h5>
                    <p>Total: PHP ${parseFloat(order.amount).toFixed(2)}</p>
                    <h3 class="text-center text-${order.orderStatus === '3' ? 'success' : 'primary'}">${order.queueNumber}</h3>
                </div>
            </div>`;

                        if (order.orderStatus === '1') {
                            incomingContainer.append(orderElement);
                        } else if (order.orderStatus === '2') {
                            preparingContainer.append(orderElement);
                        } else if (order.orderStatus === '3') {
                            readyContainer.append(orderElement);
                        }
                    });
                }

                fetchData();

                setInterval(fetchData, 1000);
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .board {
            background-color: #eee;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            position: relative;
        }


        .column {
            height: 100%;
            padding-right: 4px;
        }

        .column-header {
            margin-bottom: 20px;
        }

        .input-background {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>
    <div class="container mt-4">
        <div class="input-background">
            <br />
            <br />
            <br />
            <h2>Order Billing</h2>
            <form action="" method="POST" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Enter Order ID" name="orderId" id="orderIdInput" style="max-width: 300px;">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="processOrder" id="processOrderButton" disabled>Bill</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="board">
            <div class="row text-center position-relative">
                <div class="col-md-4 column">
                    <h4 class="column-header">Incoming</h4>
                    <?php foreach ($incomingOrders as $order) : ?>
                        <div class="card mb-3">
                            <div class=" card-body">
                                <h5>Order ID: <?= $order['orderId'] ?></h5>
                                <p>Total: PHP <?= number_format($order['amount'], 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4 column">
                    <h4 class="column-header">Preparing</h4>
                    <?php foreach ($preparingOrders as $order) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="text-center text-primary"><?= $order['queueNumber'] ?></h3>
                                <h5>Order ID: <?= $order['orderId'] ?></h5>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4 column">
                    <h4 class="column-header">Ready for Pickup</h4>
                    <?php foreach ($readyForPickupOrders as $order) : ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="text-center text-success"><?= $order['queueNumber'] ?></h3>
                                <h5>Order ID: <?= $order['orderId'] ?></h5>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if ($showModal && !empty($orderDetails)) : ?>
            <div class="modal" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailsModalLabel">Billing Order - #<?= $orderDetails['orderId'] ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Customer Nickname:</strong> <?= $orderDetails['nickname'] ?></p>
                            <p><strong>Order Date:</strong> <?= date('M d, Y', strtotime($orderDetails['orderDate'])) ?></p>
                            <p><strong>Total Amount:</strong> PHP <?= number_format($orderDetails['amount'], 2) ?></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsList">
                                        <?php
                                        $itemsSql = "SELECT orderitems.*, prod.prodName, categories.categoryName, prod_sizes.price 
                                         FROM orderitems 
                                         JOIN prod ON orderitems.prodId = prod.prodId 
                                         JOIN categories ON prod.prodCategoryId = categories.categoryId
                                         JOIN prod_sizes ON prod.prodId = prod_sizes.prodId AND orderitems.size = prod_sizes.size
                                         WHERE orderitems.orderId = ?";
                                        $itemsStmt = $conn->prepare($itemsSql);
                                        $itemsStmt->bind_param("s", $orderId);
                                        $itemsStmt->execute();
                                        $itemsResult = $itemsStmt->get_result();
                                        while ($item = $itemsResult->fetch_assoc()) :
                                        ?>
                                            <tr>
                                                <td><?= $item['prodName'] ?></td>
                                                <td><?= $item['categoryName'] ?></td>
                                                <td><?= $item['itemQuantity'] ?></td>
                                                <td>PHP <?= number_format($item['price'], 2) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label for="amountPaid">Amount Paid (PHP):</label>
                                <input type="number" class="form-control" id="amountPaid" placeholder="Enter amount paid by the customer">
                                <button class="btn btn-primary mt-2" onclick="checkAmount()">Enter</button>
                                <button class="btn btn-warning mt-2" onclick="clearAmount()">Clear</button>
                            </div>
                            <div class="form-group" id="changeContainer" style="display: none;">
                                <label>Change:</label>
                                <input type="text" class="form-control" id="changeGiven" readonly>
                                <button type="button" id="giveChangeBtn" class="btn btn-info mt-2" style="display: none;">Give Change</button>
                                <p id="noChangeMsg" class="mt-2" style="display: none;">No Change to be given, confirm order.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="confirmButton" disabled onclick="confirmAndPrepareOrder('<?= $orderDetails['orderId'] ?>')">Prepare Order</button>
                        </div>
                    </div>
                </div>
            </div>



            <script>
                $(document).ready(function() {
                    $('#orderDetailsModal').modal('show');
                });

                function checkAmount() {
                    var amountPaid = parseFloat($('#amountPaid').val());
                    var totalAmount = <?= $orderDetails['amount'] ?>;
                    if (amountPaid >= totalAmount) {
                        var change = amountPaid - totalAmount;
                        if (change > 0) {
                            $('#changeContainer').show();
                            $('#changeGiven').val(`PHP ${change.toFixed(2)}`);
                            $('#giveChangeBtn').show().off().click(function() {
                                $(this).addClass('btn-success').text('Change Given');
                                $('#confirmButton').prop('disabled', false);
                            });
                            $('#noChangeMsg').hide();
                            $('#confirmButton').prop('disabled', true);
                        } else {
                            $('#changeContainer').show();
                            $('#changeGiven').val('No change required');
                            $('#giveChangeBtn').hide();
                            $('#noChangeMsg').show();
                            $('#confirmButton').prop('disabled', false);
                        }
                    } else {
                        $('#changeContainer').hide();
                        $('#giveChangeBtn').hide();
                        $('#noChangeMsg').hide();
                        $('#confirmButton').prop('disabled', true);
                    }
                }

                function clearAmount() {
                    $('#amountPaid').val('');
                    $('#changeContainer').hide();
                    $('#giveChangeBtn').hide();
                    $('#noChangeMsg').hide();
                    $('#confirmButton').prop('disabled', true);
                }

                function confirmAndPrepareOrder(orderId) {
                    if (!orderId) {
                        alert('Invalid order ID.');
                        return;
                    }

                    if ($('#confirmButton').is(':disabled')) {
                        alert('Please ensure the payment is entered and change given if necessary.');
                        return;
                    }

                    $.ajax({
                        url: 'partials/_updateOrderStatus.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            orderId: orderId,
                            newStatus: '2'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                $('#orderDetailsModal').modal('hide');
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error contacting the server: ' + xhr.responseText);
                        }
                    });
                }
            </script>
        <?php endif; ?>
    </div>
</body>

</html>