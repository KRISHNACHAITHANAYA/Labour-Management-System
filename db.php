<?php
// Database connection settings
$host = 'localhost';        // Hostname (localhost for local development)
$dbname = 'labour_job_portal'; // Database name
$username = 'root';         // Database username (default for XAMPP is 'root')
$password = '';             // Database password (default for XAMPP is empty)

// Create the PDO instance to connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there is an error, display the error message
    die("Connection failed: " . $e->getMessage());
}
?>
