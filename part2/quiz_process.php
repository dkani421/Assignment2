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
    $quizScore = 0; // Initialize the quiz score
    $totalQuestions = count($answers);

    foreach ($answers as $question_id => $selected_option) {
        $quizQuestionsSql = "SELECT correct_option FROM quiz_questions WHERE question_id = $question_id";
        $quizQuestionsResult = $conn->query($quizQuestionsSql);

        if ($quizQuestionsResult && $quizQuestionsResult->num_rows === 1) {
            $questionRow = $quizQuestionsResult->fetch_assoc();
            $correct_option = (int) $questionRow["correct_option"]; // Convert to integer

            // Convert the selected option to integer for comparison
            $selected_option_int = ($selected_option === 'a') ? 1 : (($selected_option === 'b') ? 2 : 3);

            // Compare the selected option with the correct option (as integers)
            if ($selected_option_int === $correct_option) {
                $quizScore++;
            }
        }
    }

    // Calculate the percentage grade
    $percentageGrade = ($quizScore / $totalQuestions) * 100;

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
 
             // Check if the user has completed the quiz before
             $existingGradeSql = "SELECT * FROM grades WHERE user_id = ? AND course_name = ?";
             $existingGradeStmt = $conn->prepare($existingGradeSql);
 
             if ($existingGradeStmt) {
                 $existingGradeStmt->bind_param("is", $user_id, $course_name);
                 $existingGradeStmt->execute();
                 $existingGradeResult = $existingGradeStmt->get_result();
 
                 if ($existingGradeResult->num_rows > 0) {
                     // If an existing grade is found, update the grade
                     $updateGradeSql = "UPDATE grades SET grade = ? WHERE user_id = ? AND course_name = ?";
                     $updateGradeStmt = $conn->prepare($updateGradeSql);
 
                     if ($updateGradeStmt) {
                         $updateGradeStmt->bind_param("dis", $percentageGrade, $user_id, $course_name);
 
                         if ($updateGradeStmt->execute()) {
                             header("Location: grades.php");
                             exit();
                         } else {
                             echo "Error updating grade: " . $updateGradeStmt->error;
                         }
 
                         $updateGradeStmt->close();
                     } else {
                         echo "Error preparing update statement: " . $conn->error;
                     }
                 }
 
                 $existingGradeStmt->close();
             } else {
                 echo "Error preparing existing grade statement: " . $conn->error;
             }
 
             // If no existing grade is found, proceed with insertion
             $insertGradeSql = "INSERT INTO grades (user_id, course_name, grade) VALUES (?, ?, ?)";
             $insertGradeStmt = $conn->prepare($insertGradeSql);
 
             if ($insertGradeStmt) {
                 $insertGradeStmt->bind_param("isd", $user_id, $course_name, $percentageGrade);
 
                 if ($insertGradeStmt->execute()) {
                     header("Location: grades.php");
                     exit();
                 } else {
                     echo "Error inserting grade: " . $insertGradeStmt->error;
                 }
 
                 $insertGradeStmt->close();
             } else {
                 echo "Error preparing insertion statement: " . $conn->error;
             }
         } else {
             echo "Error fetching course name: " . $conn->error;
         }
     } else {
         echo "Error fetching quiz parent information: " . $conn->error;
     }
 } else {
     header("Location: index.php");
     exit();
 }
 ?>
