<?php
session_start();

if (!isset($_SESSION["username"])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_SESSION);
    $user_id = $_SESSION["user_id"];
    $quiz_id = $_POST["quiz_id"];
    $answers = $_POST["answers"];

    // Calculate the user's quiz score
    $quizScore = 0;
    foreach ($answers as $question_id => $selected_option) {
        $quizQuestionsSql = "SELECT correct_option FROM quiz_questions WHERE question_id = $question_id";
        $quizQuestionsResult = $conn->query($quizQuestionsSql);

        if ($quizQuestionsResult && $quizQuestionsResult->num_rows === 1) {
            $questionRow = $quizQuestionsResult->fetch_assoc();
            $correct_option = $questionRow["correct_option"];

            // Compare the selected option with the correct option
            if ($selected_option === $correct_option) {
                $quizScore++;
            }
        }
    }

    // Get the parent course ID from the quiz
    $quizParentSql = "SELECT parent_id FROM eml WHERE content_id = $quiz_id";
    $quizParentResult = $conn->query($quizParentSql);

    if ($quizParentResult && $quizParentResult->num_rows === 1) {
        $quizParentRow = $quizParentResult->fetch_assoc();
        $course_id = $quizParentRow["parent_id"];

        // Get the course name based on course_id
        $courseNameSql = "SELECT content_title FROM eml WHERE content_id = $course_id";
        $courseNameResult = $conn->query($courseNameSql);

        if ($courseNameResult && $courseNameResult->num_rows === 1) {
            $courseNameRow = $courseNameResult->fetch_assoc();
            $course_name = $courseNameRow["content_title"];

            // Insert a new grade into the "grades" table
            $insertGradeSql = "INSERT INTO grades (user_id, course_name, grade) VALUES (?, ?, ?)";

            // Create a prepared statement
            $stmt = $conn->prepare($insertGradeSql);

            if ($stmt) {
                // Bind parameters and execute the statement
                $stmt->bind_param("iss", $user_id, $course_name, $quizScore);

                if ($stmt->execute()) {
                    header("Location: grades.php"); // Redirect to the grades page after updating the grade
                    exit();
                } else {
                    echo "Error inserting grade: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Error fetching course name: " . $conn->error;
        }
    } else {
        echo "Error fetching quiz parent information: " . $conn->error;
    }
} else {
    header("Location: index.php"); // Redirect to the homepage or any other suitable page
    exit();
}
?>
