<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Get the quiz score from the form submission
    $quiz_score = $_POST['quiz_score'];

    // Define your database connection details
    $host = "localhost";
    $db_username = "root";
    $db_password = "admin";
    $database = "Database2";

    $conn = new mysqli($host, $db_username, $db_password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the update query
    $course_name = "Introduction to Programming"; // Assuming a fixed course name
    $update_query = "INSERT INTO grades (user_id, course_name, grade) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iss", $user_id, $course_name, $quiz_score);

    if ($stmt->execute()) {
        // Successful update, redirect to quiz list or other page
        header("Location: quizzes.php");
        exit;
    } else {
        // Handle update error
        echo "Error updating grade: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to quiz page if accessed directly
    header("Location: quiz.php");
    exit;
}
?>
