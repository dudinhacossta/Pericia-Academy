<?php
// session_test_set.php
// ESTA DEVE SER A PRIMEIRA LINHA ABSOLUTA DO FICHEIRO, SEM ESPAÇOS ANTES.
session_start();

$_SESSION['minha_variavel_teste'] = "Olá Mundo da Sessão! Hora: " . date('H:i:s');
$_SESSION['contador_teste'] = isset($_SESSION['contador_teste']) ? $_SESSION['contador_teste'] + 1 : 1;

echo "<h1>Página de Teste de Sessão (Definir)</h1>";
echo "ID da Sessão Atual: " . session_id() . "<br>";
echo "Variável 'minha_variavel_teste' definida como: " . $_SESSION['minha_variavel_teste'] . "<br>";
echo "Variável 'contador_teste' definida como: " . $_SESSION['contador_teste'] . "<br>";
echo "<hr>";
echo "Conteúdo completo de \$_SESSION:<pre>";
print_r($_SESSION);
echo "</pre>";
echo "<hr>";
echo "<a href='session_test_get.php'>Ir para a página de verificação</a>";
?>
