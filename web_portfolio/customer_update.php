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

function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, $input);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_option = isset($_POST["account_option"]) ? sanitizeInput($conn, $_POST["account_option"]) : "";

    if ($account_option == "reactive") {
        // Check if the provided password matches the hashed password stored for the user
        $password = isset($_POST["password"]) ? sanitizeInput($conn, $_POST["password"]) : "";
        $username = $_SESSION['username'];

        $checkPasswordQuery = "SELECT password FROM users WHERE username = ?";
        $stmtCheck = mysqli_prepare($conn, $checkPasswordQuery);
        mysqli_stmt_bind_param($stmtCheck, "s", $username);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            mysqli_stmt_bind_result($stmtCheck, $storedPassword);
            mysqli_stmt_fetch($stmtCheck);

            // Verify the provided password against the stored hashed password
            if (password_verify($password, $storedPassword)) {
                // Password is correct, proceed with reactivation
                $reactivateQuery = "UPDATE users SET account_status = 'active' WHERE username = ?";
                $stmt = mysqli_prepare($conn, $reactivateQuery);
                mysqli_stmt_bind_param($stmt, "s", $username);

                if (mysqli_stmt_execute($stmt)) {
                    // Reactivation successful
                    header("Location: login.html"); // Redirect to the appropriate page
                    exit();
                } else {
                    // Reactivation failed
                    echo "Error reactivating user account: " . mysqli_error($conn); // Debug statement
                    exit();
                }
            } else {
                // Password is incorrect
                echo "Incorrect password"; // Debug statement
                exit();
            }
        } else {
            // User not found
            echo "User not found"; // Debug statement
            exit();
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Option not yet implemented."));
    }
}

$conn->close();
?>
