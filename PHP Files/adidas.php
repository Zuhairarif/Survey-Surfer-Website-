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
    $favoriteAdidasProduct = isset($_POST['Adidas']) ? sanitizeInput($_POST['Adidas']) : null;

    if ($favoriteAdidasProduct) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO adidas_survey (favorite_adidas_product, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoriteAdidasProduct);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite Adidas product is: <strong>$favoriteAdidasProduct</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No Adidas product selected. Please go back and select your favorite.";
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
    <title>Adidas | Survey Surfers</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
<main class="container">
    <h1>Adidas Survey</h1>
    <article>
        <header><strong>Q1: Which one is your favourite Adidas' product?</strong></header>
        <form method="POST" action="adidas.php">
            <fieldset>
                <label>
                    <input type="radio" name="Adidas" value="Adidas UltraBoost" checked>
                    Adidas UltraBoost
                </label>
                <label>
                    <input type="radio" name="Adidas" value="Adidas NMD">
                    Adidas NMD
                </label>
                <label>
                    <input type="radio" name="Adidas" value="Adidas Superstar">
                    Adidas Superstar
                </label>
                <label>
                    <input type="radio" name="Adidas" value="Adidas Stansmith">
                    Adidas Stansmith
                </label>
            </fieldset>
            <input type="submit" value="submit">
        </form>
        <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
</main>
</body>
</html>
