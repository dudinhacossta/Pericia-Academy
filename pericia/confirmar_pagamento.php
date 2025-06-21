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

try {
    $pdo->beginTransaction();

    $total = array_reduce($itens, fn($s, $i) => $s + $i['preco'] * $i['quantidade'], 0);

    $insP = $pdo->prepare("
      INSERT INTO pedidos (id_cliente, valor_total)
      VALUES (:cli, :tot)
    ");
    $insP->execute([':cli' => $cli, ':tot' => $total]);
    $id_ped = $pdo->lastInsertId();

    $insI = $pdo->prepare("
      INSERT INTO itens_pedido
        (id_pedido, id_produto, nome_produto_historico, quantidade, preco_unitario_historico)
      VALUES
        (:ped, :prod, :nome, :qtd, :preco)
    ");
    foreach ($itens as $i) {
        $insI->execute([
            ':ped' => $id_ped,
            ':prod' => $i['id_produto'],
            ':nome' => $i['titulo'],
            ':qtd' => $i['quantidade'],
            ':preco' => $i['preco']
        ]);
    }

    $pdo->prepare("DELETE FROM carrinho_itens WHERE id_cliente = :cli")
        ->execute([':cli' => $cli]);

    $pdo->commit();
    $_SESSION['mensagem_carrinho'] = "Pagamento recebido! Pedido #{$id_ped} registrado com sucesso.";
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
    $_SESSION['mensagem_carrinho'] = "Erro ao confirmar o pagamento.";
}

header('Location: carrinho.php');
exit;
