<?php require 'partials/_nav.php' ?>

<?php
include 'partials/_dbconnect.php';
$id = $_GET['catid'];
$sql = "SELECT * FROM `categories` WHERE categoryId = $id";
$result = mysqli_query($conn, $sql);
$catname = "Category";
$catdesc = "Category description";
if ($row = mysqli_fetch_assoc($result)) {
    $catname = $row['categoryName'];
    $catdesc = $row['categoryDesc'];
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
    <title id="title"><?php echo htmlspecialchars($catname); ?></title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        .jumbotron {
            padding: 2rem 1rem;
        }

        #cont {
            min-height: 570px;
        }

        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
            /* Ensures all cards are of equal height */
        }

        .card-body {
            flex-grow: 1;
            /* Allows the card body to expand and fill available space */
        }

        .card-img-top {
            height: 200px;
            /* Fixed height for all images */
            object-fit: cover;
            /* Ensures the image covers the area without being stretched */
        }

        .card .btn {
            margin-top: 0.5rem;
            /* Add space on the top of the buttons */
            margin-bottom: 0.5rem;
            /* Add space on the bottom of the buttons */
        }

        /* Adjustments for smaller screens */
        @media (max-width: 767px) {
            .card .btn {
                margin-top: 0.5rem;
                margin-bottom: 0.1rem;
                width: 100%;
                /* Make buttons full width on smaller screens */
            }
        }

        .btn-group {
            display: block;
        }

        /* Ensure that buttons have a uniform style */
        .btn-group .btn {
            width: 100%;
            margin: 0.25rem 0;
        }

        /* Adjustments for the 'Quick View' button to align properly */
        .btn-secondary {
            background-color: #6c757d;
            /* Default secondary button color */
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            /* Darker on hover */
        }

        /* Icon and text spacing for buttons with icons */
        .btn-icon-left {
            display: inline-flex;
            align-items: center;
        }

        .btn-icon-left .fa-icon {
            margin-right: 0.5rem;
            /* Space between icon and text */
        }

        .btn .fa-icon,
        .btn .fas {
            margin-right: 0.5rem;
            /* Space between icon and text */
        }
    </style>
</head>

<body>
    <div>&nbsp;
        <?php require 'partials/_backToAllCat.php' ?>
    </div>
    <div class="container my-3" id="cont">
        <div class="col-lg-4 text-center bg-light my-3 category-title-container" style="margin: auto;">
            <h2 class="text-center"><span id="catTitle"><?php echo htmlspecialchars($catname); ?></span></h2>
        </div>
        <div class="col-lg-4 text-center bg-light my-3 category-title-container" style="margin: auto;">
            <p class="text-center"><span id="catDesc"><?php echo htmlspecialchars($catdesc); ?></span></p>
        </div>
        &nbsp;
        <div class="row">
            <?php
            $id = $_GET['catid'];
            $sql = "SELECT p.prodId, p.prodName, p.prodDesc, ps.size, ps.price
            FROM `prod` p
            JOIN `prod_sizes` ps ON p.prodId = ps.prodId
            WHERE p.prodCategoryId = $id";
            $result = mysqli_query($conn, $sql);
            $noResult = true;

            // Fetch products with sizes and prices
            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $noResult = false;
                $products[$row['prodId']]['prodName'] = $row['prodName'];
                $products[$row['prodId']]['prodDesc'] = $row['prodDesc'];
                $products[$row['prodId']]['sizes'][$row['size']] = $row['price'];
            }

            // Display products with sizes and prices
            foreach ($products as $prodId => $prodData) {
                echo '<div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card">
                <img src="img/prod-' . $prodId . '.jpg" class="card-img-top" alt="image for ' . $prodData['prodName'] . '">
                <div class="card-body">
                    <h5 class="card-title">' . substr($prodData['prodName'], 0, 100) . '</h5>';
                foreach ($prodData['sizes'] as $size => $price) {
                    echo '<h6 style="color: #ff0000">' . $size . ' - PHP ' . $price . '.00 </h6>';
                }
                echo '<p class="card-text">' . substr($prodData['prodDesc'], 0, 35) . '...</p>
                <div class="btn-group" role="group" aria-label="Product actions">';
                // Determine which button to show based on user login status
                if ($loggedin) {
                    // Add to cart button triggers the modal
                    echo '<button type="button" class="btn btn-primary" onclick="initializeModal(' . $prodId . ', \'' . htmlspecialchars(json_encode($prodData['sizes']), ENT_QUOTES, 'UTF-8') . '\')" data-toggle="modal" data-target="#addToCartModal"><i class="fas fa-shopping-cart fa-icon"></i>Add to cart</button>';
                    // More information button remains outside the form
                    echo '<a href="viewProd.php?prodid=' . $prodId . '" class="btn btn-secondary"><i class="fas fa-info-circle fa-icon"></i>More Information</a>';
                } else {
                    // If not logged in, show login modal on click
                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#loginModal"><i class="fas fa-shopping-cart fa-icon"></i>Add to cart</button>';
                    echo '<a href="viewProd.php?prodid=' . $prodId . '" class="btn btn-secondary"><i class="fas fa-info-circle fa-icon"></i>More Information</a>';
                }
                echo '</div>
            </div>
        </div>
    </div>';
            }
            if ($noResult) {
                echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="display-4">No items available in this category.</p>
                        <p class="lead">We will update soon.</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
    <!-- Include the add to cart modal -->
    <?php require 'partials/_addToCartModal.php'; ?>
    <?php require 'partials/_footer.php'; ?>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>


    <!-- Add to cart script -->
    <script>
        // Function to update total price based on selected size and quantity
        function updateTotalPrice(pricePerUnit) {
            let quantity = $('#quantityInput').val() || 1;
            let total = quantity * pricePerUnit;
            $('#totalPriceDisplay').val('PHP ' + total.toFixed(2));
            $('#totalPrice').val(total.toFixed(2)); // This is the value that will be submitted
        }

        // Event listener for size selection
        $(document).on('change', 'input[name="size"]', function() {
            let pricePerUnit = $(this).data('price');
            updateTotalPrice(pricePerUnit);
        });

        // Quantity change event listener
        $('#quantityInput').on('input change', function() {
            let pricePerUnit = $('input[name="size"]:checked').data('price');
            updateTotalPrice(pricePerUnit);
        });

        // Function to initialize the modal with size buttons
        function initializeModal(productId, sizesJson) {
            var sizes = JSON.parse(sizesJson);
            var buttonsHtml = '';
            var defaultSize = 'Large'; // Set the default size to 'Large'
            var defaultPrice = 0;

            for (var size in sizes) {
                buttonsHtml += `<label class="btn btn-secondary ${size === defaultSize ? 'active' : ''}">
                                <input type="radio" name="size" value="${size}" autocomplete="off" data-price="${sizes[size]}" ${size === defaultSize ? 'checked' : ''}> ${size} - PHP ${sizes[size]}
                            </label>`;
                if (size === defaultSize) {
                    defaultPrice = sizes[size]; // Set default price for 'Large'
                }
            }

            $('#addToCartModal .btn-group').html(buttonsHtml);
            $('#addToCartItemId').val(productId);
            $('#addToCartModal').modal('show');

            // Set initial quantity and price
            $('#quantityInput').val(1);
            updateTotalPrice(defaultPrice);
        }
    </script>


</body>

</html>