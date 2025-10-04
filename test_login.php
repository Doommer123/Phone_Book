<?php
// test_login.php - для отладки
include 'db.php';

echo "<h2>Отладка системы входа</h2>";

// Проверяем таблицу users
$result = $conn->query("SELECT * FROM users");
echo "<h3>Пользователи в базе:</h3>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . "<br>";
        echo "Логин: " . $row['username'] . "<br>";
        echo "Пароль (хеш): " . $row['password'] . "<br>";
        
        // Проверяем пароль
        $test_password = "admin123";
        if (password_verify($test_password, $row['password'])) {
            echo "✅ Пароль 'admin123' верный!<br>";
        } else {
            echo "❌ Пароль 'admin123' неверный!<br>";
        }
        echo "<hr>";
    }
} else {
    echo "❌ Пользователи не найдены";
}

echo "<p><a href='login.php'>Вернуться к форме входа</a></p>";
?>