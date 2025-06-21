<?php
session_start();
require_once 'config.php'; // Para conexão com o banco

$message = ''; 
$product_info_html = ''; 

if (!isset($_SESSION['client_id'])) {
    $redirect_param = 'payment.php';
    if (isset($_GET['product_id'])) {
        $redirect_param .= '?product_id=' . urlencode($_GET['product_id']) . '&product_name=' . urlencode($_GET['product_name']) . '&price=' . urlencode($_GET['price']);
    } else {
         $redirect_param = 'adicionar_carrinho.php?acao=ver';
    }
    header('Location: login.php?redirect=' . urlencode($redirect_param));
    exit;
}

if (empty($_SESSION['carrinho'])) {
    $_SESSION['mensagem_carrinho'] = "O seu carrinho está vazio. Adicione produtos antes de prosseguir para o pagamento.";
    header('Location: products.php');
    exit;
}

$valor_total_carrinho = 0;
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $valor_total_carrinho += $item['preco'] * $item['quantidade'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_name = htmlspecialchars(trim($_POST['card_name'] ?? ''));
    // ... outros campos de cartão ...

    $nome_entrega = htmlspecialchars(trim($_POST['nome_entrega'] ?? ''));
    $endereco_entrega = htmlspecialchars(trim($_POST['endereco_entrega'] ?? ''));
    $cidade_entrega = htmlspecialchars(trim($_POST['cidade_entrega'] ?? ''));
    $estado_entrega = htmlspecialchars(trim($_POST['estado_entrega'] ?? ''));
    $cep_entrega = htmlspecialchars(trim($_POST['cep_entrega'] ?? ''));
    $notas_pedido = htmlspecialchars(trim($_POST['notas_pedido'] ?? ''));

    if (empty($nome_entrega) || empty($endereco_entrega) || empty($cidade_entrega) || empty($estado_entrega) || empty($cep_entrega) || empty($card_name) ) {
        $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Todos os campos de nome, endereço e pagamento são obrigatórios.</div>";
    } else {
        $id_cliente = $_SESSION['client_id'];
        $carrinho_atual = $_SESSION['carrinho'];

        if (empty($carrinho_atual)) {
            $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>O seu carrinho está vazio.</div>";
        } else {
            $pdo->beginTransaction(); 
            try {
                // 1. Inserir na tabela 'pedidos'
                $sql_pedido = "INSERT INTO pedidos (id_cliente, valor_total, status_pedido, nome_entrega, endereco_entrega, cidade_entrega, estado_entrega, cep_entrega, notas_pedido) 
                               VALUES (:id_cliente, :valor_total, :status_pedido, :nome_entrega, :endereco_entrega, :cidade_entrega, :estado_entrega, :cep_entrega, :notas_pedido)";
                $stmt_pedido = $pdo->prepare($sql_pedido);
                $params_pedido = [
                    ':id_cliente' => $id_cliente,
                    ':valor_total' => $valor_total_carrinho,
                    ':status_pedido' => 'processando_pagamento', 
                    ':nome_entrega' => $nome_entrega,
                    ':endereco_entrega' => $endereco_entrega,
                    ':cidade_entrega' => $cidade_entrega,
                    ':estado_entrega' => $estado_entrega,
                    ':cep_entrega' => $cep_entrega,
                    ':notas_pedido' => $notas_pedido
                ];
                
                try {
                    $stmt_pedido->execute($params_pedido);
                } catch (PDOException $e) {
                    throw new PDOException("Erro ao inserir em PEDIDOS: " . $e->getMessage() . " | SQL: " . $sql_pedido . " | Params: " . json_encode($params_pedido), (int)$e->getCode(), $e);
                }
                $id_novo_pedido = $pdo->lastInsertId();

                // 2. Inserir na tabela 'itens_pedido' e atualizar stock
                $sql_item_pedido = "INSERT INTO itens_pedido (id_pedido, id_produto, nome_produto_historico, quantidade, preco_unitario_historico)
                                    VALUES (:id_pedido, :id_produto, :nome_produto, :quantidade, :preco_unitario)";
                $stmt_item_pedido = $pdo->prepare($sql_item_pedido);
                
                $sql_atualizar_stock = "UPDATE produtos SET quantidade_stock = quantidade_stock - ? WHERE id = ? AND quantidade_stock >= ?";
                $stmt_atualizar_stock = $pdo->prepare($sql_atualizar_stock);

                foreach ($carrinho_atual as $id_produto_carrinho => $item) {
                    $params_item_pedido = [
                        ':id_pedido' => $id_novo_pedido,
                        ':id_produto' => $id_produto_carrinho,
                        ':nome_produto' => $item['nome'],
                        ':quantidade' => $item['quantidade'],
                        ':preco_unitario' => $item['preco']
                    ];
                    try {
                        $stmt_item_pedido->execute($params_item_pedido);
                    } catch (PDOException $e) {
                        throw new PDOException("Erro ao inserir em ITENS_PEDIDO para produto ID {$id_produto_carrinho}: " . $e->getMessage() . " | SQL: " . $sql_item_pedido . " | Params: " . json_encode($params_item_pedido), (int)$e->getCode(), $e);
                    }

                    // Usar array indexado para placeholders posicionais
                    $params_atualizar_stock_array = [
                        $item['quantidade'], // Para o primeiro ? (quantidade_comprada em SET)
                        $id_produto_carrinho, // Para o segundo ? (id_produto em WHERE)
                        $item['quantidade']  // Para o terceiro ? (quantidade_comprada em WHERE)
                    ];
                    try {
                        $stmt_atualizar_stock->execute($params_atualizar_stock_array);
                        if ($stmt_atualizar_stock->rowCount() == 0) {
                            // Esta exceção será lançada se o stock for insuficiente no momento da atualização
                            // ou se o produto não for encontrado.
                            throw new PDOException("Falha ao atualizar stock para o produto ID: " . $id_produto_carrinho . ". Stock pode ter sido insuficiente ou produto não encontrado com stock suficiente.");
                        }
                    } catch (PDOException $e) {
                         throw new PDOException("Erro ao atualizar STOCK para produto ID {$id_produto_carrinho}: " . $e->getMessage() . " | SQL: " . $sql_atualizar_stock . " | Params: " . json_encode($params_atualizar_stock_array), (int)$e->getCode(), $e);
                    }
                }

                $pdo->commit(); 

                unset($_SESSION['carrinho']);
                $_SESSION['mensagem_carrinho'] = ''; 

                header('Location: pedido_confirmado.php?id_pedido=' . $id_novo_pedido);
                exit;

            } catch (PDOException $e) {
                $pdo->rollBack(); 
                error_log("Erro ao criar pedido (Transação Principal): " . $e->getMessage());
                $message = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao processar o seu pedido: " . htmlspecialchars($e->getMessage()) . ". Por favor, tente novamente.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - Venda de Baterias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-6 py-12 md:py-20">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-xl">
            <h1 class="text-3xl font-bold text-center text-purple-600 mb-8">Finalizar Compra</h1>
            
            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <h2 class="text-xl font-semibold text-purple-700 mb-2">Resumo do Pedido</h2>
                <?php if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])): ?>
                    <ul class="list-disc list-inside mb-2">
                    <?php foreach($_SESSION['carrinho'] as $item_resumo): ?>
                        <li><?php echo htmlspecialchars($item_resumo['nome']); ?> (<?php echo $item_resumo['quantidade']; ?>x R$ <?php echo number_format($item_resumo['preco'], 2, ',', '.'); ?>)</li>
                    <?php endforeach; ?>
                    </ul>
                    <p class="text-lg font-bold">Total a Pagar: R$ <?php echo number_format($valor_total_carrinho, 2, ',', '.'); ?></p>
                <?php else: ?>
                    <p>O seu carrinho está vazio.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($message): echo $message; endif; ?>

            <form action="payment.php" method="POST">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">1. Endereço de Entrega</h2>
                <div class="mb-4">
                    <label for="nome_entrega" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo (para entrega)</label>
                    <input type="text" id="nome_entrega" name="nome_entrega" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['nome_entrega']) ? htmlspecialchars($_POST['nome_entrega']) : ''; ?>" required>
                </div>
                 <div class="mb-4">
                    <label for="endereco_entrega" class="block text-sm font-medium text-gray-700 mb-1">Endereço (Rua, Número, Complemento)</label>
                    <input type="text" id="endereco_entrega" name="endereco_entrega" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['endereco_entrega']) ? htmlspecialchars($_POST['endereco_entrega']) : ''; ?>" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="cidade_entrega" class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                        <input type="text" id="cidade_entrega" name="cidade_entrega" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['cidade_entrega']) ? htmlspecialchars($_POST['cidade_entrega']) : ''; ?>" required>
                    </div>
                    <div>
                        <label for="estado_entrega" class="block text-sm font-medium text-gray-700 mb-1">Estado (UF)</label>
                        <input type="text" id="estado_entrega" name="estado_entrega" maxlength="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['estado_entrega']) ? htmlspecialchars($_POST['estado_entrega']) : ''; ?>" required placeholder="Ex: SP">
                    </div>
                     <div>
                        <label for="cep_entrega" class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                        <input type="text" id="cep_entrega" name="cep_entrega" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['cep_entrega']) ? htmlspecialchars($_POST['cep_entrega']) : ''; ?>" required placeholder="Ex: 00000-000">
                    </div>
                </div>
                 <div class="mb-6">
                    <label for="notas_pedido" class="block text-sm font-medium text-gray-700 mb-1">Notas Adicionais (opcional)</label>
                    <textarea id="notas_pedido" name="notas_pedido" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" placeholder="Ex: Entregar após as 18h, ponto de referência..."><?php echo isset($_POST['notas_pedido']) ? htmlspecialchars($_POST['notas_pedido']) : ''; ?></textarea>
                </div>


                <h2 class="text-xl font-semibold text-gray-700 mb-4 mt-8 border-b pb-2">2. Detalhes do Pagamento</h2>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-md" role="alert">
                  <p>Insira seus dados com atenção.</p>
                </div>
                <div class="mb-4">
                    <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Nome no Cartão</label>
                    <input type="text" id="card_name" name="card_name" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['card_name']) ? htmlspecialchars($_POST['card_name']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Número do Cartão</label>
                    <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>" required>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">Validade (MM/AA)</label>
                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/AA" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['card_expiry']) ? htmlspecialchars($_POST['card_expiry']) : ''; ?>" required>
                    </div>
                    <div>
                        <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                        <input type="text" id="card_cvc" name="card_cvc" placeholder="123" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" value="<?php echo isset($_POST['card_cvc']) ? htmlspecialchars($_POST['card_cvc']) : ''; ?>" required>
                    </div>
                </div>
                <div class="text-center mt-8">
                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                        Pagar e Finalizar Pedido
                    </button>
                </div>
            </form>
             <p class="text-center text-sm text-gray-600 mt-6">
                <a href="adicionar_carrinho.php?acao=ver" class="text-purple-600 hover:underline font-medium">&larr; Voltar para o Carrinho</a>
            </p>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
