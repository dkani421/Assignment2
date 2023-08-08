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

    // Display the edit form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Edit Bookmark</title>
        <link rel="stylesheet" href="../shared/styles.css">
        <script src="validate.js"></script>
    </head>
    <body>
    <img class="banner" src="../Shared/Bookmarking.png" alt="Banner Image">
        <header>
            <h1 class="white-title">Bookmarking Service</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="bookmarks.php">My Bookmarks</a></li>
                    <li><a href="addbookmark.php">Add Bookmark</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <h2>Edit Bookmark</h2>
            <form action="updatebookmark.php?id=<?php echo $bookmarkId; ?>" method="POST" onsubmit="return validateForm();">
                <label for="name">Website Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $bookmark[
                    "name"
                ]; ?>" required>
                <label for="url">Website URL:</label>
                <input type="text" id="url" name="url" value="<?php echo $bookmark[
                    "url"
                ]; ?>" required>
                <input type="submit" value="Update Bookmark">
            </form>
        </main>
        <footer>
            &copy; 2023 Bookmarking Service. All rights reserved.
        </footer>
    </body>
    </html>
    <?php
} else {
    // Bookmark not found, redirect to bookmarks page or any other desired page
    $stmt->close();
    $conn->close();
    header("Location: bookmarks.php");
    exit();
}
?>
