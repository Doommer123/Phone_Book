<?php
include 'db.php';
$user = 'admin';
$pass = password_hash('пароль', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
echo "Пользователь добавлен";
?>
