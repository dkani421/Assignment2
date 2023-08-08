<?php
session_start(); // Start the session

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register - Learning Management System</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title">Register</h1>
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
    <h2>Register</h2>
    <form action="register_process.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

         <!-- Add the radio button group for selecting the role -->
         <label>Role:</label>
        <input type="radio" id="admin" name="role" value="admin" required>
        <label for="admin">Admin</label>
        <input type="radio" id="student" name="role" value="student" required>
        <label for="student">Student</label><br>

        <button type="submit">Register</button>
        <?php if (isset($_SESSION["registration_message"])): ?>
        <p><?php echo $_SESSION["registration_message"]; ?></p>
        <?php unset($_SESSION["registration_message"]); ?>
        <?php endif; ?>
    </form>
    </main>
    <footer>
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
