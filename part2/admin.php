<?php
// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database2";

// Check if the user is logged in and is an admin (you need to implement the authentication system)
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to a login page or show an error message if the user is not an admin
    header("Location: login.php");
    exit;
}

// Function to process and save the EML content to the database
function saveEmlToDatabase($emlContent) {
    global $host, $db_username, $db_password, $database;

    try {
        $db = new PDO("mysql:host=$host;dbname=$database", $db_username, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }

    // Insert the EML content into the 'eml' table
    $query = "INSERT INTO eml (content) VALUES (:content)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':content', $emlContent, PDO::PARAM_STR);
    $stmt->execute();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['eml_file']) && $_FILES['eml_file']['error'] === UPLOAD_ERR_OK) {
        // Get the temporary uploaded file path
        $tmpFilePath = $_FILES['eml_file']['tmp_name'];

        // Read the EML content from the temporary file
        $emlContent = file_get_contents($tmpFilePath);

        // Save the EML content to the database
        saveEmlToDatabase($emlContent);

        // Redirect or show a success message
        header("Location: admin.php?upload_success=true");
        exit;
    } else {
        // Handle file upload error
        // Redirect or show an error message
        header("Location: admin.php?upload_error=true");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Upload EML File</title>
    <link rel="stylesheet" href="../shared/styles.css">
</head>
<style>
        pre {
            text-align: left;
        }
    </style>
<body>
    <header>
        <h1>Admin - Upload EML File</h1>
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
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        // Show a success message if the upload was successful
        if (isset($_GET['upload_success']) && $_GET['upload_success'] === 'true') {
            echo '<p>File uploaded successfully!</p>';
        }

        // Show an error message if there was an upload error
        if (isset($_GET['upload_error']) && $_GET['upload_error'] === 'true') {
            echo '<p>Error uploading the file. Please try again.</p>';
        }
        ?>

    <h1>Admin Documentation for EML Content Formatting and Upload</h1>
        <p>
            Below is an example of the structure for an EML document that can be uploaded by admins and parsed by the system.
        </p>

        <h2>EML Document Structure</h2>
        <pre>
            &lt;Document&gt;
                &lt;Contents&gt;
                    &lt;section&gt;
                        &lt;content_type&gt;course&lt;/content_type&gt;
                        &lt;content_title&gt;Introduction to Programming&lt;/content_title&gt;
                        &lt;content_description&gt;Learn the basics of programming languages and concepts.&lt;/content_description&gt;
                        &lt;content&gt;https://example.com/intro_programming_video&lt;/content&gt;
                    &lt;/section&gt;
                &lt;/Contents&gt;
            &lt;/Document&gt;
        </pre>

        <h2>Upload EML File</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label for="eml_file">Upload EML File:</label>
            <input type="file" id="eml_file" name="eml_file" accept=".eml" required>
            <button type="submit">Upload</button>
        </form>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
