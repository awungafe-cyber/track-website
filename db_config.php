<?php
// db_config.php
$conn = new mysqli("localhost", "root", "", "logistics_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>