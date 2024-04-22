<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .tooltip.show {
        top: -62px !important;
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



<div class="container" style="margin-top:98px;background: aliceblue;">
    <div class="table-wrapper">
        <div class="table-title" style="border-radius: 14px;">
            <div class="row">
                <div class="col-sm-6">
                    <h2>Order Details</h2>
                </div>
                <div class="col-sm-6">
                    <a Onclick=location.reload() class="btn btn-primary"><i class="material-icons">&#xE863;</i> <span>Refresh List</span></a>
                    <a href="#" onclick="window.print()" class="btn btn-info"><i class="material-icons">&#xE24D;</i> <span>Print</span></a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center" id="NoOrder">
                <thead style="background-color: rgb(111 202 203);">
                    <tr>
                        <th>Order Id</th>
                        <th>User Email</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Order Date</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT orders.*, users.email FROM orders JOIN users ON orders.userId = users.userId";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $orderId = htmlspecialchars($row['orderId']);
                        $email = htmlspecialchars($row['email']);
                        $amount = htmlspecialchars($row['amount']);
                        $paymentMode = ($row['paymentMode'] == 2) ? "Cash" : "Online";
                        $orderDate = date('F j, Y', strtotime($row['orderDate']));
                        echo "<tr>
                                <td>" . $orderId . "</td>
                                <td>" . $email . "</td>
                                <td>PHP " . $amount . ".00</td>
                                <td>" . $paymentMode . "</td>
                                <td>" . $orderDate . "</td>
                                <td><button href='#' class='btn btn-info' data-toggle='modal' data-target='#orderItem<?= $orderId; ?>'>View</button>
</td>
</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

include 'partials/_dbconnect.php';

function getOrderStatusDescription($status)
{
    $statuses = [
        '0' => 'Order Placed',
        '1' => 'Order Confirmed',
        '2' => 'Preparing your Order',
        '3' => 'Your order is ready, claim it on the counter',
        '4' => 'Order Received',
        '5' => 'Order Denied',
        '6' => 'Cancelled',
    ];
    return $statuses[$status] ?? 'Unknown Status';
}

$itemModalSql = "SELECT o.orderId, o.orderDate, o.amount, oi.prodId, oi.size, o.orderStatus, oi.itemQuantity, p.prodName, ps.price 
                 FROM `orders` o
                 JOIN `orderitems` oi ON o.orderId = oi.orderId
                 JOIN `prod` p ON oi.prodId = p.prodId
                 JOIN `prod_sizes` ps ON oi.prodId = ps.prodId AND oi.size = ps.size
                 WHERE o.userId = $userId
                 ORDER BY o.orderDate DESC";
$itemModalResult = mysqli_query($conn, $itemModalSql);
$orders = []; // Store orders by ID for modal generation

while ($itemModalRow = mysqli_fetch_assoc($itemModalResult)) {
    $orderId = $itemModalRow['orderId'];
    $orderStatus = $itemModalRow['orderStatus'];
    if (!isset($orders[$orderId])) {
        $orders[$orderId] = [
            'orderId' => $orderId,
            'orderDate' => $itemModalRow['orderDate'],
            'orderStatus' => $orderStatus,
            'amount' => $itemModalRow['amount'],
            'items' => []
        ];
    }
    $orders[$orderId]['items'][] = $itemModalRow;
}

foreach ($orders as $order) {
    $orderId = $order['orderId'];
    $orderStatus = getOrderStatusDescription($order['orderStatus']);
?>

    <!-- Order Items Modal -->
    <div class="modal fade" id="orderItem<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-labelledby="orderItemLabel<?php echo $orderId; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemLabel<?php echo $orderId; ?>">Order Details - #<?php echo $orderId; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-dy">
                    <h6>Order Date: <?php echo date('M d, Y', strtotime($order['orderDate'])); ?></h6>
                    <h6>Order Status: <?php echo $orderStatus; ?></h6>
                    <h6>Total Amount: PHP <?php echo number_format($order['amount'], 2); ?></h6>
                    <div class=" table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item) { ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="img/prod-<?php echo $item['prodId']; ?>.jpg" alt="" width="40" class="img-fluid rounded shadow-sm mr-2">
                                                <span><?php echo $item['prodName']; ?></span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center"><?php echo $item['itemQuantity']; ?> pcs.</td>
                                        <td class="align-middle text-center">PHP <?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (!in_array($order['orderStatus'], [5, 6])) : ?>
                        <button onclick='cancelOrder(<?php echo $orderId; ?>)' class='btn btn-danger'>Cancel Order</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cancelOrder(orderId) {
            if (confirm("Are you sure you want to cancel this order?")) {
                $.ajax({
                    url: '/partials/_cancelOrder.php',
                    type: 'POST',
                    data: {
                        orderId: orderId
                    },
                    success: function(response) {
                        alert("Order cancelled successfully.");
                        location.reload();
                    },
                    error: function() {
                        alert("Error cancelling order.");
                    }
                });
            }
        }
    </script>
<?php
}
?>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>