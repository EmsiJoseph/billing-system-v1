<?php

// Include the database connection from _dbconnect.php or adjust the path as needed
require '_dbconnect.php';

// Set the content type to application/json for proper ajax response handling
header('Content-Type: application/json');

// Initialize an array to hold the response
$response = [
    'success' => false,
    'orderStatus' => null,
    'statusText' => '',
    'message' => 'No data'
];

// Check if the POST variable for orderId is set and is a number
if (isset($_POST['orderId']) && ctype_digit($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    // Prepare the SQL statement to fetch the order status
    $stmt = $conn->prepare("SELECT orderStatus FROM orders WHERE orderId = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the order status
        $orderData = $result->fetch_assoc();
        $orderStatus = $orderData['orderStatus'];
        $statusText = getOrderStatusDescription($orderStatus);

        // Populate the response array with order status data
        $response['success'] = true;
        $response['orderStatus'] = $orderStatus;
        $response['statusText'] = $statusText;
        $response['message'] = 'Order status fetched successfully.';
    } else {
        // Order not found
        $response['message'] = 'Order not found.';
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // orderId POST variable is not set or is not a number
    $response['message'] = 'Invalid request.';
}

// Close the database connection
$conn->close();

// Convert the response array to JSON format and print it
echo json_encode($response);


function getOrderStatusDescription($status)
{
    $statuses = [
        '0' => 'Order Placed',
        '1' => 'Order Confirmed',
        '2' => 'Preparing Your Order',
        '3' => 'Ready for Pickup',
        '4' => 'Order Completed',
        '5' => 'Order Denied',
        '6' => 'Cancelled',
        // ... add other statuses as needed
    ];

    return $statuses[$status] ?? 'Unknown Status';
}
