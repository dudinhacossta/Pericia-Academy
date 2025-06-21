<?php
session_start();
require_once 'config.php';

// 1) Login obrigatório
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php?redirect=' . urlencode('cursos.php'));
    exit;
}
$cli  = $_SESSION['client_id'];
$acao = $_GET['acao'] ?? null;

// ─── Remover item (DELETE da tabela `carrinho_itens`)
if ($acao === 'remover' && isset($_GET['id'])) {
    $id_item = intval($_GET['id']);
    if ($id_item > 0) {
        $pdo->prepare("
          DELETE FROM carrinho_itens
           WHERE id_item_carrinho = :it
             AND id_cliente       = :cli
        ")->execute([':it'=>$id_item, ':cli'=>$cli]);
        $_SESSION['mensagem_carrinho'] = "Item removido.";
    }
    header('Location: carrinho.php');
    exit;
}

// ─── Adicionar curso (INSERT/UPDATE em `carrinho_itens`)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$acao) {
    $id_prod = intval($_POST['id_produto'] ?? 0);
    if ($id_prod <= 0) {
        $_SESSION['mensagem_carrinho'] = "Curso inválido.";
    } else {
        $pdo->prepare("
          INSERT INTO carrinho_itens (id_cliente, id_produto, quantidade)
          VALUES (:cli, :prod, 1)
          ON DUPLICATE KEY UPDATE
            quantidade = quantidade + 1
        ")->execute([':cli'=>$cli, ':prod'=>$id_prod]);
        $_SESSION['mensagem_carrinho'] = "Curso adicionado ao carrinho!";
    }
    header('Location: carrinho.php');
    exit;
}

// ─── Finalizar compra (tabelas `pedidos` + `itens_pedido`)
if ($acao === 'finalizar') {
    // 1) Seleciona itens no carrinho
    $it = $pdo->prepare("
      SELECT ci.id_item_carrinho, ci.id_produto, ci.quantidade,
             c.titulo, c.preco
      FROM carrinho_itens ci
      JOIN cursos c ON ci.id_produto = c.id
      WHERE ci.id_cliente = :cli
    ");
    $it->execute([':cli'=>$cli]);
    $itens = $it->fetchAll();

    if (empty($itens)) {
        $_SESSION['mensagem_carrinho'] = "Carrinho vazio.";
        header('Location: carrinho.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insere pedido
        $total = array_reduce($itens, fn($s,$i)=> $s + $i['preco'] * $i['quantidade'], 0);
        $insP = $pdo->prepare("
          INSERT INTO pedidos (id_cliente, valor_total)
          VALUES (:cli, :tot)
        ");
        $insP->execute([':cli'=>$cli, ':tot'=>$total]);
        $id_ped = $pdo->lastInsertId();

        // Insere itens_pedido
        $insI = $pdo->prepare("
          INSERT INTO itens_pedido
            (id_pedido, id_produto, nome_produto_historico, quantidade, preco_unitario_historico)
          VALUES
            (:ped, :prod, :nome, :qtd, :preco)
        ");
        foreach ($itens as $i) {
            $insI->execute([
              ':ped'   => $id_ped,
              ':prod'  => $i['id_produto'],
              ':nome'  => $i['titulo'],
              ':qtd'   => $i['quantidade'],
              ':preco' => $i['preco']
            ]);
        }

        // Limpa carrinho_itens
        $pdo->prepare("DELETE FROM carrinho_itens WHERE id_cliente = :cli")
            ->execute([':cli'=>$cli]);

        $pdo->commit();
        $_SESSION['mensagem_carrinho'] = "Compra finalizada! Pedido #{$id_ped}";
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log($e->getMessage());
        $_SESSION['mensagem_carrinho'] = "Erro ao finalizar a compra.";
    }

    header('Location: carrinho.php');
    exit;
}

// ─── Exibir carrinho (SELECT de `carrinho_itens` + JOIN `cursos`)
$stmt = $pdo->prepare("
  SELECT ci.id_item_carrinho, ci.quantidade,
         c.titulo, c.preco
  FROM carrinho_itens ci
  JOIN cursos c ON ci.id_produto = c.id
  WHERE ci.id_cliente = :cli
");
$stmt->execute([':cli'=>$cli]);
$itens = $stmt->fetchAll();

$total    = array_reduce($itens, fn($s,$i)=> $s + $i['preco'] * $i['quantidade'], 0);
$mensagem = $_SESSION['mensagem_carrinho'] ?? '';
unset($_SESSION['mensagem_carrinho']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Meu Carrinho – Perícia Academy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
  <?php include 'header.php'; ?>
  <main class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Meu Carrinho</h1>

    <?php if ($mensagem): ?>
      <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">
        <?= htmlspecialchars($mensagem) ?>
      </div>
    <?php endif; ?>

    <?php if (empty($itens)): ?>
      <p>Seu carrinho está vazio.</p>
      <a href="cursos.php" class="mt-4 inline-block bg-purple-600 text-white px-4 py-2 rounded">
        Ver Cursos
      </a>
    <?php else: ?>
      <table class="w-full mb-6">
        <thead>
          <tr class="border-b">
            <th class="py-2">Curso</th>
            <th class="py-2">Preço</th>
            <th class="py-2">Qtd</th>
            <th class="py-2">Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($itens as $i): ?>
          <tr class="border-b">
            <td class="py-2"><?= htmlspecialchars($i['titulo']) ?></td>
            <td class="py-2">R$ <?= number_format($i['preco'],2,',','.') ?></td>
            <td class="py-2"><?= $i['quantidade'] ?></td>
            <td class="py-2">R$ <?= number_format($i['preco']*$i['quantidade'],2,',','.') ?></td>
            <td class="py-2">
              <a href="carrinho.php?acao=remover&id=<?= $i['id_item_carrinho'] ?>"
                 class="text-red-600 hover:underline">Remover</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="text-right font-bold text-xl mb-4">
        Total: R$ <?= number_format($total,2,',','.') ?>
      </div>

      <div class="flex justify-end gap-4">
        <a href="cursos.php" class="bg-purple-600 text-white px-4 py-2 rounded">
          Adicionar Mais
        </a>
       <!-- botão de finalizar -->
<a href="pagamento.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
  Finalizar Compra
</a>

      </div>
    <?php endif; ?>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
