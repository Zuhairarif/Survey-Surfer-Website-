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
    $favoritePlaystation = isset($_POST['gaming']) ? sanitizeInput($_POST['gaming']) : null;

    if ($favoritePlaystation) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO gaming_survey (favorite_playstation, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoritePlaystation);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite Playstation is: <strong>$favoritePlaystation</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No Playstation selected. Please go back and select your favorite Playstation.";
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
  <title>Gaming Console: Playstation | Survey Surfers</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
  <main class="container">
    <h1>Gaming Survey</h1>
    <article>
      <header><strong>Q1: Which is your favourite Playstation?</strong></header>
      <form method="POST" action="gaming.php">
        <fieldset>
          <label>
            <input type="radio" name="gaming" value="Playstation 5">
            Playstation 5
          </label>
          <label>
            <input type="radio" name="gaming" value="Playstation 4 Pro">
            Playstation 4 Pro
          </label>
          <label>
            <input type="radio" name="gaming" value="Playstation 4 Slim">
            Playstation 4 Slim
          </label>
          <label>
            <input type="radio" name="gaming" value="Playstation VR">
            Playstation VR
          </label>
        </fieldset>
        <input type="submit" value="submit">
      </form>
      <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
  </main>
</body>
</html>
