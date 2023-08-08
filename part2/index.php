<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title">Home</h1>
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
        <h1>Welcome to our Learning Management System (LMS)</h1>
        <p>Your gateway to an enriched educational experience! Our LMS provides a user-friendly and intuitive interface designed to cater to all your learning needs. Whether you're a student, instructor, or an eager learner, our platform offers a comprehensive set of features to enhance your learning journey.</p>

        <h2><a href="dashboard.php">Dashboard</a></h2>
        <p>Your Dashboard is where you'll find a detailed view of your courses, grades, and quizzes. It allows you to navigate the learning material, manage deadlines, and stay organized.</p>

        <h2>Quiz</h2>
        <p>With our interactive Quiz feature, you can assess your knowledge and understanding of the course material. Engage in challenging quizzes that provide immediate feedback, enabling you to identify areas of improvement and solidify your grasp on the subject matter.</p>

        <h2><a href="grades.php">Grades</a></h2>
        <p>The Grades section is your performance hub, providing a transparent view of your scores and progress in each course. Keep track of your achievements, monitor your strengths, and identify areas that need extra attention. Our comprehensive grading system ensures you have a clear understanding of your academic standing.</p>

        <h2><a href="admin.php">Admins</a></h2>
        <p>The Admins section is reserved for SME's. This page allows SME's to manage, add develop new content using our very own in house EML!</p>

        <h2><a href="login.php">Login/Register</a></h2>
        <p>To unlock the vast array of educational resources and courses, you need to log in or register with our platform. Returning users can access their accounts with ease, while new users can quickly create an account to embark on their learning journey.</p>
    </main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
