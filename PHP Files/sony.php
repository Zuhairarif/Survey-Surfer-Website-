<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "id22299453_apple";
$password = "Zuhair@arif1"; // Replace with your MySQL root password
$dbname = "id22299453_apple"; // The database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Process form submission
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $favoriteHeadphone = isset($_POST['Sony']) ? sanitizeInput($_POST['Sony']) : null;

    if ($favoriteHeadphone) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO sony_survey (favorite_headphone, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoriteHeadphone);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite Sony headphone model is: <strong>$favoriteHeadphone</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No Sony headphone model selected. Please go back and select your favorite Sony headphone model.";
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
  <title>Sony Headphones | Survey Surfers</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
  <main class="container">
    <h1>Sony Headphones Survey</h1>
    <article>
      <header><strong>Q1: Which one is your favourite headphone of Sony?</strong></header>
      <form method="POST" action="sony.php">
        <fieldset>
          <label>
            <input type="radio" name="Sony" value="Sony WH-1000XM4" checked>
            Sony WH-1000XM4
          </label>
          <label>
            <input type="radio" name="Sony" value="Sony WF-1000XM4">
            Sony WF-1000XM4
          </label>
          <label>
            <input type="radio" name="Sony" value="Sony WH-XB900N">
            Sony WH-XB900N
          </label>
          <label>
            <input type="radio" name="Sony" value="Sony MDR-ZX110">
            Sony MDR-ZX110
          </label>
        </fieldset>
        <input type="submit" value="submit">
      </form>
      <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
  </main>
</body>
</html>
