<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить категорию - Городской телефонный справочник</title>
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

        .header p {
            color: #666;
            font-size: 1em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95em;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
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
            margin-right: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            background: #6c757d;
        }

        .btn-back:hover {
            background: #5a6268;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-icon {
            margin-right: 8px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
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

        .char-count {
            text-align: right;
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📁 Добавить категорию</h1>
            <p>Создайте новую категорию для организации контактов</p>
        </div>

        <?php
        include 'db.php';

        $show_success = false;
        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['categoryname']);
            $desc = trim($_POST['description']);

            // Валидация
            if (empty($name)) {
                $error_message = "Название категории обязательно для заполнения";
            } elseif (strlen($name) > 50) {
                $error_message = "Название категории не должно превышать 50 символов";
            } else {
                try {
                    // Проверяем, нет ли уже категории с таким названием
                    $check_stmt = $conn->prepare("SELECT categoryid FROM Categories WHERE categoryname = ?");
                    $check_stmt->bind_param("s", $name);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    if ($check_result->num_rows > 0) {
                        $error_message = "Категория с таким названием уже существует";
                    } else {
                        // Добавляем новую категорию
                        $stmt = $conn->prepare("INSERT INTO Categories (categoryname, description) VALUES (?, ?)");
                        $stmt->bind_param("ss", $name, $desc);
                        
                        if ($stmt->execute()) {
                            $show_success = true;
                            // Очищаем форму после успешного добавления
                            $_POST = array();
                        } else {
                            $error_message = "Ошибка при добавлении категории: " . $conn->error;
                        }
                    }
                } catch (Exception $e) {
                    $error_message = "Произошла ошибка: " . $e->getMessage();
                }
            }
        }

        // Показываем сообщения об ошибках
        if (!empty($error_message)) {
            echo "
            <div class='alert alert-error'>
                <span class='alert-icon'>⚠️</span>
                {$error_message}
            </div>";
        }

        // Показываем сообщение об успехе
        if ($show_success) {
            echo "
            <div class='alert alert-success'>
                <span class='alert-icon'>✅</span>
                Категория успешно добавлена!
            </div>";
        }
        ?>

        <form method="POST" id="categoryForm">
            <div class="form-group">
                <label class="form-label required">Название категории</label>
                <input 
                    type="text" 
                    name="categoryname" 
                    class="form-input" 
                    value="<?php echo isset($_POST['categoryname']) ? htmlspecialchars($_POST['categoryname']) : ''; ?>"
                    placeholder="Введите название категории"
                    maxlength="50"
                    required
                >
                <div class="char-count"><span id="nameCount">0</span>/50</div>
            </div>

            <div class="form-group">
                <label class="form-label">Описание категории</label>
                <textarea 
                    name="description" 
                    class="form-textarea" 
                    placeholder="Введите описание категории (необязательно)"
                    maxlength="200"
                ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <div class="char-count"><span id="descCount">0</span>/200</div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-back">
                    <span class="btn-icon">←</span> Назад
                </a>
                <button type="submit" class="btn">
                    <span class="btn-icon">📁</span> Добавить категорию
                </button>
            </div>
        </form>
    </div>

    <script>
        // Счетчик символов
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.querySelector('input[name="categoryname"]');
            const descTextarea = document.querySelector('textarea[name="description"]');
            const nameCount = document.getElementById('nameCount');
            const descCount = document.getElementById('descCount');

            function updateCount(element, counter, max) {
                const count = element.value.length;
                counter.textContent = count;
                if (count > max * 0.8) {
                    counter.style.color = '#e74c3c';
                } else {
                    counter.style.color = '#666';
                }
            }

            nameInput.addEventListener('input', function() {
                updateCount(this, nameCount, 50);
            });

            descTextarea.addEventListener('input', function() {
                updateCount(this, descCount, 200);
            });

            // Инициализация счетчиков
            updateCount(nameInput, nameCount, 50);
            updateCount(descTextarea, descCount, 200);

            // Подтверждение перед уходом если форма заполнена
            const form = document.getElementById('categoryForm');
            let formChanged = false;

            form.addEventListener('input', function() {
                formChanged = true;
            });

            window.addEventListener('beforeunload', function(e) {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            form.addEventListener('submit', function() {
                formChanged = false;
            });
        });
    </script>
</body>
</html>