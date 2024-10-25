<?php
$servername = "localhost";
$username = "id22299453_apple"; // replace with your MySQL username
$password = "Zuhair@arif1"; // replace with your MySQL password
$dbname = "id22299453_apple"; // replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all table names
$tables_result = $conn->query("SHOW TABLES");
if (!$tables_result) {
    die("Query failed: " . $conn->error);
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=all_tables_export.csv');

$output = fopen('php://output', 'w');

while ($table_row = $tables_result->fetch_row()) {
    $table_name = $table_row[0];
    $sql = "SELECT * FROM $table_name";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Write table name as prefix
        fputcsv($output, array("Table: $table_name"));

        // Fetch fields
        $fields = $result->fetch_fields();
        $headers = array();
        foreach ($fields as $field) {
            $headers[] = $field->name;
        }
        fputcsv($output, $headers);

        // Fetch data
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }

        // Add an empty line between tables
        fputcsv($output, array());
    } else {
        echo "No records found in $table_name<br>";
    }
}

fclose($output);
$conn->close();
?>
