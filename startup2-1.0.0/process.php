<?php
   
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "pillows143";
    $db_name = "businessdb";
    $conn = "";

    try{
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }
    catch(mysqli_sql_exception){
        echo "try again";
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
        $gender = $_POST["gender"];
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $number = filter_input(INPUT_POST, "number", FILTER_SANITIZE_SPECIAL_CHARS);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if(empty($firstname) || empty($lastname)){
            echo "Please enter both first and last names";
        }
        elseif(empty($gender)){
            echo "Please choose a gender";
        }
        elseif(empty($password)){
            echo "Please input a password";
        }
        elseif(empty($number)){
            echo "Please enter your number";
        }
        else{
            $sql = "INSERT INTO users (firstname, lastname, gender, password, email, phone_number, username)
                     VALUES ('$firstname', '$lastname', '$gender', '$hash', '$email', '$number', '$username')";

            try{
                mysqli_query($conn, $sql);
                // echo "You are now registered!";
                // echo '<script>alert("Data inserted successfully");</script>';
                header("Location: login.html");
                exit();
            }
            catch(mysqli_sql_exception $e){
                echo '<script>alert("Error: ' . $e->getMessage() . '");</script>';
            }
        }
    }

    mysqli_close($conn);
?>
