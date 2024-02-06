<?php

// Replace these with your database credentials
$host = "localhost";
$username = "root";
$password = "pillows143";
$database = "businessdb";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$userData = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Retrieve user data from the database based on the entered username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        // Verify the entered password against the stored hashed password
        if (password_verify($password, $userData['password'])) {
            // Password verification successful, remove the password from the data
            unset($userData['password']);
            $message = "Login successful. User data:";
        } else {
            $message = "Invalid password. Please try again.";
            $userData = array(); // Reset user data if the password is incorrect
        }
    } else {
        $message = "User not found. Please check the username.";
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .message {
            color: #333;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>User Data Display</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Submit">
    </form>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <?php if (!empty($userData)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Number</th>
                <th>Date of Registration</th>
                <th>Account Status</th>
            </tr>
            <tr>
                <td><?php echo $userData['user_id']; ?></td>
                <td><?php echo $userData['firstname']; ?></td>
                <td><?php echo $userData['lastname']; ?></td>
                <td><?php echo $userData['gender']; ?></td>
                <td><?php echo $userData['email']; ?></td>
                <td><?php echo $userData['phone_number']; ?></td>
                <td><?php echo $userData['reg_date']; ?></td>
                <td><?php echo $userData['account_status']; ?></td>
            </tr>
        </table>
    <?php endif; ?>

</body>
</html>
