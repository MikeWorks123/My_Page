<?php
session_start();

$db_server = "localhost";
$db_user = "root";
$db_pass = "pillows143";
$db_name = "businessdb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Check for database connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedOption = $_POST['account_option'];

    switch ($selectedOption) {
        case "update":
            // Validate and sanitize the form data as needed
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            // Add more fields as needed

            // Update user details in the database using prepared statement
            $updateQuery = "UPDATE users SET email = ? WHERE username = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ss", $email, $_SESSION['username']);

            if (mysqli_stmt_execute($stmt)) {
                // Update successful
                echo "Update successful"; // Debug statement
                header("Location: account_settings.php");
                exit();
            } else {
                // Update failed
                echo "Error updating user details: " . mysqli_error($conn); // Debug statement
                exit();
            }

        case "deactivate":
            // Update the user account status to inactive in the database using prepared statement
            $deactivateQuery = "UPDATE users SET account_status = 'inactive' WHERE username = ?";
            $stmt = mysqli_prepare($conn, $deactivateQuery);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);

            if (mysqli_stmt_execute($stmt)) {
                // Deactivation successful
                echo "Deactivation successful"; // Debug statement
                header("Location: account_settings.php");
                exit();
            } else {
                // Deactivation failed
                echo "Error deactivating user account: " . mysqli_error($conn); // Debug statement
                exit();
            }

        case "delete":
            // Delete user data using prepared statement
            $deleteDataQuery = "DELETE FROM users WHERE username = ?";
            $stmt = mysqli_prepare($conn, $deleteDataQuery);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);

            if (mysqli_stmt_execute($stmt)) {
                // Logout the user
                session_unset();
                session_destroy();
                echo "Account deletion successful"; // Debug statement
                // Redirect to a page after account deletion
                header("Location: login.html");
                exit();
            } else {
                // Deletion failed
                echo "Error deleting user account: " . mysqli_error($conn); // Debug statement
                exit();
            }
    }
} else {
    // If the form is not submitted, redirect to the account settings page
    header("Location: account_settings.php");
    exit();
}
?>
