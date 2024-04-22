<div class="container-fluid" style="margin-top:98px">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <form action="partials/_categoryManage.php" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(111 202 203);">
                            Create New Category
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">Category Name: </label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category Description: </label>
                                <input type="text" class="form-control" name="desc" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="createCategory" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover mb-0">
                            <thead style="background-color: rgb(111 202 203);">
                                <tr>
                                    <th class="text-center" style="width:7%;">Id</th>
                                    <th class="text-center" style="width:58%;">Category Detail</th>
                                    <th class="text-center" style="width:18%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `categories` ORDER BY categoryId DESC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $catId = $row['categoryId'];
                                    $catName = $row['categoryName'];
                                    $catDesc = $row['categoryDesc'];

                                    echo '<tr>
                                        <td class="text-center"><b>' . $catId . '</b></td>
                                        <td>
                                            <p>Name : <b>' . $catName . '</b></p>
                                            <p>Description : <b class="truncate">' . $catDesc . '</b></p>
                                        </td>
                                        <td class="text-center">
                                            <div class="row mx-auto" style="width:112px">
                                            <button class="btn btn-sm btn-primary edit_cat" type="button" data-toggle="modal" data-target="#updateCat' . $catId . '">Edit</button>
                                            <form action="partials/_categoryManage.php" method="POST">
                                                <button name="removeCategory" class="btn btn-sm btn-danger" style="margin-left:9px;">Delete</button>
                                                <input type="hidden" name="catId" value="' . $catId . '">
                                            </form></div>
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>


<?php
$catsql = "SELECT * FROM `categories`";
$catResult = mysqli_query($conn, $catsql);
while ($catRow = mysqli_fetch_assoc($catResult)) {
    $catId = $catRow['categoryId'];
    $catName = $catRow['categoryName'];
    $catDesc = $catRow['categoryDesc'];
?>

    <!-- Modal -->
    <div class="modal fade" id="updateCat<?php echo $catId; ?>" tabindex="-1" role="dialog" aria-labelledby="updateCat<?php echo $catId; ?>" aria-hidden="true" style="width: -webkit-fill-available;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(111 202 203);">
                    <h5 class="modal-title" id="updateCat<?php echo $catId; ?>">Category Id: <b><?php echo $catId; ?></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="partials/_categoryManage.php" method="post">
                        <div class="text-left my-2">
                            <b><label for="name">Name</label></b>
                            <input class="form-control" id="name" name="name" value="<?php echo $catName; ?>" type="text" required>
                        </div>
                        <div class="text-left my-2">
                            <b><label for="desc">Description</label></b>
                            <textarea class="form-control" id="desc" name="desc" rows="2" required minlength="6"><?php echo $catDesc; ?></textarea>
                        </div>
                        <input type="hidden" id="catId" name="catId" value="<?php echo $catId; ?>">
                        <button type="submit" class="btn btn-success" name="updateCategory">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>