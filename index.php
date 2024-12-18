<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh Sahifa</title>
</head>
<body>
    <?php if (isset($_SESSION['username'])): ?>
        <p>Salom, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="logout.php">Chiqish</a></p>
    <?php else: ?>
        <p><a href="login.php">Kirish</a> | <a href="register.php">Ro'yxatdan o'tish</a></p>
    <?php endif; ?>
<?php
// Database connection
require 'includes/db.php';

// Mahsulotlarni bazadan olish
$statement = $pdo->prepare("SELECT * FROM products");
$statement->execute();
$products = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh Sahifa</title>
    <style>
        .product {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .product img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .product a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .product a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Mahsulotlar</h1>
    
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h3><?= htmlspecialchars($product['title']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p>Joriy narx: <?= number_format($product['start_price'], 2) ?> so'm</p>
                
      <?php          // Rasmlar ro'yxatini olish
    $productId = $product['id'];
    $imageStatement = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $imageStatement->execute([$productId]);
    $images = $imageStatement->fetchAll();

    // Har bir rasmni chiqarish
    echo "<div class='product-images'>";
    foreach ($images as $image) {
        echo "<img src='" . $image['image'] . "' alt='Product Image' style='width:100px;height:auto; margin: 5px;'>";
    }
           ?>     
                <!-- Taklif kiritish sahifasiga havola -->
                <p><a href="bid.php?product_id=<?= htmlspecialchars($product['id']) ?>">Taklif kiritish</a></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Hozircha mahsulot mavjud emas.</p>
    <?php endif; ?>
</body>
</html>

    