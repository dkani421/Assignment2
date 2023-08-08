<?php
// Define your database connection details
$host = "localhost";
$db_username = "root";
$db_password = "admin";
$database = "Database2";

// Check if the user is logged in and is an admin (you need to implement the authentication system)
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    // Redirect to a login page or show an error message if the user is not an admin
    header("Location: login.php");
    exit();
}

// Function to process and save the uploaded EML content to the database
function saveEmlToDatabase($emlContent)
{
    global $host, $db_username, $db_password, $database;

    // Ensure $emlContent is not empty or null
    if (empty($emlContent)) {
        die("EML content is empty.");
    }

    try {
        $db = new PDO(
            "mysql:host=$host;dbname=$database",
            $db_username,
            $db_password
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Parse the EML content as defiend in the assignment
    $xml = simplexml_load_string($emlContent);

    // Check if XML parsing was successful
    if ($xml === false) {
        die("Failed to parse the XML content.");
    }

    // Check if the necessary eml table elements exist before reading their values
    if (
        !isset($xml->Contents->section->content_type) ||
        !isset($xml->Contents->section->content_title) ||
        !isset($xml->Contents->section->content_description) ||
        !isset($xml->Contents->section->content)
    ) {
        die("XML content does not have the required elements.");
    }

    // Read the values from the XML input
    $content_type = (string) $xml->Contents->section->content_type;
    $content_title = (string) $xml->Contents->section->content_title;
    $content_description =
        (string) $xml->Contents->section->content_description;
    $content = (string) $xml->Contents->section->content;
    $parent_id = isset($xml->Contents->section->parent_id)
        ? (int) $xml->Contents->section->parent_id
        : null;
    $course_id = isset($xml->Contents->section->course_id)
        ? (int) $xml->Contents->section->course_id
        : null;

    // Insert the EML content into the 'eml' table
    $query = "INSERT INTO eml (content_type, parent_id, content_title, content_description, content, course_id, date_created, date_modified) 
            VALUES (:content_type, :parent_id, :content_title, :content_description, :content, :course_id, NOW(), NOW())";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":content_type", $content_type, PDO::PARAM_STR);
    $stmt->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
    $stmt->bindParam(":content_title", $content_title, PDO::PARAM_STR);
    $stmt->bindParam(
        ":content_description",
        $content_description,
        PDO::PARAM_STR
    );
    $stmt->bindParam(":content", $content, PDO::PARAM_STR);
    $stmt->bindParam(":course_id", $course_id, PDO::PARAM_INT);

    // Execute the query
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error executing the database query: " . $e->getMessage());
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_FILES["eml_file"]) &&
        $_FILES["eml_file"]["error"] === UPLOAD_ERR_OK
    ) {
        // Get the temporary uploaded file path
        $tmpFilePath = $_FILES["eml_file"]["tmp_name"];

        // Read the EML content from the temporary file
        $emlContent = file_get_contents($tmpFilePath);

        // Save the EML content to the database
        saveEmlToDatabase($emlContent);

        // Redirect or show a success message
        header("Location: admin.php?upload_success=true");
        exit();
    } else {
        // Handle file upload error
        // Redirect or show an error message
        header("Location: admin.php?upload_error=true");
        exit();
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
<img class="banner" src="../Shared/ELMS.png" alt="Banner Image">
    <header>
        <h1 class="white-title">Admin - Upload EML File</h1>
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
        <?php
        // Show a success message if the upload was successful
        if (
            isset($_GET["upload_success"]) &&
            $_GET["upload_success"] === "true"
        ) {
            echo "<p>File uploaded successfully!</p>";
        }

        // Show an error message if there was an upload error
        if (isset($_GET["upload_error"]) && $_GET["upload_error"] === "true") {
            echo "<p>Error uploading the file. Please try again.</p>";
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
                        &lt;content_type&gt;only one of (course,lesson,quiz)&lt;/content_type&gt;
                        &lt;content_title&gt;title&lt;/content_title&gt;
                        &lt;content_description&gt;description&lt;/content_description&gt;
                        &lt;content&gt;content&lt;/content&gt;
                        &lt;parent_id&gt;If course, Leave blank. If lesson or quiz, use matching parent id.&lt;/parent_id&gt;
                        &lt;course_id&gt;&lt;/course_id&gt;
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
        &copy; <?php echo date(
            "Y"
        ); ?> Learning Management System. All rights reserved.
    </footer>
</body>
</html>
