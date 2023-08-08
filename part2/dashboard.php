<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $username; ?>'s Dashboard - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title"><?php echo $username; ?>'s Dashboard</h1>
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
        <h2><a href="course.php"><?php echo $username; ?>'s Enrolled Courses</a></h2>
        <p>Explore the courses you are currently enrolled in. Access course materials.</p>

        <h2><a href="grades.php"><?php echo $username; ?>'s Grades</a></h2>
        <p>Explore your saved user grades to expand your understanding on the presented materials.</p>

    </main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
