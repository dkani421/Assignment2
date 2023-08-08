<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database2";

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM eml WHERE content_type = 'course'";
$result = $conn->query($sql);

if (!$result) {
    // Handle query execution error
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Courses - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title">Course Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> 
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="grades.php">Grades</a></li>
                <li><a href="admin.php">Admin</a></li>        
                <li><a href="register.php">Register</a></li>
                <?php // Check if the user is logged in
                if (isset($_SESSION["username"])) {
                    // Show the "Logout" link
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    // Show the "Login" link
                    echo '<li><a href="login.php">Login</a></li>';
                } ?>
            </ul>
        </nav>
    </header>
    <main>
    <?php
    if ($result->num_rows > 0) {
        // Loop through the results and display the data
        while ($row = $result->fetch_assoc()) {
            echo '<h2><a href="lesson.php?course_id=' .
                $row["course_id"] .
                '">' .
                $row["content_title"] .
                "</a></h2>";
            echo "<p>" . $row["content_description"] . "</p>";
            echo "<p>Course ID: " . $row["course_id"] . "</p>";
            echo "<p>Date Created: " . $row["date_created"] . "</p>";
            echo "<p>Content: " . $row["content"] . "</p>";
        }
    } else {
        echo "No course data found.";
    }

    $conn->close();
    ?>
</main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>