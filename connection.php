<?php
$dsn = "mysql:host=127.0.0.1;dbname=happy_herbivore;charset=utf8mb4";
$username = "root";
$password = "";

try {
    //create pdo instance
    $pdo = new PDO($dsn, $username, $password, [
        //enabling error handling
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //fetching results as an associative array (my beloved <3)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}