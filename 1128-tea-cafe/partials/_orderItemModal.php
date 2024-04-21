<?php
$itemModalSql = "SELECT * FROM `orders` WHERE `userId`= $userId";
$itemModalResult = mysqli_query($conn, $itemModalSql);
while ($itemModalRow = mysqli_fetch_assoc($itemModalResult)) {
    $orderid = $itemModalRow['orderId'];
?>

    <!-- Order Items Modal -->
    <div class="modal fade" id="orderItem<?php echo $orderid; ?>" tabindex="-1" role="dialog" aria-labelledby="orderItemLabel<?php echo $orderid; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemLabel<?php echo $orderid; ?>">Order Items - #<?php echo $orderid; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
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
                                    echo '<tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <img src="img/prod-' . $myrow['prodId'] . '.jpg" alt="" width="40" class="img-fluid rounded shadow-sm mr-2">
                                            <span>' . $myrow['prodName'] . '</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">' . $myrow['itemQuantity'] . ' pcs.</td>
                                    <td class="align-middle text-center"><span style="white-space: nowrap;">PHP ' . number_format($myrow['price'], 2) . '</span></td>
                                </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>