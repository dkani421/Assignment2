<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit;
}

// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database2";

$conn = new mysqli($host, $db_username, $db_password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM eml WHERE content_type = 'quiz'";
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
    <title>Quizzes - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
    <header>
        <h1>Quizzes</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> 
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="quiz.php">Quiz</a></li>
                <li><a href="grades.php">Grades</a></li>
                <?php
                // Check if the user is logged in
                if (isset($_SESSION['username'])) {
                    // Show the "Logout" link
                    echo '<li><a href="logout.php">Logout</a></li>';
                } else {
                    // Show the "Login" link
                    echo '<li><a href="login.php">Login</a></li>';
                }
                ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        if ($result->num_rows > 0) {
            // Loop through the quizzes and display each one
            while ($row = $result->fetch_assoc()) {
                echo '<h2>' . $row['content_title'] . '</h2>';
                echo '<p>' . $row['content_description'] . '</p>';
                echo '<form method="post" action="quiz_process.php">';
                echo '<input type="submit" value="Submit">';
                echo '</form>';
            }
        } else {
            echo 'No quizzes found.';
        }
        ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
