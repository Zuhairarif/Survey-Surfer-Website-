<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Hardcoded survey questions and options
$surveys = [
    'apple' => [
        'question' => 'Which is your favourite apple iPhone?',
        'options' => ['iPhone 13', 'iPhone 13 Pro', 'iPhone 13 Mini', 'iPhone SE']
    ],
    'dell' => [
        'question' => 'Which one is your favourite Dell Laptop?',
        'options' => ['Dell XPS 13', 'Dell Inspiron 15', 'Dell Latitude 14', 'Dell G3']
    ],
    'cricket' => [
        'question' => 'Who is your favourite player?',
        'options' => ['Dhoni', 'Kohli', 'Raina', 'Shami', 'Jadeja']
    ],
    'colgate' => [
        'question' => 'Which is your favourite type of product?',
        'options' => ['Health care and industrial supplies', 'Personal care products', 'Leisure and sports equipment', 'Food products']
    ],
    'adidas' => [
        'question' => 'Which one is your favourite Adidas product?',
        'options' => ['Adidas UltraBoost', 'Adidas NMD', 'Adidas Superstar', 'Adidas Stansmith']
    ],
    'disney' => [
        'question' => 'Which is your favourite Disney streaming service?',
        'options' => ['Disney+', 'Hulu', 'ESPN+', 'Disney+ Bundle']
    ],
    'sony' => [
        'question' => 'Which one is your favourite headphone of Sony?',
        'options' => ['Sony WH-1000XM4', 'Sony WF-1000XM4', 'Sony WH-XB900N', 'Sony MDR-ZX110']
    ],
    'toyota' => [
        'question' => 'Which is your favourite Toyota car?',
        'options' => ['Toyota Camry', 'Toyota RAV4', 'Toyota Prius', 'Toyota Highlander']
    ],
    'gaming' => [
        'question' => 'Which is your favourite Playstation?',
        'options' => ['Playstation 5', 'Playstation 4 Pro', 'Playstation 4 Slim', 'Playstation VR']
    ]
];

// Get the topic parameter from the URL
$topic = isset($_GET['topic']) ? htmlspecialchars(strip_tags(trim($_GET['topic']))) : null;

// Check if topic is valid
if (!$topic || !array_key_exists($topic, $surveys)) {
    die("Invalid or no topic specified.");
}

// Fetch the survey questions and options for the specified topic
$question = $surveys[$topic]['question'];
$options = $surveys[$topic]['options'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey on <?php echo htmlspecialchars($topic); ?> | Survey Surfers</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <h2>Here is your survey on <?php echo htmlspecialchars($topic); ?>:</h2>
        <form action="process_survey.php" method="post">
            <fieldset>
                <legend><?php echo htmlspecialchars($question); ?></legend>
                <?php foreach ($options as $index => $option): ?>
                    <label>
                        <input type="radio" name="option" value="<?php echo htmlspecialchars($option); ?>" <?php echo $index === 0 ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($option); ?>
                    </label><br>
                <?php endforeach; ?>
            </fieldset>
            <input type="hidden" name="topic" value="<?php echo htmlspecialchars($topic); ?>">
            <input type="hidden" name="question" value="<?php echo htmlspecialchars($question); ?>">
            <input type="submit" value="Submit Survey">
        </form>
    </main>
</body>
</html> 