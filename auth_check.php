<?php
session_start();

// Проверка авторизации пользователя
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
    
    // Проверка времени сессии (опционально - 8 часов)
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 28800)) {
        session_destroy();
        header("Location: login.php?error=session_expired");
        exit();
    }
}

// Получение имени текущего пользователя
function getCurrentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

// Выход из системы
function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>