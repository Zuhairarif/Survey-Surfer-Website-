<?php
session_start(); // Start the session

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: signup.php"); // Redirect if not logged in
    exit();
}

// Fetch user details from session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Clear session variables
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .success-box {
            background-color: #ffffff;
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #4CAF50;
        }
        p {
            color: #333333;
        }
        .survey {
            margin-top: 1em;
            padding-top: 1em;
            border-top: 1px solid #dddddd;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h2>Account Created Successfully!</h2>
        <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
        <div class="survey">
            <p>Thank you for joining! Please participate in our surveys for more fun and rewards.</p>
        </div>
    </div>
</body>
</html>
