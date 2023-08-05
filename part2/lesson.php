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

$course_id = 1; // The course_id of Course 1

// Fetch all lesson details of Course 1
$sql_lessons = "SELECT * FROM eml WHERE content_type = 'lesson' AND course_id = $course_id";
$result_lessons = $conn->query($sql_lessons);

if (!$result_lessons) {
    // Handle query execution error
    die("Error executing lessons query: " . $conn->error);
}

// Fetch all the lessons belonging to Course 1
$lessons = $result_lessons->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Course 1 Lessons - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<header>
        <h1>Course 1 Lessons</h1>
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
        <h2>Course 1 Lessons</h2>
        <?php
        foreach ($lessons as $lesson) {
            echo '<h3>' . $lesson['content_title'] . '</h3>';
            echo '<p>' . $lesson['content_description'] . '</p>';
            echo '<p>Content: ' . $lesson['content'] . '</p>';
            echo '<p>Date Created: ' . $lesson['date_created'] . '</p>';
        }
        ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
