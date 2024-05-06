<?php
include '_dbconnect.php';

$sql = "SELECT o.orderId, o.amount, o.orderStatus, LPAD(q.queueNumber, 3, '0') AS queueNumber, o.orderDate 
        FROM orders o
        LEFT JOIN queue q ON o.orderId = q.orderId
        ORDER BY o.orderDate DESC";
$result = $conn->query($sql);
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
