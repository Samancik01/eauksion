<?php
require 'includes/db.php';
// Mahsulotlarni olish
$statement = $pdo->prepare("SELECT * FROM products");
$statement->execute();
$products = $statement->fetchAll();

echo "<h1>Maxsulotlar Ro'yxati</h1>";

// Har bir mahsulot uchun rasmlarni olish
foreach ($products as $product) {
    echo "<h2>Maxsulot nomi: " . htmlspecialchars($product['title']) . "</h2>";
    echo "<p><strong>Maxsulot haqida qisqacha</strong>: " . htmlspecialchars($product['description']) . "</p>";
    echo "<p><strong>Boshlang'ich narxi:</strong> " . htmlspecialchars(number_format($product['start_price'], 2)) . " so'm</p>";
    echo "<p><strong>Tugash vaqti</strong>: " . htmlspecialchars($product['end_time']) . "</p>";
    
    // Rasmlar ro'yxatini olish
    $productId = $product['id'];
    $imageStatement = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $imageStatement->execute([$productId]);
    $images = $imageStatement->fetchAll();

    // Har bir rasmni chiqarish
    echo "<div class='product-images'>";
    foreach ($images as $image) {
        echo "<img src='" . $image['image'] . "' alt='Product Image' style='width:100px;height:auto; margin: 5px;'>";
    }

    echo "<p><a href='bid.php?product_id=" . $product['id'] . "'>Taklif kiritish</a></p>";

    echo "</div>";


    echo "<hr>"; // Mahsulotlar orasida chiziq
}
