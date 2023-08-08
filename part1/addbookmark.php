<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Bookmark</title>
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
        <h2>Add Bookmark</h2>
        <form action="processbookmark.php" method="POST" onsubmit="return validateForm();">
            <label for="name">Website Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="url">Website URL:</label>
            <input type="text" id="url" name="url" required>
            <input type="submit" value="Add Bookmark">
        </form>
    </main>
    <footer>
        &copy; 2023 Bookmarking Service. All rights reserved.
    </footer>
</body>
</html>
