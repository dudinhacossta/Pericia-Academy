<?php
// config.php - Configurações do Banco de Dados e Conexão

define('DB_HOST', 'localhost');     
define('DB_NAME', 'pericia_cursos_db'); 
define('DB_USER', 'root');            
define('DB_PASS', '');                
define('DB_CHARSET', 'utf8mb4');

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    die("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
}
?>
