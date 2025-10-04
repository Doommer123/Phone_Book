<?php
// auth_check.php
session_start();

// Проверка авторизации пользователя
function checkAuth() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
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