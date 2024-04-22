<?php
// Connection setup
require '_dbconnect.php';

if (isset($_POST['orderId']) && is_numeric($_POST['orderId'])) {
    $orderId = intval($_POST['orderId']);

    $stmt = $conn->prepare("UPDATE orders SET orderStatus = 6 WHERE orderId = ?");
    $stmt->bind_param("i", $orderId);
    if ($stmt->execute()) {
        echo "Order #$orderId cancelled successfully.";
    } else {
        echo "Error cancelling order.";
    }
} else {
    echo "Invalid request.";
}
