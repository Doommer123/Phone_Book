<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить контакт - Городской телефонный справочник</title>
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
            max-width: 600px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95em;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
            font-family: inherit;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 12px;
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

        .phone-preview {
            margin-top: 5px;
            font-size: 0.9em;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Добавить контакт</h1>
            <p>Добавьте новый контакт в телефонный справочник</p>
        </div>

        <?php
        include 'db.php';

        $show_success = false;
        $error_message = '';

        // Получаем список категорий
        $result = $conn->query("SELECT categoryid, categoryname FROM Categories ORDER BY categoryname");
        $categories = [];
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryid = intval($_POST['categoryid']);
            $name = trim($_POST['name']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $desc = trim($_POST['description']);

            // Валидация
            if (empty($name)) {
                $error_message = "Имя контакта обязательно для заполнения";
            } elseif (empty($phone)) {
                $error_message = "Телефон обязателен для заполнения";
            } elseif (!preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
                $error_message = "Некорректный формат телефона";
            } elseif (strlen($name) > 100) {
                $error_message = "Имя не должно превышать 100 символов";
            } else {
                try {
                    // Проверяем, нет ли уже контакта с таким телефоном
                    $check_stmt = $conn->prepare("SELECT contactid FROM Contacts WHERE phone = ?");
                    $check_stmt->bind_param("s", $phone);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    if ($check_result->num_rows > 0) {
                        $error_message = "Контакт с таким телефоном уже существует";
                    } else {
                        // Добавляем новый контакт
                        $stmt = $conn->prepare("INSERT INTO Contacts (categoryid, name, phone, address, description) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("issss", $categoryid, $name, $phone, $address, $desc);
                        
                        if ($stmt->execute()) {
                            $show_success = true;
                            // Очищаем форму после успешного добавления
                            $_POST = array();
                        } else {
                            $error_message = "Ошибка при добавлении контакта: " . $conn->error;
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
                Контакт успешно добавлен в справочник!
            </div>";
        }

        // Проверяем есть ли категории
        if (empty($categories)) {
            echo "
            <div class='alert alert-error'>
                <span class='alert-icon'>📁</span>
                Для добавления контактов необходимо сначала создать категории. 
                <a href='add_category.php' style='color: #721c24; font-weight: bold;'>Создать категорию</a>
            </div>";
        }
        ?>

        <form method="POST" id="contactForm" <?php echo empty($categories) ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
            <div class="form-group">
                <label class="form-label required">Категория</label>
                <select name="categoryid" class="form-select" required>
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['categoryid'] ?>" 
                            <?php echo (isset($_POST['categoryid']) && $_POST['categoryid'] == $cat['categoryid']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['categoryname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Имя контакта</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-input" 
                        value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                        placeholder="Введите имя"
                        maxlength="100"
                        required
                    >
                    <div class="char-count"><span id="nameCount">0</span>/100</div>
                </div>

                <div class="form-group">
                    <label class="form-label required">Телефон</label>
                    <input 
                        type="text" 
                        name="phone" 
                        class="form-input" 
                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        placeholder="+7 (XXX) XXX-XX-XX"
                        maxlength="20"
                        required
                    >
                    <div class="phone-preview" id="phonePreview"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Адрес</label>
                <input 
                    type="text" 
                    name="address" 
                    class="form-input" 
                    value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
                    placeholder="Введите адрес"
                    maxlength="200"
                >
                <div class="char-count"><span id="addressCount">0</span>/200</div>
            </div>

            <div class="form-group">
                <label class="form-label">Описание</label>
                <textarea 
                    name="description" 
                    class="form-textarea" 
                    placeholder="Дополнительная информация о контакте"
                    maxlength="500"
                ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <div class="char-count"><span id="descCount">0</span>/500</div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-back">
                    <span class="btn-icon">←</span> Назад
                </a>
                <button type="submit" class="btn" <?php echo empty($categories) ? 'disabled' : ''; ?>>
                    <span class="btn-icon">💾</span> Добавить контакт
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Счетчики символов
            const nameInput = document.querySelector('input[name="name"]');
            const addressInput = document.querySelector('input[name="address"]');
            const descTextarea = document.querySelector('textarea[name="description"]');
            const nameCount = document.getElementById('nameCount');
            const addressCount = document.getElementById('addressCount');
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
                updateCount(this, nameCount, 100);
            });

            addressInput.addEventListener('input', function() {
                updateCount(this, addressCount, 200);
            });

            descTextarea.addEventListener('input', function() {
                updateCount(this, descCount, 500);
            });

            // Форматирование телефона
            const phoneInput = document.querySelector('input[name="phone"]');
            const phonePreview = document.getElementById('phonePreview');

            phoneInput.addEventListener('input', function() {
                let phone = this.value.replace(/\D/g, '');
                
                // Форматирование для российских номеров
                if (phone.startsWith('7') || phone.startsWith('8')) {
                    if (phone.length === 1) phone = '7';
                    else if (phone.length <= 4) phone = '+7 (' + phone.substring(1);
                    else if (phone.length <= 7) phone = '+7 (' + phone.substring(1, 4) + ') ' + phone.substring(4);
                    else if (phone.length <= 9) phone = '+7 (' + phone.substring(1, 4) + ') ' + phone.substring(4, 7) + '-' + phone.substring(7, 9);
                    else phone = '+7 (' + phone.substring(1, 4) + ') ' + phone.substring(4, 7) + '-' + phone.substring(7, 9) + '-' + phone.substring(9, 11);
                }
                
                this.value = phone;
                
                // Показываем preview
                if (phone.length > 5) {
                    phonePreview.textContent = `Телефон: ${phone}`;
                } else {
                    phonePreview.textContent = '';
                }
            });

            // Инициализация счетчиков
            updateCount(nameInput, nameCount, 100);
            updateCount(addressInput, addressCount, 200);
            updateCount(descTextarea, descCount, 500);

            // Подтверждение перед уходом если форма заполнена
            const form = document.getElementById('contactForm');
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

            // Фокус на первое поле при загрузке
            if (nameInput.value === '') {
                nameInput.focus();
            }
        });
    </script>
</body>
</html>