<?php
include '_dbconnect.php';

header('Content-Type: application/json');

function getNewQueueNumber($conn)
{
    $today = new DateTime("now", new DateTimeZone('Asia/Manila'));
    $dateToday = $today->format('Y-m-d');
    $stmt = $conn->prepare("SELECT MAX(queueNumber) as maxQueue FROM queue WHERE dateAdded = ?");
    $stmt->bind_param("s", $dateToday);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $currentMaxQueue = $result ? $result['maxQueue'] : 0;
    $stmt->close();
    return $currentMaxQueue + 1;
}

function updateAnalytics($orderId, $conn)
{
    $dateToday = (new DateTime("now", new DateTimeZone('Asia/Manila')))->format('Y-m-d');
    $orderQuery = "SELECT SUM(price * itemQuantity) AS order_total FROM orderitems WHERE orderId = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("s", $orderId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $orderTotal = $result['order_total'] ?? 0;

    $analyticsUpdate = "INSERT INTO analytics (date, total_orders, daily_revenue) VALUES (?, 1, ?) ON DUPLICATE KEY UPDATE total_orders = total_orders + 1, daily_revenue = daily_revenue + ?";
    $stmt = $conn->prepare($analyticsUpdate);
    $stmt->bind_param("sdd", $dateToday, $orderTotal, $orderTotal);
    if (!$stmt->execute()) {
        return false;
    }
    return true;
}

if (isset($_POST['orderId']) && isset($_POST['newStatus'])) {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];
    $dateToday = (new DateTime("now", new DateTimeZone('Asia/Manila')))->format('Y-m-d');

    $conn->begin_transaction();

    try {
        $analyticsUpdated = updateAnalytics($orderId, $conn);
        if (!$analyticsUpdated) {
            throw new Exception("Failed to update analytics.");
        }

        if ($newStatus == '2') {
            $queueNumber = getNewQueueNumber($conn);
            $stmt = $conn->prepare("INSERT INTO queue (orderId, queueNumber, dateAdded) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $orderId, $queueNumber, $dateToday);
            $stmt->execute();
            $stmt->close();
        } elseif ($newStatus == '3') {
            $stmt = $conn->prepare("DELETE FROM queue WHERE orderId = ?");
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE orders SET orderStatus = ? WHERE orderId = ?");
        $stmt->bind_param("ss", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Order and analytics updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to update order status: ' . $e->getMessage()]);
    }
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
