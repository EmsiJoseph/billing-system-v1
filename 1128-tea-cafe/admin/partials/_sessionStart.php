<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
    $_SESSION['adminloggedin'] = true;
    $userId = $_SESSION['adminuserId'];
    $email = $_SESSION['adminemail'];
    $nickname = $_SESSION['nickname'];
} else {
    $loggedin = false;
    $userId = 0;
}
