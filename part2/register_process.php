<?php
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        // Handle empty fields
        echo "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle invalid email format
        echo "Invalid email format.";
    } else {
        // Define your database connection details
        $host = "localhost";
        $db_username = "root";
        $db_password = "admin";
        $database = "database2";

        // Create a database connection
        $conn = new mysqli($host, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password for better security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create a SQL query to insert the user data into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the database connection
        $conn->close();
    }
}
?>