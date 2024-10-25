<?php
session_start(); // Start the session

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

// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Process form submission
$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = sanitizeInput($_POST['name']);
    $login = sanitizeInput($_POST['login']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    // Check if login field is provided
    if (empty($login)) {
        $message = "Please provide either an email address or a phone number.";
    } else if ($password !== $confirm_password) {
        $message = "Passwords do not match. Please try again.";
    } else {
        // Determine if login is an email or phone number
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $email = $login;
            $phone = null;
        } else {
            $email = null;
            $phone = $login;
        }

        // Hash the password
        $hashedPassword = hashPassword($password);

        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO users (`Full Name`, Gmail, `Phone Number`, Password) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

            // Execute the statement
            if ($stmt->execute()) {
                // Set session variables for the logged-in user
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $name;

                // Redirect to account.php on success
                header("Location: account.php");
                exit();
            } else {
                $message = "Error executing statement: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
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
    <title>Create Your Account</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
    <style>
        body {
            max-width: 600px;
            margin: auto;
            padding: 1em;
        }
        .message {
            font-size: 1.2em;
            color: green;
            margin-top: 1em;
        }
    </style>
    <script>
        function validateForm() {
            var login = document.forms["registerForm"]["login"].value;
            var password = document.forms["registerForm"]["password"].value;
            var confirmPassword = document.forms["registerForm"]["confirm_password"].value;

            if (login == "") {
                alert("Please provide either an email address or a phone number.");
                return false;
            }

            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <h2>Create Your Account</h2>
    <form name="registerForm" action="signup.php" method="POST" onsubmit="return validateForm()">
        <h3>Enter your full name</h3>
        <input type="text" name="name" placeholder="Full Name" aria-label="Full Name" required />

        <h3>Enter your Gmail or phone number (any one)</h3>
        <input type="text" name="login" placeholder="Email or Phone Number" aria-label="Email or Phone Number" required />

        <h3>Enter your password</h3>
        <input type="password" name="password" placeholder="Password" aria-label="Password" required />

        <h3>Confirm your password</h3>
        <input type="password" name="confirm_password" placeholder="Confirm Password" aria-label="Confirm Password" required />

        <input type="submit" value="Create Your Account" />
    </form>
    <?php if (!empty($message)) { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>
</body>
</html>
