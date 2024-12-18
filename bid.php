<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
require 'includes/db.php';

// `product_id` GET orqali yuborilganmi?
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Mahsulotni bazadan yuklash
    $statement = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $statement->execute([$product_id]);
    $product = $statement->fetch();

    if (!$product) {
        echo "Xatolik: Mahsulot topilmadi.";
        exit;
    }
} else {
    echo "Xatolik: Mahsulot ID kiritilmagan yoki noto‘g‘ri.";
    exit;
}

// Get all bids
$statement = $pdo->prepare("SELECT * FROM bids");
$statement->execute();
$bids = $statement->fetchAll();


echo "<h1>Taklilar</h1>";

// Display bids

foreach ($bids as $bid) {
    // Get the product associated with this bid
    $productStatement = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $productStatement->execute([$bid['product_id']]);
    $product = $productStatement->fetch();

    
    if($product){
        echo "<h3>Maxsulotga: " . $product['title'] . " - taklif</h3>";
        echo "<p><strong>Taklif qildi :</strong> " . $bid['user_id'] . " ID raqamli foydalanuvchi</p>";
        echo "<p><strong>Taklif qildi:</strong> " . number_format($bid['bid_price'] , 2). " so'm</p>";
    }
    // Display bid and product details


    // Get images for the product
    $imageStatement = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $imageStatement->execute([$product['id']]);
    $images = $imageStatement->fetchAll();

    echo "<div class='product-images'>";
    foreach ($images as $image) {
        echo "<img src='" . $image['image'] . "' alt='Product Image' style='width:100px;height:100px;'>";
    }
    echo "</div>";
    echo "<hr>";
}
// Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = 1; // Foydalanuvchi ID
    $bid_price = $_POST['bid_price'];

    // Mahsulotni qayta olish
    $statement = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $statement->execute([$product_id]);
    $product = $statement->fetch();

    if ($product) {
        // Taklif miqdorini tekshirish
        if ($bid_price > $product['start_price']) {
            // Taklifni qo‘shish
            $statement = $pdo->prepare("INSERT INTO bids (product_id, user_id, bid_price) VALUES (?, ?, ?)");
            $statement->execute([$product_id, $user_id, $bid_price]);

            // Eng so'nggi narxni yangilash
            $updateStatement = $pdo->prepare("UPDATE products SET start_price = ? WHERE id = ?");
            $updateStatement->execute([$bid_price, $product_id]);

            echo "Taklif muvaffaqiyatli kiritildi!";
        } else {
            echo "Taklif miqdori mahsulotning joriy narxidan yuqori bo'lishi kerak.";
        }
    } else {
        echo "Xatolik: Mahsulot topilmadi.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taklif Kiritish</title>
</head>
<body>
    <h1>Taklif Kiritish</h1>
    <h3><?= htmlspecialchars($product['title']) ?></h3>
    <p><?= htmlspecialchars($product['description']) ?></p>
    <p>Joriy narx: <?= htmlspecialchars($product['start_price']) ?> so'm</p>

    <?php
    // Mahsulot rasmlarini chiqarish
    $imageStatement = $pdo->prepare("SELECT image FROM product_images WHERE product_id = ?");
    $imageStatement->execute([$product['id']]);
    $images = $imageStatement->fetchAll();
    ?>

    <div class="product-images">
        <?php if (!empty($images)): ?>
            <h4>Mahsulot rasmlari:</h4>
            <?php foreach ($images as $image): ?>
                <img src="<?= htmlspecialchars($image['image']) ?>" alt="Mahsulot rasmi" style="width: 100px; height: 100px;">
            <?php endforeach; ?>
        <?php else: ?>
            <p>Rasm mavjud emas.</p>
        <?php endif; ?>
    </div>

    <form method="POST" action="bid.php">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
        <input type="number" name="bid_price" step="0.01" min="<?= htmlspecialchars($product['start_price']) ?>" placeholder="Taklif miqdori" required>
        <button type="submit">Taklif kiritish</button>
    </form>
</body>
</html>

