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
    return htmlspecialchars(strip_tags(trim($data)));
}

// Process form submission
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $favoritePlayer = isset($_POST['player']) ? sanitizeInput($_POST['player']) : null;

    if ($favoritePlayer) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO cricket_survey (favorite_cricketer, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoritePlayer);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite cricketer is: <strong>$favoritePlayer</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No cricketer selected. Please go back and select your favorite cricketer.";
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
  <title>Cricket | Survey Surfers</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
  <main class="container">
    <h1>Cricket Survey</h1>
    <article>
      <header><strong>Q1: Who is your favourite player?</strong></header>
      <form method="POST" action="cricket.php">
        <fieldset>
          <label>
            <input type="radio" name="player" value="Dhoni" checked>
            Dhoni
          </label>
          <label>
            <input type="radio" name="player" value="Kohli">
            Kohli
          </label>
          <label>
            <input type="radio" name="player" value="Rhyna">
            Rhyna
          </label>
          <label>
            <input type="radio" name="player" value="Shami">
            Shami
          </label>
          <label>
            <input type="radio" name="player" value="Jadeja">
            Jadeja
          </label>
        </fieldset>
        <input type="submit" value="submit">
      </form>
      <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
  </main>
</body>
</html>
