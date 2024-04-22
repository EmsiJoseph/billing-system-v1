<?php
include 'partials/_sessionStart.php';
include 'partials/_dbconnect.php';

$systemName = "Default System Name";
$categories = [];
$userId = 0;
$count = 0;
// $nickname = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && isset($_SESSION['nickname']) ? $_SESSION['nickname'] : 'User';

// Start session
if (
  session_status() === PHP_SESSION_NONE
) {
  session_start();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  $loggedin = true;
  $userId = $_SESSION['userId'];
} else {
  $loggedin = false;
}

// Fetch system name
if ($result = mysqli_query($conn, "SELECT * FROM `sitedetail`")) {
  if ($row = mysqli_fetch_assoc($result)) {
    $systemName = $row['systemName'];
  }
} else {
  // Handle error
  $error = mysqli_error($conn);
}

// Fetch categories
$categoryQuery = "SELECT categoryName, categoryId FROM `categories`";
if ($result = mysqli_query($conn, $categoryQuery)) {
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
  }
} else {
  // Handle error
  $error = mysqli_error($conn);
}

// Fetch cart count
if ($loggedin) {
  $countsql = "SELECT SUM(`itemQuantity`) AS itemCount FROM `viewcart` WHERE `userId`=?";
  if ($stmt = mysqli_prepare($conn, $countsql)) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $count = $count ?: 0; // Ensure count is not null
  }
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php"><?= htmlspecialchars($systemName) ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Menu</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php foreach ($categories as $category) : ?>
            <a class="dropdown-item" href="viewProdList.php?catid=<?= $category['categoryId'] ?>"><?= htmlspecialchars($category['categoryName']) ?></a>
          <?php endforeach; ?>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewOrder.php">Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact Us</a>
      </li>
    </ul>
    <form method="get" action="/1128-tea-cafe/search.php" class="form-inline my-2 my-lg-0 mx-3">
      <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Search" aria-label="Search" required>
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <a href="viewCart.php" class="btn btn-secondary mx-2" title="My Cart">
      <i class="fas fa-shopping-cart"></i> Cart (<span id="cartCount"><?= $count ?></span>)
    </a>
    <?php if ($loggedin) : ?>
      <ul class="navbar-nav mr-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown">Welcome <?= htmlspecialchars($_SESSION['nickname']) ?></a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
            <a class="dropdown-item" href="partials/_logout.php">Logout</a>
          </div>
        </li>
      </ul>
      <div class="text-center image-size-small position-relative">
        <a href="viewProfile.php"><img src="img/person-<?= $userId ?>.jpg" class="rounded-circle" onError="this.src = 'img/profilePic.jpg'" style="width:40px; height:40px"></a>
      </div>
    <?php else : ?>
      <button type="button" class="btn btn-success mx-2" data-toggle="modal" data-target="#loginModal">Login</button>
      <button type="button" the "btn btn-success mx-2" data-toggle="modal" data-target="#signupModal">SignUp</button>
    <?php endif; ?>
  </div>
</nav>

<?php
include 'partials/_loginModal.php';
include 'partials/_signupModal.php';

// Alert messages for sign up and login feedback
if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] === "true") {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> You can now login.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>';
} elseif (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] === "false") {
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Warning!</strong> ' . htmlspecialchars($_GET['error']) . '
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>';
}

if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] === "true") {
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> You are logged in
          <button type="button" the "close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>';
} elseif (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] === "false") {
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Warning!</strong> Invalid Credentials
          <button type="button" the "close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>';
}
?>