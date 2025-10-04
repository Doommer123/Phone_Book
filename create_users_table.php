<?php
// create_users_table.php
include 'db.php';

echo "<!DOCTYPE html>
<html lang='ru'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Создание таблицы пользователей</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
        .info { color: #856404; padding: 10px; background: #fff3cd; border-radius: 5px; margin: 10px 0; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Настройка системы авторизации</h1>";

try {
    // Удаляем старую таблицу если есть (для чистоты)
    $conn->query("DROP TABLE IF EXISTS users");
    
    // Создаем таблицу пользователей с правильной структурой
    $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✅ Таблица пользователей создана успешно</div>";
        
        // Создаем тестового пользователя (БЕЗ email)
        $username = "admin";
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        
        if ($stmt->execute()) {
            echo "<div class='success'>✅ Тестовый пользователь создан</div>";
            echo "<div class='info'>
                <strong>Данные для входа:</strong><br>
                Логин: <strong>admin</strong><br>
                Пароль: <strong>admin123</strong>
            </div>";
        } else {
            echo "<div class='error'>❌ Ошибка создания пользователя: " . $conn->error . "</div>";
        }
        
        $stmt->close();
        
    } else {
        echo "<div class='error'>❌ Ошибка создания таблицы: " . $conn->error . "</div>";
    }
    
    // Проверяем существование таблиц
    echo "<h2>📊 Проверка структуры базы данных</h2>";
    
    $tables = ['users', 'Categories', 'Contacts'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<div class='success'>✅ Таблица '$table' существует</div>";
            
            // Показываем структуру таблицы
            $structure = $conn->query("DESCRIBE $table");
            echo "<div style='font-size: 12px; background: #f8f9fa; padding: 10px; margin: 5px 0; border-radius: 5px;'>";
            echo "<strong>Структура $table:</strong><br>";
            while ($row = $structure->fetch_assoc()) {
                echo "• {$row['Field']} ({$row['Type']})<br>";
            }
            echo "</div>";
        } else {
            echo "<div class='error'>❌ Таблица '$table' не найдена</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Ошибка: " . $e->getMessage() . "</div>";
}

echo "
    <div style='margin-top: 20px; padding: 15px; background: #e9ecef; border-radius: 5px;'>
        <h3>🚀 Следующие шаги:</h3>
        <ol>
            <li><a href='login.php' target='_blank'>Перейти к форме входа</a></li>
            <li><a href='index.php' target='_blank'>Перейти на главную страницу</a></li>
            <li>Используйте логин: <strong>admin</strong> и пароль: <strong>admin123</strong></li>
        </ol>
    </div>";

echo "</div></body></html>";
?>