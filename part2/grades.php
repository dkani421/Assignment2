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

// Retrieve the user_id of the logged-in user from the users table
$username = $_SESSION["username"];
$sql_user = "SELECT user_id FROM users WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if (!$result_user) {
    // Handle query execution error
    die("Error executing user query: " . $conn->error);
}

$user = $result_user->fetch_assoc();
$user_id = $user["user_id"];

// Retrieve grades for the logged-in user from the grades table
$sql_grades = "SELECT course_name, grade FROM grades WHERE user_id = $user_id";
$result_grades = $conn->query($sql_grades);

if (!$result_grades) {
    // Handle query execution error
    die("Error executing grades query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $username; ?>'s Grades - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title"><?php echo $username; ?>'s Grades</h1>
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
        <?php if ($result_grades->num_rows > 0) {
            $username = $_SESSION["username"];
            echo "<table>";
            echo "<tr><th>Course</th><th>Grade</th></tr>";
            while ($row = $result_grades->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["course_name"] . "</td>";
                echo "<td>" . $row["grade"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No grades available.</p>";
        } ?>
    </main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
<?php // Close the database connection
$conn->close();
?>
