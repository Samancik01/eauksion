<?php
// Database connection
require 'includes/db.php';

session_start();

// Foydalanuvchini tekshirish (faqat sotuvchilar mahsulot qo'sha oladi)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    die("Siz mahsulot qo'sha olmaysiz. Tizimga kirganingizni va sotuvchi ekanligingizni tekshiring.");
}


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $startPrice = $_POST['start_price'];
    $endTime = $_POST['end_time'];
    $sellerId = $_SESSION['user_id'];

    // Handle images upload
    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        try {
            $uploadedImages = [];
            $totalFiles = count($_FILES['images']['name']);

            for ($i = 0; $i < $totalFiles; $i++) {
                $image = $_FILES['images'];

                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($image['type'][$i], $allowedTypes)) {
                    $imagePath = 'uploads/' . basename($image['name'][$i]);

                    // Move file to the upload directory
                    if (move_uploaded_file($image['tmp_name'][$i], $imagePath)) {
                        $uploadedImages[] = $imagePath;
                    } else {
                        echo "Rasm yuklashda xato yuz berdi.";
                    }
                } else {
                    echo "Faqat rasm formatidagi fayllar qabul qilinadi (JPEG, PNG, GIF).";
                }
            }


            // Insert product into the database with additional fields
            $statement = $pdo->prepare("INSERT INTO products (title, description, start_price, end_time, seller_id) VALUES (?, ?, ?, ?, ?)");
            $statement->execute([$title, $description, $startPrice, $endTime, $sellerId]);

            $productId = $pdo->lastInsertId(); // Get the last inserted product ID

            // Insert images for the product
            foreach ($uploadedImages as $imagePath) {
                $statement = $pdo->prepare("INSERT INTO product_images (product_id, image) VALUES (?, ?)");
                $statement->execute([$productId, $imagePath]);
            }

            echo "Mahsulot muvaffaqiyatli qo'shildi!";
        }
        catch (PDOException $e) {
            echo "Xatolik: " . $e->getMessage();
        }

    } else {
        // Insert product without images
        $statement = $pdo->prepare("INSERT INTO products (title, description, start_price, end_time, seller_id) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->execute([$title, $description, $startPrice, $endTime, $sellerId]);

        echo "Mahsulot(Rasmlarsiz) muvaffaqiyatli qo'shildi!";
    }
}
?>


<form action="ad_product.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="title">Mahsulot nomi:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div>
        <label for="description">Tavsif:</label>
        <textarea name="description" id="description" required></textarea>
    </div>
    <div>
        <label for="start_price">Boshlang'ich narx:</label>
        <input type="number" name="start_price" id="start_price" step="0.01" required>
    </div>
    <div>
        <label for="end_time">Auksion tugash vaqti:</label>
        <input type="datetime-local" name="end_time" id="end_time" required>
    </div>
    <div>
        <label for="images">Rasmlar:</label>
        <input type="file" name="images[]" id="images" multiple>
    </div>
    <button type="submit">Mahsulotni qo'shish</button>
</form>

