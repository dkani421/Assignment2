<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION["username"])) {
    // Redirect to the home page or any other desired page
    header("Location: dashboard.php");
    exit();
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
        $_SESSION["error"] = "Both username and password are required.";
    } else {
        // Create a database connection
        $conn = new mysqli($host, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a SQL query to fetch the user's password and role from the database
        $sql = "SELECT user_id, password, role FROM users WHERE username = '$username'";

        // Execute the query
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            // Fetch the password and role from the result set
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];
            $role = $row["role"];
            $user_id = $row["user_id"];

            // Verify the submitted password against the hashed password
            if (password_verify($password, $hashed_password)) {
                // Set the session variables and redirect to the dashboard page
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;
                $_SESSION["user_id"] = $user_id;
                header("Location: dashboard.php");
                exit();
            } else {
                // Set the error message for incorrect username or password
                $_SESSION["error"] = "Invalid username or password.";
            }
        } else {
            // Set the error message for incorrect username or password
            $_SESSION["error"] = "Invalid username or password.";
        }

        // Close the database connection
        $conn->close();
    }
}

// Redirect back to the login page, passing the error message in the query parameter
header("Location: login.php?error=" . urlencode($_SESSION["error"]));
exit();
?>
