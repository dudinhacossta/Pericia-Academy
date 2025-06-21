<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$cli = $_SESSION['client_id'];

// Busca itens do carrinho
$stmt = $pdo->prepare("
  SELECT ci.id_item_carrinho, ci.id_produto, ci.quantidade,
         c.titulo, c.preco
  FROM carrinho_itens ci
  JOIN cursos c ON ci.id_produto = c.id
  WHERE ci.id_cliente = :cli
");
$stmt->execute([':cli' => $cli]);
$itens = $stmt->fetchAll();

if (empty($itens)) {
    $_SESSION['mensagem_carrinho'] = "Carrinho vazio.";
    header('Location: carrinho.php');
    exit;
}

$total = array_reduce($itens, fn($s, $i) => $s + $i['preco'] * $i['quantidade'], 0);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pagamento via PIX - Simulado</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <main class="container mx-auto p-6 text-center">
    <h1 class="text-3xl font-bold mb-4">Pagamento via PIX</h1>
    <p class="text-lg mb-2">Simulação de pagamento</p>
    <p class="text-xl font-semibold mb-6">Total: R$ <?= number_format($total, 2, ',', '.') ?></p>

    <div class="bg-white shadow-md rounded p-6 inline-block">
      <p class="mb-4">🚀 Escaneie o código abaixo com seu app de banco</p>
      <img src="https://api.qrserver.com/v1/create-qr-code/?data=pagamento_simulado<?= rand(1000,9999) ?>&size=200x200" alt="QR Code" class="mx-auto mb-4">
      <form method="POST" action="confirmar_pagamento.php">
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
          Já paguei (simulado)
        </button>
      </form>
    </div>
  </main>
</body>
</html>
