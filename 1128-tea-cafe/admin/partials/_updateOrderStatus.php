<?php
include '_dbconnect.php';

function getNewQueueNumber($conn)
{
    $today = new DateTime("now", new DateTimeZone('Asia/Manila'));
    $dateToday = $today->format('Y-m-d');
    $stmt = $conn->prepare("SELECT MAX(queueNumber) as maxQueue FROM orders WHERE DATE(orderDate) = ?");
    $stmt->bind_param("s", $dateToday);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $currentMaxQueue = $result['maxQueue'];
    $stmt->close();
    return sprintf('%03d', ($currentMaxQueue + 1));
}

if (isset($_POST['orderId']) && isset($_POST['newStatus'])) {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];
    $queueNumber = null;
    if ($newStatus == '2') {
        $queueNumber = getNewQueueNumber($conn);
    }
    $sql = "UPDATE orders SET orderStatus = ?, queueNumber = COALESCE(?, queueNumber) WHERE orderId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $newStatus, $queueNumber, $orderId);
    if ($stmt->execute()) {
        echo "Order status updated successfully. Queue Number: " . ($queueNumber ? $queueNumber : 'N/A');
    } else {
        echo "Error updating order status.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
