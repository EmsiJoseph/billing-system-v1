<?php require 'partials/_nav.php'; ?>
<?php include 'partials/_dbconnect.php'; ?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
    <title>Cart</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        #cont {
            min-height: 626px;
        }

        .table {
            width: 100%;
        }

        .list-group-item {
            justify-content: space-between;
            /* Ensures items in the list are spaced out between start and end of the list item */
        }

        .list-group-item div.w-100 {
            padding-right: 15px;
            /* Adds padding to align the total price */
        }

        .list-group-item .w-100 {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-group-item .w-100 strong {
            min-width: 50%;
            /* This ensures that the label "Total Price" doesn't jump around */
            text-align: left;
        }
    </style>
</head>

<body>
    <?php if ($loggedin) :
        $totalPrice = 0;
        $totalItems = 0;
    ?>
        <div class="container" id="cont">
            <div class="row">
                <div class="col-lg-12 text-center border rounded bg-light my-3">
                    <h1>My Cart</h1>
                </div>
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Size</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT vc.*, p.prodName, ps.price FROM `viewcart` vc JOIN `prod` p ON vc.prodId = p.prodId JOIN `prod_sizes` ps ON p.prodId = ps.prodId AND vc.size = ps.size WHERE vc.userId = $userId";
                                $result = mysqli_query($conn, $sql);
                                $counter = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $counter++;
                                    $total = $row['price'] * $row['itemQuantity'];
                                    $totalPrice += $total;
                                    $totalItems += $row['itemQuantity'];
                                    echo "<tr>
                                    <td>{$counter}</td>
                                    <td>{$row['prodName']}</td>
                                    <td>{$row['size']}</td>
                                    <td>PHP {$row['price']}</td>
                                    <td>
                                        <input type='number' value='{$row['itemQuantity']}' class='text-center' min='1' onchange='updateCart({$row['cartItemId']}, this.value, {$row['price']})' style='width:60px;'>
                                    </td>
                                    <td id='total{$row['cartItemId']}'>PHP {$total}.00</td>
                                    <td>
                                        <form action='/partials/_manageCart.php' method='POST' style='margin-bottom: 0;'>
                                    <input type='hidden' name='cartItemId' value='{$row['cartItemId']}'>
                                    <input type='hidden' name='size' value='{$row['size']}'> <!-- Add this line -->
                                    <input type='hidden' name='removeItem' value='1'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Remove</button>
                                </form>
                                        </td>
                                </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card wish-list mb-3">
                        <div class="pt-4 border bg-light rounded p-3">
                            <h5 class="mb-3 text-uppercase font-weight-bold text-center">Order summary</h5>
                            <ul class="list-group list-group-flush">
                                <?php
                                mysqli_data_seek($result, 0); // Reset result pointer
                                while ($item = mysqli_fetch_assoc($result)) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                        {$item['prodName']} ({$item['size']})
                        <span>PHP " . number_format($item['price'] * $item['itemQuantity'], 2) . "</span>
                    </li>";
                                }
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                    <div class="w-100 d-flex justify-content-between">
                                        <strong> Total Price:</strong>
                                        <span><strong>PHP <?php echo number_format($totalPrice, 2); ?></strong></span>
                                    </div>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#checkoutModal" <?php if ($totalItems == 0) echo 'disabled'; ?>>Checkout</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <?php else : ?>
        <div class="container" style="min-height : 610px;">
            <div class="alert alert-info my-3">
                <font style="font-size:22px">
                    <center>Before checkout you need to <strong><a class="alert-link" data-toggle="modal" data-target="#loginModal">Login</a></strong></center>
                </font>
            </div>
        </div>
    <?php endif; ?>
    <?php include 'partials/_checkoutModal.php' ?>
    <?php require 'partials/_footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
        function updateOrderSummary() {
            // Get all the rows from the cart table
            let cartRows = document.querySelectorAll('.table tbody tr');
            let summaryHtml = '';
            let newTotalPrice = 0;

            // Loop through each row and calculate the new total price
            cartRows.forEach(row => {
                let name = row.cells[1].innerText;
                let size = row.cells[2].innerText;
                let price = parseFloat(row.cells[3].innerText.replace('PHP ', ''));
                let quantity = parseInt(row.cells[4].querySelector('input').value);
                let total = price * quantity;
                newTotalPrice += total;

                summaryHtml += `
                <li class='list-group-item d-flex justify-content-between align-items-center'>
                    ${name} (${size})
                    <span>PHP ${total.toFixed(2)}</span>
                </li>
            `;


            });

            // Add the total price to the summary
            summaryHtml += `
            <li class='list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3'>
                <div class="w-100 d-flex justify-content-between">
                   <strong> Total Price:</strong>
                    <span><strong>PHP ${newTotalPrice.toFixed(2)}</strong></span>
                </div>
            </li>
        `;

            // Update the order summary's HTML
            document.querySelector('.list-group').innerHTML = summaryHtml;

        }

        function updateCart(cartItemId, qty, price) {
            if (qty < 1) {
                alert("Quantity can't be less than 1.");
                return; // Stop the function if qty is less than 1
            }
            let totalElement = document.querySelector(`#total${cartItemId}`);
            let oldTotal = parseFloat(totalElement.innerText.replace('PHP ', ''));
            let newTotal = qty * price;

            // Update the total for the item
            totalElement.innerText = `PHP ${newTotal.toFixed(2)}`;

            // Update the cart in the backend
            $.ajax({
                url: 'partials/_manageCart.php',
                type: 'POST',
                data: {
                    cartItemId: cartItemId,
                    quantity: qty,
                    update: true
                },
                success: function(response) {
                    console.log("Cart updated!", response);
                    location.reload();
                    updateOrderSummary();
                },
                error: function(error) {
                    console.error("Failed to update cart", error);
                }
            });

        }
        updateOrderSummary();
    </script>

</body>

</html>