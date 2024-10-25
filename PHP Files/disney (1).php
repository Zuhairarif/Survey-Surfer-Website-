<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "id22299453_apple"; // Replace with your MySQL username
$password = "Zuhair@arif1"; // Replace with your MySQL password
$dbname = "id22299453_apple"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Process form submission
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $favoriteDisney = isset($_POST['Disney']) ? sanitizeInput($_POST['Disney']) : null;

    if ($favoriteDisney) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO disney_survey (favorite_disney_streaming, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoriteDisney);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite Disney streaming service is: <strong>$favoriteDisney</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No Disney streaming service selected. Please go back and select your favorite.";
    }
} else {
    $message = "Invalid request method.";
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disney | Survey Surfers</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
<main class="container">
    <h1>Disney Survey</h1>
    <article>
        <header><strong>Q1: Which is your favourite Disney streaming service?</strong></header>
        <form method="POST" action="disney.php">
            <fieldset>
                <label>
                    <input type="radio" name="Disney" value="Disney+" />
                    Disney+
                </label>
                <label>
                    <input type="radio" name="Disney" value="Hulu" />
                    Hulu
                </label>
                <label>
                    <input type="radio" name="Disney" value="ESPN+" />
                    ESPN+
                </label>
                <label>
                    <input type="radio" name="Disney" value="Disney+ Bundle" />
                    Disney+ Bundle
                </label>
            </fieldset>
            <input type="submit" value="submit" />
        </form>
        <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
</main>
</body>
</html>
