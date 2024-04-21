<?php
$server = "db";  // Or use "docker.for.win.localhost" or "docker.for.mac.localhost" as needed
$nickname = "user";
$password = "password";
$database = "billing-system-db";

$conn = mysqli_connect($server, $nickname, $password, $database);
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}
