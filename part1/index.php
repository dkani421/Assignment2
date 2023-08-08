<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["username"]);
if ($isLoggedIn) {
    // Get the username from the session
    $username = $_SESSION["username"];
}

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

// Prepare the SQL statement to retrieve the top 10 bookmarks
$sql = "SELECT name, url FROM bookmarks ORDER BY id DESC LIMIT 10";

// Execute the statement
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bookmarking Service</title>
    <link rel="stylesheet" href="../shared/styles.css">
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
                <?php if ($isLoggedIn): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <?php if ($isLoggedIn): ?>
            <h2>Welcome, <?php echo $username; ?>!</h2>
        <?php else: ?>
            <h2>Welcome!</h2>
        <?php endif; ?>
        <p>Here are the ten most popular websites bookmarked by users:</p>
        <ul>
            <?php // Loop through the results and display the bookmarks
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='" .
                    $row["url"] .
                    "' target='_blank'>" .
                    $row["name"] .
                    "</a></li>";
            } ?>
        </ul>
    </main>
    <footer>
        &copy; 2023 Bookmarking Service. All rights reserved.
    </footer>
</body>
</html>
