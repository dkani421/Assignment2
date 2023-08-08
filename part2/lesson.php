<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
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

// Check if the course_id parameter is present in the URL
if (isset($_GET["course_id"]) && is_numeric($_GET["course_id"])) {
    $course_id = $_GET["course_id"];

    // Fetch the course name based on the provided course_id
    $sql_course = "SELECT content_title FROM eml WHERE content_type = 'course' AND content_id = $course_id";
    $result_course = $conn->query($sql_course);

    if (!$result_course) {
        // Handle query execution error
        die("Error executing course query: " . $conn->error);
    }

    $course_data = $result_course->fetch_assoc();
    $course_name = $course_data["content_title"];

    // Fetch all lesson details of the specified course
    $sql_lessons = "SELECT * FROM eml WHERE content_type = 'lesson' AND parent_id = $course_id";
    $result_lessons = $conn->query($sql_lessons);

    if (!$result_lessons) {
        // Handle query execution error
        die("Error executing lessons query: " . $conn->error);
    }

    // Fetch all the lessons belonging to the specified course
    $lessons = $result_lessons->fetch_all(MYSQLI_ASSOC);
} else {
    // Redirect or show an error message if the course_id parameter is missing or invalid
    header("Location: index.php"); // Redirect to the homepage or any other suitable page
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $course_name; ?> Lessons - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title"><?php echo $course_name; ?> Lessons</h1>
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
    <h2><?php echo $course_name; ?> Lessons</h2>
    <?php foreach ($lessons as $lesson) {
        echo "<h3>" . $lesson["content_title"] . "</h3>";
        echo "<p>" . $lesson["content_description"] . "</p>";
        echo "<p>Content: " . $lesson["content"] . "</p>";
        echo "<p>Date Created: " . $lesson["date_created"] . "</p>";
    } ?>
    <h2><?php echo $course_name; ?> Quizzes</h2>
    <?php
    // Fetch all quiz details associated with the same course as lessons
    $sql_quizzes = "SELECT * FROM eml WHERE content_type = 'quiz' AND parent_id = $course_id";
    $result_quizzes = $conn->query($sql_quizzes);

    if (!$result_quizzes) {
        // Handle query execution error
        die("Error executing quizzes query: " . $conn->error);
    }

    // Fetch all the quizzes associated with the specified course
    $quizzes = $result_quizzes->fetch_all(MYSQLI_ASSOC);

    foreach ($quizzes as $quiz) {
        echo '<h3><a href="quiz.php?course_id=' .
            $quiz["parent_id"] .
            '">' .
            $quiz["content_title"] .
            "</a></h3>";
        echo "<p>" . $quiz["content_description"] . "</p>";
        echo "<p>Date Created: " . $quiz["date_created"] . "</p>";
    }
    ?>
</main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
