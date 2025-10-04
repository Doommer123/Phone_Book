<?php
/**
 * Подключение к базе данных - Городской телефонный справочник
 * Enhanced Version with Error Handling and Security
 */

class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "phone_directory";
    private $conn;
    private static $instance = null;

    public function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
            
            if ($this->conn->connect_error) {
                throw new Exception("Ошибка подключения к базе данных: " . $this->conn->connect_error);
            }
            
            // Устанавливаем кодировку
            $this->conn->set_charset("utf8mb4");
            
            // Логируем успешное подключение (в разработке)
            $this->log("Успешное подключение к базе данных: " . $this->dbname);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function getConnection() {
        // Проверяем соединение и переподключаем если нужно
        if (!$this->conn || !$this->conn->ping()) {
            $this->connect();
        }
        return $this->conn;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Ошибка подготовки запроса: " . $this->conn->error);
            }

            if (!empty($params)) {
                $types = '';
                $bind_params = [];
                
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } elseif (is_string($param)) {
                        $types .= 's';
                    } else {
                        $types .= 'b';
                    }
                    $bind_params[] = $param;
                }
                
                array_unshift($bind_params, $types);
                call_user_func_array([$stmt, 'bind_param'], $this->refValues($bind_params));
            }

            if (!$stmt->execute()) {
                throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $this->log("Выполнен запрос: " . substr($sql, 0, 100) . "...");
            
            return $result;

        } catch (Exception $e) {
            $this->handleError($e->getMessage());
            return false;
        }
    }

    public function getLastInsertId() {
        return $this->getConnection()->insert_id;
    }

    private function refValues($arr) {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }

    private function handleError($message) {
        // В режиме разработки показываем подробные ошибки
        if ($this->isDevelopment()) {
            $error_html = "
            <div style='
                background: #ffebee;
                border: 2px solid #f44336;
                border-radius: 10px;
                padding: 20px;
                margin: 20px;
                font-family: Arial, sans-serif;
                color: #c62828;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            '>
                <h3 style='color: #d32f2f; margin-top: 0;'>🚫 Ошибка базы данных</h3>
                <p><strong>Сообщение:</strong> {$message}</p>
                <p><strong>Время:</strong> " . date('Y-m-d H:i:s') . "</p>
                <p><strong>Файл:</strong> " . __FILE__ . "</p>
            </div>";
            die($error_html);
        } else {
            // В продакшене логируем ошибку и показываем общее сообщение
            $this->log("DATABASE ERROR: " . $message, 'ERROR');
            die("Произошла ошибка в системе. Пожалуйста, попробуйте позже.");
        }
    }

    private function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$level}: {$message}\n";
        
        // Логируем в файл (создайте папку logs если её нет)
        $log_file = __DIR__ . '/logs/database.log';
        if (!is_dir(dirname($log_file))) {
            mkdir(dirname($log_file), 0755, true);
        }
        
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }

    private function isDevelopment() {
        // Проверяем, находимся ли мы в режиме разработки
        return $_SERVER['SERVER_NAME'] == 'localhost' || 
               $_SERVER['SERVER_NAME'] == '127.0.0.1' ||
               strpos($_SERVER['SERVER_NAME'], '.local') !== false;
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
            $this->log("Соединение с базой данных закрыто");
        }
    }
}

// Создаем глобальный экземпляр базы данных
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Проверяем существование необходимых таблиц
    $check_tables = $conn->query("SHOW TABLES LIKE 'categories'");
    if ($check_tables->num_rows == 0) {
        // Здесь можно добавить автоматическое создание таблиц
        $db->log("Внимание: таблицы базы данных не найдены", 'WARNING');
    }
    
} catch (Exception $e) {
    die("Критическая ошибка инициализации базы данных: " . $e->getMessage());
}

// Упрощенная функция для быстрых запросов (обратная совместимость)
function db_query($sql, $params = []) {
    global $db;
    return $db->query($sql, $params);
}

?>