<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "id22299453_apple";
$password = "Zuhair@arif1"; // Change this to your MySQL root password
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
    $favoriteiPhone = isset($_POST['iPhone']) ? sanitizeInput($_POST['iPhone']) : null;

    if ($favoriteiPhone) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO apple_company_survey (favorite_iPhone) VALUES (?)");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoriteiPhone);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite iPhone model is: <strong>$favoriteiPhone</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No iPhone model selected. Please go back and select your favorite iPhone model.";
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
  <title>Apple | Survey Surfers</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
  <main class="container">
    <h1>Apple Survey</h1>
    <article>
      <header><strong>Q1: Which is your favourite Apple iPhone?</strong></header>
      <form method="POST" action="apple.php">
        <fieldset>
          <label>
            <input type="radio" name="iPhone" value="iPhone 13" checked>
            iPhone 13
          </label>
          <label>
            <input type="radio" name="iPhone" value="iPhone 13 Pro">
            iPhone 13 Pro
          </label>
          <label>
            <input type="radio" name="iPhone" value="iPhone 13 Mini">
            iPhone 13 Mini
          </label>
          <label>
            <input type="radio" name="iPhone" value="iPhone SE">
            iPhone SE
          </label>
        </fieldset>
        <input type="submit" value="submit">
      </form>
      <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
  </main>
</body>
</html>
