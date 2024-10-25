<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate session id
if (!isset($_SESSION['id'])) {
    die("User ID not set in session.");
}

// Database connection parameters
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

// Initialize message variables
$message = "";
$option_selected = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure topic is set and valid
    if (!isset($_POST['topic']) || empty($_POST['topic'])) {
        die("Invalid or no topic specified.");
    }
    $topic = $_POST['topic'];

    // Ensure question and option are set
    if (!isset($_POST['question']) || !isset($_POST['option'])) {
        die("Missing question or option.");
    }
    $question = $_POST['question'];
    $option_selected = $_POST['option'];

    // Prepare INSERT statement based on the survey topic
    switch ($topic) {
        case 'adidas':
            $stmt = $conn->prepare("INSERT INTO adidas_survey (favorite_adidas_product, submission_time) VALUES (?, NOW())");
            break;
        case 'apple':
            $stmt = $conn->prepare("INSERT INTO apple_company_survey (favorite_iPhone, submission_time) VALUES (?, NOW())");
            break;
        case 'colgate':
            $stmt = $conn->prepare("INSERT INTO colgate_survey (colgate_product, submission_time) VALUES (?, NOW())");
            break;
        case 'cricket':
            $stmt = $conn->prepare("INSERT INTO cricket_survey (favorite_cricketer, submission_time) VALUES (?, NOW())");
            break;
        case 'dell':
            $stmt = $conn->prepare("INSERT INTO dell_company (favorite_dell_laptop, submission_time) VALUES (?, NOW())");
            break;
        case 'disney':
            $stmt = $conn->prepare("INSERT INTO disney_survey (favorite_disney_streaming, submission_time) VALUES (?, NOW())");
            break;
        case 'gaming':
            $stmt = $conn->prepare("INSERT INTO gaming_survey (favorite_playstation, submission_time) VALUES (?, NOW())");
            break;
        case 'sony':
            $stmt = $conn->prepare("INSERT INTO sony_survey (favorite_headphone, submission_time) VALUES (?, NOW())");
            break;
        case 'toyota':
            $stmt = $conn->prepare("INSERT INTO toyota_survey (favorite_toyota_car, submission_time) VALUES (?, NOW())");
            break;
        default:
            die("Invalid survey topic.");
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("s", $option_selected);
    $stmt->execute();

    // Check for successful insertion
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Survey response inserted successfully.";
        $_SESSION['option_selected'] = $option_selected;
    } else {
        $_SESSION['message'] = "Error: Survey response not inserted.";
    }

    $stmt->close();

    // Redirect to the success page
    header("Location: success.php");
    exit();
} else {
    die("No form submission detected.");
}

$conn->close();
?>
