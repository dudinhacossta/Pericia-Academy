<?php
session_start(); // Inicia a sessão para poder destruí-la

// Remove todas as variáveis de sessão
$_SESSION = array();

// Destrói a sessão.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redireciona para a página inicial ou de login
header('Location: index.php');
exit;
?>
