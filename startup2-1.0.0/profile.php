<?php
// Ensure session is started if not done already
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.html");
    exit();
}

// Fetch user data from the database based on $_SESSION['user_id']
$conn = mysqli_connect("localhost", "root", "pillows143", "businessdb");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']); // Using $_SESSION['user_id']
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Now $user should contain the user data, including 'user_id'
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        Hii
    </body>
    </html>

    <?php
} else {
    echo "User not found."; // Handle case when no user is found
}

mysqli_close($conn);
?>
