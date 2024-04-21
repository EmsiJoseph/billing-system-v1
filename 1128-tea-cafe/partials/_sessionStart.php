<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
    $userId = $_SESSION['userId'];
    $email = $_SESSION['email'];
    $nickname = $_SESSION['nickname'];
} else {
    $loggedin = false;
    $userId = 0;
}
