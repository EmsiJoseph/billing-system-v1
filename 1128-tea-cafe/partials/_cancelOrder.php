<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderId'])) {
    $userId = $_SESSION['userId'];
    $orderId = $_POST['orderId'];
    $cancelStatus = 6; // Assuming 6 is the status code for canceled orders

    // Prepare SQL to update the order status
    $stmt = $conn->prepare("UPDATE `orders` SET `orderStatus` = ? WHERE `orderId` = ? AND `userI` = ?");
    $stmt->bind_param("is", $cancelStatus, $orderId);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Order canceled']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel order']);
    }
}
