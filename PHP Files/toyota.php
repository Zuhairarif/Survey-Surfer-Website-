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
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Process form submission
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $favoriteToyota = isset($_POST['Toyota']) ? sanitizeInput($_POST['Toyota']) : null;

    if ($favoriteToyota) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO toyota_survey (favorite_toyota_car, submission_time) VALUES (?, NOW())");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("s", $favoriteToyota);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "Thank you for participating in our survey! Your favorite Toyota car is: <strong>$favoriteToyota</strong>";
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        $message = "No Toyota car selected. Please go back and select your favorite Toyota car.";
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
    <title>Toyota | Survey Surfers</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
<main class="container">
    <h1>Toyota Survey</h1>
    <article>
        <header><strong>Q1: Which is your favourite Toyota car?</strong></header>
        <form method="POST" action="toyota.php">
            <fieldset>
                <label>
                    <input type="radio" name="Toyota" value="Toyota Camry" />
                    Toyota Camry
                </label>
                <label>
                    <input type="radio" name="Toyota" value="Toyota RAV4" />
                    Toyota RAV4
                </label>
                <label>
                    <input type="radio" name="Toyota" value="Toyota Prius" />
                    Toyota Prius
                </label>
                <label>
                    <input type="radio" name="Toyota" value="Toyota Highlander" />
                    Toyota Highlander
                </label>
            </fieldset>
            <input type="submit" value="submit">
        </form>
        <p><?php echo isset($message) ? $message : ''; ?></p>
    </article>
</main>
</body>
</html>
