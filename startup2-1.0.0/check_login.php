<?php
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

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            echo '<script>alert("Login successful");</script>';
            header("Location: index.html");
        } else {
            
            header("Location: login.html");
            echo '<script>alert("Incorrect username or password");</script>';
        }
    } else {
        echo '<script>alert("Error in login");</script>';
    }
}

mysqli_close($conn);
?>
