<?php
$host = 'localhost';
$dbname = 'qlnhansu';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $conn = $pdo;
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function closeConnection($pdo) {
    $pdo = null;
}
?>