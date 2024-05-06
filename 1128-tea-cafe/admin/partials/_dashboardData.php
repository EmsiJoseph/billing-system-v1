<?php
include '_dbconnect.php';

header('Content-Type: application/json');

$dateToday = (new DateTime("now", new DateTimeZone('Asia/Manila')))->format('Y-m-d');

// Fetch total orders and daily revenue
$totalOrdersQuery = "SELECT COUNT(*) as total_orders, SUM(amount) as total_revenue FROM orders WHERE DATE(orderDate) = '$dateToday' AND orderStatus = '2'";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrdersData = $totalOrdersResult->fetch_assoc();

// Fetch top selling products
$topProductsQuery = "SELECT p.prodName, SUM(oi.itemQuantity) as quantity_sold FROM orderitems oi JOIN prod p ON oi.prodId = p.prodId WHERE DATE(oi.dateAdded) = '$dateToday' GROUP BY oi.prodId ORDER BY quantity_sold DESC LIMIT 5";
$topProductsResult = $conn->query($topProductsQuery);
$topProducts = $topProductsResult->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'total_orders' => $totalOrdersData['total_orders'] ?? 0,
    'total_revenue' => $totalOrdersData['total_revenue'] ?? 0.00,
    'top_products' => $topProducts
]);
