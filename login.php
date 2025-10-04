<?php
session_start();
include 'db.php';

$error_message = '';
$success_message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    // Валидация
    if (empty($user) || empty($pass)) {
        $error_message = "Все поля обязательны для заполнения";
    } else {
        // Используем правильные названия столбцов
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $db_user, $db_pass);
        
        if ($stmt->fetch() && password_verify($pass, $db_pass)) {
            $_SESSION['user'] = $db_user;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['login_time'] = time();
            $success_message = "Вход выполнен успешно!";
            
            // Перенаправление на главную страницу
            header("Refresh: 2; url=index.php");
        } else {
            $error_message = "Неверный логин или пароль";
        }
        $stmt->close();
    }
}

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему - Городской телефонный справочник</title>
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
            max-width: 400px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-icon {
            margin-right: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Вход в систему</h1>
            <p>Городской телефонный справочник</p>
        </div>

        <?php
        if (!empty($error_message)) {
            echo "
            <div class='alert alert-error'>
                <span class='alert-icon'>⚠️</span>
                {$error_message}
            </div>";
        }

        if (!empty($success_message)) {
            echo "
            <div class='alert alert-success'>
                <span class='alert-icon'>✅</span>
                {$success_message}
            </div>";
        }
        ?>

        <form method="POST" id="loginForm">
            <div class="form-group">
                <input 
                    type="text" 
                    name="username" 
                    class="form-input" 
                    placeholder="Логин (admin)"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <input 
                    type="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Пароль (admin123)"
                    required
                >
            </div>

            <button type="submit" class="btn">
                Войти в систему
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Фокус на поле логина при загрузке
            document.querySelector('input[name="username"]').focus();
        });
    </script>
</body>
</html>