<?php

$host = 'sql309.infinityfree.com';
$dbname = 'if0_43385341_grocery_stock_db';
$username = 'if0_43385341';
$password = 'Joshuazerrudo12';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Database connection failed.");

}
?>