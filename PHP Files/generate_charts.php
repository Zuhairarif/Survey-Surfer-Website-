<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Access denied.");
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

// Function to fetch survey data and return it in a format suitable for Chart.js
function getSurveyData($conn, $table, $column) {
    $sql = "SELECT $column, COUNT(*) as count FROM $table GROUP BY $column";
    $result = $conn->query($sql);

    $labels = [];
    $counts = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row[$column];
        $counts[] = $row['count'];
    }

    return [
        'labels' => $labels,
        'datasets' => [
            [
                'data' => $counts,
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ]
            ]
        ]
    ];
}

$surveyData = [];

// Fetch data for all surveys
$surveyData['adidas'] = getSurveyData($conn, 'adidas_survey', 'favorite_adidas_product');
$surveyData['apple'] = getSurveyData($conn, 'apple_company_survey', 'favorite_iPhone');
$surveyData['colgate'] = getSurveyData($conn, 'colgate_survey', 'colgate_product');
$surveyData['cricket'] = getSurveyData($conn, 'cricket_survey', 'favorite_cricketer');
$surveyData['dell'] = getSurveyData($conn, 'dell_company', 'favorite_dell_laptop');
$surveyData['disney'] = getSurveyData($conn, 'disney_survey', 'favorite_disney_streaming');
$surveyData['gaming'] = getSurveyData($conn, 'gaming_survey', 'favorite_playstation');
$surveyData['sony'] = getSurveyData($conn, 'sony_survey', 'favorite_headphone');
$surveyData['toyota'] = getSurveyData($conn, 'toyota_survey', 'favorite_toyota_car');

header('Content-Type: application/json');
echo json_encode($surveyData);

$conn->close();
?>
