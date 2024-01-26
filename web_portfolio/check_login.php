<?php
session_start();

$db_server = "localhost";
$db_user = "root";
$db_pass = "pillows143";
$db_name = "businessdb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT user_id, password, account_status FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['account_status'] === 'active') {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['user_id']; // Store 'user_id' in the session
                header("Location: index.php");
                exit();
            } else {
                // Account is inactive
                mysqli_close($conn);
                echo '<script>alert("Your account is inactive. Contact support for assistance.");</script>';
                header("Location: login.html");
                exit();
            }
        } else {
            mysqli_close($conn);
            echo '<script>alert("Incorrect username or password");</script>';
            header("Location: login.html");
            exit();
        }
    } else {
        // Handle error or log it for debugging
        echo '<script>alert("Error in login");</script>';
    }
}

// Close connection if not already closed
mysqli_close($conn);
?>
