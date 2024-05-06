<?php
header('Content-Type: application/json');

$server = "db";
$username = "root";
$password = "root";
$database = "billing-system-db";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}
try {
    $today = date('Y-m-d');
    $query = "SELECT total_orders, daily_revenue, weekly_revenue, monthly_revenue FROM analytics WHERE date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        throw new Exception('No data available for today.');
    }

    $response = [
        'daily_revenue' => $result['daily_revenue'],
        'weekly_revenue' => $result['weekly_revenue'],
        'monthly_revenue' => $result['monthly_revenue'],
        'total_orders' => $result['total_orders'],
        'labels' => [],
        'revenue_over_time' => []
    ];

    $query = "SELECT date, daily_revenue FROM analytics ORDER BY date DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        array_unshift($response['labels'], $row['date']);
        array_unshift($response['revenue_over_time'], $row['daily_revenue']);
    }

    echo json_encode(['success' => true, 'data' => $response]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
