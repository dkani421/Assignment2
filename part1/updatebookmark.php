<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

// Check if the bookmark ID is provided as a query parameter
if (!isset($_GET["id"])) {
    // Bookmark ID is not provided, redirect to bookmarks page or any other desired page
    header("Location: bookmarks.php");
    exit();
}

// Get the bookmark ID from the query parameter
$bookmarkId = $_GET["id"];

// Define your database connection details
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

// Prepare the SQL statement to retrieve the bookmark
$sql = "SELECT * FROM bookmarks WHERE id = ?";

// Create a prepared statement
$stmt = $conn->prepare($sql);

// Bind the parameter
$stmt->bind_param("i", $bookmarkId);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the bookmark exists
if ($result->num_rows === 1) {
    // Fetch the bookmark data
    $bookmark = $result->fetch_assoc();

    // Close the connection
    $stmt->close();
    $conn->close();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the updated bookmark name and URL
        $updatedName = $_POST["name"];
        $updatedURL = $_POST["url"];

        // Create a new connection to the database
        $conn = new mysqli($host, $db_username, $db_password, $database);

        // Check if the connection was successful
        if ($conn->connect_error) {
            // Display an error message
            echo "Connection failed: " . $conn->connect_error;
            exit();
        }

        // Prepare the SQL statement to update the bookmark
        $sql = "UPDATE bookmarks SET name = ?, url = ? WHERE id = ?";

        // Create a prepared statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ssi", $updatedName, $updatedURL, $bookmarkId);

        // Execute the statement
        $stmt->execute();

        // Check if the bookmark was successfully updated
        if ($stmt->affected_rows === 1) {
            // Close the connection
            $stmt->close();
            $conn->close();

            // Redirect to bookmarks page or any other desired page
            header("Location: bookmarks.php");
            exit();
        } else {
            // Display an error message if the bookmark update failed
            echo "Failed to update bookmark.";
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    }

    // Redirect to the edit bookmark page if the form was not submitted
    header("Location: editbookmark.php?id=" . $bookmarkId);
    exit();
} else {
    // Bookmark not found, redirect to bookmarks page or any other desired page
    $stmt->close();
    $conn->close();
    header("Location: bookmarks.php");
    exit();
}
?>
