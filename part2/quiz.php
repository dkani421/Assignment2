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

if (isset($_GET["course_id"]) && is_numeric($_GET["course_id"])) {
    $course_id = $_GET["course_id"];

    $sql = "SELECT * FROM eml WHERE content_type = 'quiz' AND parent_id = $course_id";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error executing query: " . $conn->error);
    }
} else {
    header("Location: index.php"); // Redirect to the homepage or any other suitable page
    exit();
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
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title">Quiz</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li> 
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="grades.php">Grades</a></li>
                <li><a href="admin.php">Admin</a></li>              
                <li><a href="register.php">Register</a></li>
                <li><a href="logout.php">Logout</a></li>
                
            </ul>
        </nav>
    </header>
    <main>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $quizId = $row["content_id"];
                echo '<div class="quiz">';
                echo "<h2>" . $row["content_title"] . "</h2>";
                echo "<p>" . $row["content_description"] . "</p>";

                $quizQuestionsSql = "SELECT * FROM quiz_questions WHERE quiz_id = $quizId";
                $quizQuestionsResult = $conn->query($quizQuestionsSql);

                if (
                    $quizQuestionsResult &&
                    $quizQuestionsResult->num_rows > 0
                ) {
                    echo '<form method="post" action="quiz_process.php">';
                    while ($questionRow = $quizQuestionsResult->fetch_assoc()) {
                        echo '<div class="question">';
                        echo "<h3>" . $questionRow["question_text"] . "</h3>";
                        echo "<p>a. " . $questionRow["option1"] . "</p>";
                        echo "<p>b. " . $questionRow["option2"] . "</p>";
                        echo "<p>c. " . $questionRow["option3"] . "</p>";
                        echo '<input type="radio" name="answers[' .
                            $questionRow["question_id"] .
                            ']" value="a">a';
                        echo '<input type="radio" name="answers[' .
                            $questionRow["question_id"] .
                            ']" value="b">b';
                        echo '<input type="radio" name="answers[' .
                            $questionRow["question_id"] .
                            ']" value="c">c';
                        echo "</div>";
                    }
                    echo '<input type="hidden" name="quiz_score" value="0">';
                    echo '<input type="hidden" name="quiz_id" value="' .
                        $quizId .
                        '">';
                    echo '<input type="hidden" name="user_id" value="' .
                        $_SESSION["user_id"] .
                        '">';
                    echo "<br>";
                    echo '<input type="submit" value="Submit">';
                    echo "</form>";
                } else {
                    echo "<p>No quiz questions found for this quiz.</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No quizzes found.</p>";
        } ?>
    </main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
