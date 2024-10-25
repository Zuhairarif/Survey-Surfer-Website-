<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "id22299453_apple";
$password = "Zuhair@arif1";
$dbname = "id22299453_apple";

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

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $login = isset($_POST['login']) ? sanitizeInput($_POST['login']) : null;
    $password = isset($_POST['password']) ? sanitizeInput($_POST['password']) : null;

    // Check if login and password are provided
    if ($login === null || $password === null) {
        $message = "Please enter both email/phone number and password.";
    } else {
        // Prepare SQL statement to fetch hashed password for the provided email or phone number
        $stmt = $conn->prepare("SELECT id, Password, `Full Name` FROM users WHERE Gmail = ? OR `Phone Number` = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        // Bind parameters
        $stmt->bind_param("ss", $login, $login);
        
        // Execute the statement
        $stmt->execute();
        
        // Check for errors in execution
        if ($stmt->errno) {
            die("Error executing statement: " . $stmt->error);
        }
        
        // Bind result variables
        $stmt->bind_result($user_id, $hashedPassword, $fullName);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // Verify password using password_verify function
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['id'] = $user_id; // Set user ID in session
                $_SESSION['user_name'] = $fullName; // Set user name in session
                // Redirect to the main page or survey page
                header("Location: index.html");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "Invalid email/phone number.";
        }

        // Close statement
        $stmt->close();
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
    <title>Login</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
    <style>
        body {
            max-width: 600px;
            margin: auto;
            padding: 1em;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <input type="text" name="login" placeholder="Email or Phone Number" aria-label="Email or Phone Number" required>
        <input type="password" name="password" placeholder="Password" aria-label="Password" required>
        <input type="submit" value="Submit">
    </form>
    <h5>Don't have an account? <a href="signup.html">Sign Up</a></h5>
    <p><?php echo $message; ?></p>
</body>
</html>
