<?php
// session_test_get.php
// ESTA DEVE SER A PRIMEIRA LINHA ABSOLUTA DO FICHEIRO, SEM ESPAÇOS ANTES.
session_start();

echo "<h1>Página de Teste de Sessão (Verificar)</h1>";
echo "ID da Sessão Atual: " . session_id() . "<br>";
echo "<hr>";
echo "Conteúdo completo de \$_SESSION:<pre>";
print_r($_SESSION);
echo "</pre>";
echo "<hr>";

if (isset($_SESSION['minha_variavel_teste'])) {
    echo "<strong>SUCESSO!</strong> A variável 'minha_variavel_teste' foi encontrada: " . htmlspecialchars($_SESSION['minha_variavel_teste']) . "<br>";
} else {
    echo "<strong>FALHA!</strong> A variável 'minha_variavel_teste' NÃO foi encontrada na sessão.<br>";
}

if (isset($_SESSION['contador_teste'])) {
    echo "<strong>SUCESSO!</strong> A variável 'contador_teste' foi encontrada: " . htmlspecialchars($_SESSION['contador_teste']) . "<br>";
} else {
    echo "<strong>FALHA!</strong> A variável 'contador_teste' NÃO foi encontrada na sessão.<br>";
}
echo "<hr>";
echo "<a href='session_test_set.php'>Voltar para a página de definição</a>";
?>
