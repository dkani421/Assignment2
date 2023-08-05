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
    <title>Dashboard - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
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
        <h2>Announcements</h2>
        <p>There are no new announcements at the moment.</p>
        <!-- Add announcements dynamically here -->

        <h2>Enrolled Courses</h2>
        <ul>
            <li><a href="course.php">Courses Dashboard</a></li>
        </ul>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
