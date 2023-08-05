<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to the home page or any other desired page
    header("Location: dashboard.php");
    exit;
}

// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "database2"; 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input
    if (empty($username) || empty($password)) {
        // Handle empty fields
        $_SESSION['error'] = "Both username and password are required.";
    } else {
        // Create a database connection
        $conn = new mysqli($host, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a SQL query to fetch the user's password from the database
        $sql = "SELECT password FROM users WHERE username = '$username'";

        // Execute the query
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            // Fetch the password from the result set
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];

            // Verify the submitted password against the hashed password
            if (password_verify($password, $hashed_password)) {
                // Set the session variable and redirect to the dashboard page 
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                // Set the error message for incorrect username or password
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            // Set the error message for incorrect username or password
            $_SESSION['error'] = "Invalid username or password.";
        }

        // Close the database connection
        $conn->close();
    }
}

// Redirect back to the login page, regardless of success or failure
header("Location: login.php");
exit;
?>