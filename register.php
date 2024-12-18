<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Parolni shifrlash
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);
        echo "Ro'yxatdan muvaffaqiyatli o'tdingiz!";
    } catch (PDOException $e) {
        echo "Xatolik: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ro'yxatdan o'tish</title>
</head>
<body>
<h1>Ro'yxatdan o'tish</h1>
<form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="role" required>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
    </select><br>
    <button type="submit">Ro'yxatdan o'tish</button>
</form>
</body>
</html>
