<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION["username"])) {
    // Redirect to the home page or any other desired page
    header("Location: index.php");
    exit();
}

// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username and password match the admin credentials
    if ($username === "admin" && $password === "admin") {
        // Set the session variable and redirect to the home page or any other desired page
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Set the error message
        $error = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../shared/styles.css">
    <style>
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
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
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        
        <?php // Display the error message if it exists
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        } ?>
    </main>
    <footer>
        &copy; 2023 Bookmarking Service. All rights reserved.
    </footer>
</body>
</html>
