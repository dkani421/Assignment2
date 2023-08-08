<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Bookmarks</title>
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
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>My Bookmarks</h2>
        <ul>
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

            // Create a connection to the database
            $conn = new mysqli($host, $db_username, $db_password, $database);

            // Check if the connection was successful
            if ($conn->connect_error) {
                // Display an error message
                echo "Connection failed: " . $conn->connect_error;
                exit();
            }

            // Get the username from the session
            $username = $_SESSION["username"];

            // Prepare the SQL statement to retrieve the user's bookmarks
            $sql = "SELECT * FROM bookmarks WHERE username = ?";

            // Create a prepared statement
            $stmt = $conn->prepare($sql);

            // Bind the parameter
            $stmt->bind_param("s", $username);

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Loop through the bookmarks and display them
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<a href='" .
                    $row["url"] .
                    "' target='_blank'>" .
                    $row["name"] .
                    "</a>";
                echo "<form style='display: inline;' action='editbookmark.php?id=" .
                    $row["id"] .
                    "' method='POST'>";
                echo "<button type='submit' name='edit'>Edit</button>";
                echo "</form>";
                echo "<form style='display: inline;' action='deletebookmark.php?id=" .
                    $row["id"] .
                    "' method='POST'>";
                echo "<button type='submit' name='delete'>Delete</button>";
                echo "</form>";
                echo "</li>";
            }

            // Close the connection
            $conn->close();
            ?>
        </ul>
    </main>
    <footer>
        &copy; 2023 Bookmarking Service. All rights reserved.
    </footer>
</body>
</html>
