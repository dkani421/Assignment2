<?php
// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "database2";

$conn = new mysqli($host, $db_username, $db_password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user's grades (you'll need to have a user authentication system in place to get the user ID)
$userId = 1; // Replace with the actual user ID (from user authentication process)

$sql = "SELECT courses.content_title AS course_name, grades.grade
        FROM eml AS courses
        INNER JOIN eml AS grades ON courses.content_id = grades.parent_id
        WHERE courses.content_type = 'course' AND grades.content_type = 'quiz' AND grades.course_id IS NOT NULL AND grades.parent_id IS NOT NULL AND grades.course_id = $userId";

$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing the query: " . $conn->error);
}

// Fetch and process the data (example)
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Course Name: " .
            $row["course_name"] .
            ", Grade: " .
            $row["grade"] .
            "<br>";
    }
} else {
    echo "No grades found for the user.";
}

// Close the database connection
$conn->close();
?>
