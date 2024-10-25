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
    $colgate_product = isset($_POST['product']) ? sanitizeInput($_POST['product']) : null;

    if ($colgate_product) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO colgate_survey (colgate_product) VALUES (?)");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $colgate_product);
            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite product category is: <strong>$colgate_product</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No product category selected. Please go back and select your favorite product category.";
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
  <title>Colgate | Survey Surfers</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
  <main class="container">
    <h1>Colgate Survey</h1>
    <article>
      <header><strong>Q1: Which is your favourite type of product?</strong></header>
      <form method="POST" action="colgate.php">
        <fieldset>
          <label>
            <input type="radio" name="product" value="Health care and industrial supplies" checked>
            Health care and industrial supplies
          </label>
          <label>
            <input type="radio" name="product" value="Personal care products">
            Personal care products
          </label>
          <label>
            <input type="radio" name="product" value="Leisure and sports equipment">
            Leisure and sports equipment
          </label>
          <label>
            <input type="radio" name="product" value="Food products">
            Food products
          </label>
        </fieldset>
        <input type="submit" value="submit">
      </form>
      <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
  </main>
</body>
</html>
