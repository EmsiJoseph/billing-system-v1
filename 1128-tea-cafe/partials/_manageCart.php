<?php
error_log(print_r($_POST, true));

ob_start();
include '_sessionStart.php';
if (!session_id()) session_start();

include '_dbconnect.php';
error_log("Script is executing.");

function generateOrderId($conn)
{
    $date = new DateTime();
    $orderNumber = $conn->query("SELECT COUNT(*) FROM `orders`")->fetch_row()[0] + 1;
    $uniqueCode = substr(md5(uniqid(rand(), true)), 0, 5);
    return "ORD" . $date->format("YmdHis") . "-" . str_pad($orderNumber, 5, '0', STR_PAD_LEFT) . "-" . $uniqueCode;
}

function handleCheckout($conn, $userId)
{
    $pickupPersonName = $_POST["pickupPersonName"];
    $pickupPersonPhone = $_POST["pickupPersonPhone"];
    $userTime = $_POST["pickupTime"];
    $paymentMode = $_POST["paymentMode"];
    $totalPrice = $_POST["totalPrice"];

    // Combine the current date with the user provided time
    date_default_timezone_set('Asia/Manila');
    $currentDate = date("Y-m-d"); // Get the current date in YYYY-MM-DD format
    $pickupDateTime = $currentDate . ' ' . $userTime . ':00'; // Combine with the time input

    $conn->begin_transaction();
    try {
        $orderId = generateOrderId($conn);
        $insertOrderStmt = $conn->prepare("INSERT INTO `orders` (orderId, userId, amount, paymentMode, orderStatus, orderDate, pickupPersonName, pickupPersonPhone, pickupTime) VALUES (?, ?, ?, ?, '0', NOW(), ?, ?, ?)");
        $insertOrderStmt->bind_param("sisssss", $orderId, $userId, $totalPrice, $paymentMode, $pickupPersonName, $pickupPersonPhone, $pickupDateTime);
        $insertOrderStmt->execute();

        // Commit transaction only if all operations succeed
        if ($insertOrderStmt->affected_rows > 0) {
            $conn->commit();
            $_SESSION['status'] = "Thanks for ordering with us. Your order id is $orderId.";

            // Fetch items from viewcart and join with prod_sizes to get prices
            $cartItemsSql = "SELECT vc.*, ps.price FROM viewcart vc
                 JOIN prod_sizes ps ON vc.prodId = ps.prodId AND vc.size = ps.size
                 WHERE vc.userId=?";
            $cartStmt = $conn->prepare($cartItemsSql);
            $cartStmt->bind_param("i", $userId);
            $cartStmt->execute();
            $cartItemsResult = $cartStmt->get_result();

            // Prepare statement to insert order items
            $insertItemStmt = $conn->prepare("INSERT INTO orderitems (orderId, prodId, size, itemQuantity, price) VALUES (?, ?, ?, ?, ?)");

            // Insert items into orderitems
            while ($cartItem = $cartItemsResult->fetch_assoc()) {
                // Bind and execute insert for each item with the fetched price
                $insertItemStmt->bind_param("sisid", $orderId, $cartItem['prodId'], $cartItem['size'], $cartItem['itemQuantity'], $cartItem['price']);
                $insertItemStmt->execute();
                if ($insertItemStmt->affected_rows == 0) {
                    throw new Exception("Failed to insert order item.");
                }
            }

            // Assuming the deletion of cart items is performed after successful insertion
            $deleteCartStmt = $conn->prepare("DELETE FROM viewcart WHERE userId=?");
            $deleteCartStmt->bind_param("i", $userId);
            $deleteCartStmt->execute();
            if ($deleteCartStmt->affected_rows == 0) {
                throw new Exception("Failed to clear cart items after order placement.");
            }

            // Check if all operations are successful and commit transaction
            $conn->commit();

            $_SESSION['status'] = "Order placed successfully. Your order ID is $orderId.";
            header("Location: /viewOrder.php");
            exit();
        } else {
            throw new Exception("Order insertion failed.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error placing the order: " . $e->getMessage();
        error_log("Checkout error: " . $e->getMessage());
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userId'];

    if (isset($_POST['addToCart'])) {
        $itemId = $_POST["itemId"];
        $size = $_POST["size"]; // Received from the form
        $quantity = $_POST["quantity"]; // Quantity input


        // Check whether this item with the specific size already exists in the cart
        $existSql = "SELECT * FROM `viewcart` WHERE prodId = '$itemId' AND `userId`='$userId' AND `size`='$size'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);

        if ($numExistRows > 0) {
            // If the item already exists, update the quantity
            $row = mysqli_fetch_assoc($result);
            $newQuantity = $row['itemQuantity'] + $quantity;
            $updateSql = "UPDATE `viewcart` SET `itemQuantity`='$newQuantity' WHERE prodId = '$itemId' AND `userId`='$userId' AND `size`='$size'";
            $updateResult = mysqli_query($conn, $updateSql);
        } else {
            // If the item does not exist, insert it into the cart with the specified size and quantity
            $sql = "INSERT INTO `viewcart` (`userId`, `prodId`, `size`, `itemQuantity`, `addedDate`) VALUES ('$userId', '$itemId', '$size', '$quantity', current_timestamp())";
            $result = mysqli_query($conn, $sql);
        }

        if (isset($updateResult) && $updateResult || isset($result) && $result) {
            $_SESSION['status'] = 'Item Added to Cart.';
        } else {
            $_SESSION['status'] = 'Unable to Add Item.';
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    // Handle Remove Item
    if (isset($_POST['removeItem'])) {
        $cartItemId = $_POST["cartItemId"];
        $size = $_POST["size"];
        $sql = "DELETE FROM `viewcart` WHERE `cartItemId`='$cartItemId' AND `userId`='$userId' AND `size`='$size'";
        if ($conn->query($sql)) {
            $_SESSION['status'] = 'Item Removed.';
        } else {
            $_SESSION['status'] = 'Failed to Remove Item.';
            error_log("Failed to remove item: " . $conn->error);
        }
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }


    // Handle Checkout
    if (isset($_POST['checkout'])) {
        handleCheckout($conn, $userId);
    }

    // AJAX request handling
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $cartItemId = $_POST['cartItemId'];
        $qty = $_POST['quantity'];
        // Using prepared statements for better security
        $updatesql = "UPDATE `viewcart` SET `itemQuantity` = ? WHERE `cartItemId` = ? AND `userId` = ?";
        $stmt = $conn->prepare($updatesql);
        $stmt->bind_param("iii", $qty, $cartItemId, $userId);
        $updateresult = $stmt->execute();
        if ($updateresult) {
            echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
        $stmt->close();
        ob_end_flush();
        exit();
    }
}