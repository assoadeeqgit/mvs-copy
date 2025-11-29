<?php
// config.php
$servername = "localhost";
$database = "mvs";
$username = "root";
$password = "A@123456.Aaa";

try {
    $dbh = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch (PDOException $e) {
    // Log the error but don't show to users
    error_log("Database connection failed: " . $e->getMessage());
    // Set $dbh to null so we can check for it later
    $dbh = null;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
