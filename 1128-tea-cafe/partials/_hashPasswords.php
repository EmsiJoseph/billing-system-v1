<?php
include '_dbconnect.php';

// Fetch all users' passwords
$sql = "SELECT userId, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Hash the user's password
        $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);

        // Update the user's password with the new hash
        $updateSql = $conn->prepare("UPDATE users SET password = ? WHERE userId = ?");
        $updateSql->bind_param("si", $hashedPassword, $row['userId']);
        $updateSql->execute();
    }
    echo "Passwords hashed successfully.";
} else {
    echo "No users found or error fetching users.";
}

$conn->close();
