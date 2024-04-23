<?php
// Start the session at the very top of the script, no HTML or whitespace before this line
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Finally, destroy the session itself
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging out</title>
    <script>
        // Redirect with JavaScript
        setTimeout(function() {
            window.location.href = "/index.php";
        }, 3000); // 3000ms = 3 seconds
    </script>
    <style>
        /* Add your CSS styles for the loading spinner and the screen here */
        body {
            text-align: center;
            padding: 150px;
            font-family: "Montserrat", sans-serif;
            color: #333;
            background-color: #f7f7f7;
        }

        #loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="loader"></div>
    <!-- Your HTML content for the logout page goes here -->
    <p>Logging out, please wait...</p>

</body>

</html>