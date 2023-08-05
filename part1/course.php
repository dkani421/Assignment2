<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit;
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
    <header>
        <h1>Available Courses</h1>
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
            </ul>
        </nav>
    </header>
    <main>
        <!-- Add a list of available courses here with links to each course's page -->
        <ul>
            <li><a href="lesson.php?course=1">Course 1</a></li>
            <li><a href="lesson.php?course=2">Course 2</a></li>
        </ul>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
