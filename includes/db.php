<?php

$host = 'localhost'; // Server manzili
$dbname = 'online_auction'; // Ma'lumotlar bazasi nomi
$username = 'root'; // MySQL foydalanuvchi nomi
$password = ''; // MySQL paroli (XAMPP uchun odatda bo'sh)

// PDO orqali ulanish
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ma'lumotlar bazasiga ulanishda xatolik: " . $e->getMessage());
}