<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск контактов - Городской телефонный справочник</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1em;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .form-group {
            flex: 1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95em;
        }

        .search-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1.1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-input::placeholder {
            color: #999;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            background: #6c757d;
            margin-right: 15px;
        }

        .btn-back:hover {
            background: #5a6268;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-icon {
            margin-right: 8px;
        }

        .results-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .results-title {
            color: #333;
            font-size: 1.5em;
            font-weight: 600;
        }

        .results-count {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .contacts-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .contacts-table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        .contacts-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .contacts-table tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .contact-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        .contact-phone {
            color: #667eea;
            font-weight: 600;
            font-size: 1.1em;
        }

        .contact-address {
            color: #666;
            font-size: 0.95em;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-results-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-results h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #333;
        }

        .search-tips {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .search-tips h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .search-tips ul {
            color: #666;
            padding-left: 20px;
        }

        .search-tips li {
            margin-bottom: 5px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .export-btn {
            background: #28a745;
        }

        .export-btn:hover {
            background: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .contacts-table {
                display: block;
                overflow-x: auto;
            }
            
            .results-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .header h1 {
                font-size: 2em;
            }
        }

        .highlight {
            background: linear-gradient(120deg, #ffd70044, #ffd70044);
            padding: 2px 4px;
            border-radius: 4px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Поиск контактов</h1>
            <p>Найдите контакт по имени или номеру телефона</p>
        </div>

        <div class="search-section">
            <form method="GET" id="searchForm">
                <div class="search-form">
                    <div class="form-group">
                        <label class="form-label">Введите имя или номер телефона</label>
                        <input 
                            type="text" 
                            name="keyword" 
                            class="search-input" 
                            placeholder="Например: Иван или +7 (912) ..."
                            value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
                            required
                            autofocus
                        >
                    </div>
                    <button type="submit" class="btn">
                        <span class="btn-icon">🔍</span> Найти
                    </button>
                </div>
            </form>
        </div>

        <?php
        include 'db.php';

        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
            $keyword = '%' . $conn->real_escape_string(trim($_GET['keyword'])) . '%';
            $search_term = trim($_GET['keyword']);

            // Подсветка в результатах
            function highlight($text, $keyword) {
                $pattern = '/(' . preg_quote($keyword, '/') . ')/i';
                return preg_replace($pattern, '<span class="highlight">$1</span>', htmlspecialchars($text));
            }

            $stmt = $conn->prepare("SELECT contactid, name, phone, address, description FROM Contacts WHERE name LIKE ? OR phone LIKE ? ORDER BY name");
            $stmt->bind_param("ss", $keyword, $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
            $total_results = $result->num_rows;
        ?>
        
        <div class="results-section">
            <div class="results-header">
                <div class="results-title">Результаты поиска</div>
                <div class="results-count">Найдено: <?= $total_results ?></div>
            </div>

            <?php if ($total_results > 0): ?>
                <div class="table-container">
                    <table class="contacts-table">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="25%">Имя</th>
                                <th width="20%">Телефон</th>
                                <th width="30%">Адрес</th>
                                <th width="20%">Описание</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['contactid'] ?></td>
                                    <td>
                                        <div class="contact-name"><?= highlight($row['name'], $search_term) ?></div>
                                    </td>
                                    <td>
                                        <div class="contact-phone"><?= highlight($row['phone'], $search_term) ?></div>
                                    </td>
                                    <td>
                                        <div class="contact-address"><?= $row['address'] ? highlight($row['address'], $search_term) : '<span style="color: #999;">—</span>' ?></div>
                                    </td>
                                    <td>
                                        <div class="contact-address"><?= $row['description'] ? highlight($row['description'], $search_term) : '<span style="color: #999;">—</span>' ?></div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="actions">
                    <div>
                        <a href="index.php" class="btn btn-back">
                            <span class="btn-icon">←</span> На главную
                        </a>
                        <button onclick="window.print()" class="btn">
                            <span class="btn-icon">🖨️</span> Печать
                        </button>
                    </div>
                    <a href="add_contact.php" class="btn">
                        <span class="btn-icon">👤</span> Добавить контакт
                    </a>
                </div>

            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    <h3>Ничего не найдено</h3>
                    <p>Попробуйте изменить поисковый запрос</p>
                    
                    <div class="search-tips">
                        <h4>Советы по поиску:</h4>
                        <ul>
                            <li>Проверьте правильность написания имени</li>
                            <li>Используйте часть имени или фамилии</li>
                            <li>Попробуйте поиск по последним цифрам телефона</li>
                            <li>Убедитесь, что контакт существует в справочнике</li>
                        </ul>
                    </div>
                </div>

                <div class="actions">
                    <a href="index.php" class="btn btn-back">
                        <span class="btn-icon">←</span> На главную
                    </a>
                    <a href="add_contact.php" class="btn">
                        <span class="btn-icon">👤</span> Добавить контакт
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php } else if (isset($_GET['keyword'])) { ?>
            <div class="results-section">
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    <h3>Введите поисковый запрос</h3>
                    <p>Пожалуйста, введите имя или номер телефона для поиска</p>
                </div>
            </div>
        <?php } else { ?>
            <div class="results-section">
                <div class="no-results">
                    <div class="no-results-icon">📋</div>
                    <h3>Начните поиск</h3>
                    <p>Введите имя или номер телефона в поле выше для поиска контактов</p>
                    
                    <div class="search-tips">
                        <h4>Возможности поиска:</h4>
                        <ul>
                            <li>Поиск по имени (полностью или частично)</li>
                            <li>Поиск по номеру телефона (полностью или частично)</li>
                            <li>Регистр не имеет значения</li>
                            <li>Можно искать по адресу и описанию</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.querySelector('input[name="keyword"]');
            
            // Фокус на поле поиска при загрузке
            searchInput.focus();
            
            // Очистка поиска при двойном клике
            searchInput.addEventListener('dblclick', function() {
                this.value = '';
                this.focus();
            });
            
            // Автопоиск при вводе (опционально)
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                if (this.value.length >= 2) {
                    // Можно добавить автопоиск при вводе 2+ символов
                    // searchTimeout = setTimeout(() => searchForm.submit(), 500);
                }
            });
            
            // Сохранение позиции скролла при обновлении
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('searchScrollPos', window.scrollY);
            });
            
            // Восстановление позиции скролла
            const savedScrollPos = sessionStorage.getItem('searchScrollPos');
            if (savedScrollPos) {
                window.scrollTo(0, parseInt(savedScrollPos));
                sessionStorage.removeItem('searchScrollPos');
            }
        });
    </script>
</body>
</html>