<?php
include 'auth_check.php';
checkAuth();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Городской телефонный справочник</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .user-info {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 15px 20px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .icon {
            margin-right: 15px;
            font-size: 1.2em;
            color: #667eea;
        }

        .stats {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            font-size: 0.9em;
            color: #666;
        }

        .footer {
            margin-top: 20px;
            color: #999;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <div>
                👤 Вы вошли как: <strong><?php echo $_SESSION['user']; ?></strong>
            </div>
            <a href="logout.php" class="logout-btn">🚪 Выйти</a>
        </div>
        
        <div class="header">
            <h1>📞 Городской телефонный справочник</h1>
            <p>Управление контактами и категориями</p>
        </div>

        <div class="menu">
            <a href="add_category.php" class="menu-item">
                <span class="icon">📁</span>
                Добавить категорию
            </a>
            
            <a href="add_contact.php" class="menu-item">
                <span class="icon">👤</span>
                Добавить контакт
            </a>
            
            <a href="search_contact.php" class="menu-item">
                <span class="icon">🔍</span>
                Поиск контакта
            </a>
        </div>

        <div class="stats">
            <p>🔄 Система работает стабильно</p>
            <p>💾 XAMPP + PHP + MySQL</p>
            <p>🔐 Авторизованный доступ</p>
        </div>

        <div class="footer">
            &copy; <?php echo date('Y'); ?> Городской телефонный справочник
        </div>
    </div>
</body>
</html>