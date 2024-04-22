<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_sessionStart.php';
    include '_dbconnect.php';

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection
    $sql = $conn->prepare("SELECT userId, password, userType FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();
    $num = $result->num_rows;

    if ($num == 1) {
        $row = $result->fetch_assoc();
        if ($row['userType'] == 1 && password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['adminloggedin'] = true;
            $_SESSION['adminemail'] = $email;
            $_SESSION['adminuserId'] = $row['userId'];
            header("location: /admin/index.php?loginsuccess=true");
            exit();
        } else {
            header("location: /admin/login.php?loginsuccess=false&error=Invalid credentials");
            exit();
        }
    } else {
        header("location: /admin/login.php?loginsuccess=false&error=Invalid credentials");
        exit();
    }
}
