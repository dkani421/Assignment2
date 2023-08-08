<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

// Check if the bookmark ID is provided
if (isset($_GET["id"])) {
    // Get the bookmark ID
    $bookmarkId = $_GET["id"];

    // Define database connection details
    $host = "localhost";
    $db_username = "root";
    $db_password = "admin";
    $database = "Database";

    // Create a connection to the database
    $conn = new mysqli($host, $db_username, $db_password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        // Display an error message
        echo "Connection failed: " . $conn->connect_error;
        exit();
    }

    // Prepare the SQL statement to delete the bookmark
    $sql = "DELETE FROM bookmarks WHERE id = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("i", $bookmarkId);

    // Execute the statement
    $stmt->execute();

    // Check if the bookmark was successfully deleted
    if ($stmt->affected_rows === 1) {
        // Bookmark deleted successfully, redirect to bookmarks page or any other desired page
        header("Location: bookmarks.php");
        exit();
    } else {
        // Display an error message if the bookmark deletion failed
        echo "Failed to delete bookmark.";
    }

    // Close the connection
    $conn->close();
} else {
    // Bookmark ID is not provided, redirect to bookmarks page or any other desired page
    header("Location: bookmarks.php");
    exit();
}
?>
