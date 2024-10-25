<?php
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$option_selected = isset($_SESSION['option_selected']) ? $_SESSION['option_selected'] : '';
unset($_SESSION['message']);
unset($_SESSION['option_selected']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Success</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
    <style>
        body {
            max-width: 600px;
            margin: auto;
            padding: 1em;
            text-align: center;
        }
        .message {
            font-size: 1.5em;
            color: green;
        }
        .option-selected {
            font-size: 1.2em;
            color: blue;
        }
    </style>
</head>
<body>
    <h2>Survey Response</h2>
    <div class="message">
        <?php echo $message; ?>
    </div>
    <div class="option-selected">
        You chose: <strong><?php echo htmlspecialchars($option_selected); ?></strong>
    </div>
    <a href="index.html" class="button">Go Back</a>
</body>
</html>
