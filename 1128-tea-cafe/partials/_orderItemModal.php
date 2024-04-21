<?php
$itemModalSql = "SELECT * FROM `orders` WHERE `userId`= $userId";
$itemModalResult = mysqli_query($conn, $itemModalSql);
while ($itemModalRow = mysqli_fetch_assoc($itemModalResult)) {
    $orderid = $itemModalRow['orderId'];
?>

    <!-- Modal -->
    <div class="modal fade" id="orderItem<?php echo $orderid; ?>" tabindex="-1" role="dialog" aria-labelledby="orderItem<?php echo $orderid; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItem<?php echo $orderid; ?>">Order Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <!-- Shopping cart table -->
                            <div class="table-responsive">
                                <table class="table text">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="px-3">Item</div>
                                            </th>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="text-center">Quantity</div>
                                            </th>
                                            <th scope="col" class="border-0 bg-light">
                                                <div class="text-center">Price</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $mysql = "SELECT oi.*, p.prodName, ps.price 
                                              FROM `orderitems` oi
                                              JOIN `prod` p ON oi.prodId = p.prodId
                                              JOIN `prod_sizes` ps ON oi.prodId = ps.prodId AND oi.size = ps.size
                                              WHERE orderId = '$orderid'";
                                        $myresult = mysqli_query($conn, $mysql);
                                        while ($myrow = mysqli_fetch_assoc($myresult)) {
                                            $prodName = $myrow['prodName'];
                                            $itemQuantity = $myrow['itemQuantity'];
                                            $prodPrice = $myrow['price'];

                                            echo '<tr>
                                            <th scope="row" class="align-middle">
                                                <div class="p-2">
                                                <img src="img/prod-' . $myrow['prodId'] . '.jpg" alt="" width="70" class="img-fluid rounded shadow-sm">
                                                <div class="ml-3 d-inline-block align-middle">
                                                    <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">' . $prodName . '</a></h5>
                                                </div>
                                                </div>
                                            </th>
                                            <td class="align-middle text-center"><strong>' . $itemQuantity . '</strong></td>
                                            <td class="align-middle text-center">PHP ' . $prodPrice . '</td>
                                        </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>