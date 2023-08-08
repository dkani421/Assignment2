<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted bookmark name and URL
    $name = $_POST["name"];
    $url = $_POST["url"];

    // Create a connection to the database
    $conn = new mysqli($host, $db_username, $db_password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        // Display an error message
        echo "Connection failed: " . $conn->connect_error;
        exit();
    }

    // Prepare the SQL statement to insert the bookmark
    $sql = "INSERT INTO bookmarks (name, url, username) VALUES (?, ?, ?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $name, $url, $_SESSION["username"]);

    // Execute the statement
    $stmt->execute();

    // Check if the bookmark was successfully inserted
    if ($stmt->affected_rows === 1) {
        // Bookmark added successfully, redirect to bookmarks page or any other desired page
        header("Location: bookmarks.php");
        exit();
    } else {
        // Display an error message if the bookmark insertion failed
        echo "Failed to add bookmark.";
    }
}
?>
