<?php
include '_dbconnect.php';

header('Content-Type: application/json');

$dateToday = (new DateTime("now", new DateTimeZone('Asia/Manila')))->format('Y-m-d');

// Fetch daily analytics
$analyticsQuery = "SELECT total_orders, daily_revenue, weekly_revenue, monthly_revenue FROM analytics WHERE date = ?";
$stmt = $conn->prepare($analyticsQuery);
$stmt->bind_param("s", $dateToday);
$stmt->execute();
$result = $stmt->get_result();
$todayData = $result->fetch_assoc();

if (!$todayData) {
    $todayData = ['total_orders' => 0, 'daily_revenue' => 0.00, 'weekly_revenue' => 0.00, 'monthly_revenue' => 0.00];
}

// Fetch top selling products today using `orderitems` and `prod` tables for detailed information
$topProductsQuery = "SELECT p.prodName, SUM(oi.itemQuantity) as quantity_sold 
                     FROM orderitems oi 
                     JOIN prod p ON oi.prodId = p.prodId 
                     WHERE DATE(oi.dateAdded) = ? 
                     GROUP BY oi.prodId 
                     ORDER BY quantity_sold DESC 
                     LIMIT 5";
$stmt = $conn->prepare($topProductsQuery);
$stmt->bind_param("s", $dateToday);
$stmt->execute();
$topProductsResult = $stmt->get_result();
$topProducts = $topProductsResult->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'total_orders' => $todayData['total_orders'],
    'daily_revenue' => $todayData['daily_revenue'],
    'weekly_revenue' => $todayData['weekly_revenue'],
    'monthly_revenue' => $todayData['monthly_revenue'],
    'top_products' => $topProducts
]);
