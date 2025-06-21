<?php
session_start();
require_once 'config.php'; // Conexão com o banco

// Se o cliente não estiver logado, redireciona para a página de login
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php?redirect=' . urlencode('meus_pedidos.php'));
    exit;
}

$id_cliente_logado = $_SESSION['client_id'];
$mensagem_status = '';

// Lógica para CANCELAR um pedido
if (isset($_POST['acao']) && $_POST['acao'] === 'cancelar_pedido' && isset($_POST['id_pedido_cancelar'])) {
    $id_pedido_para_cancelar = filter_var($_POST['id_pedido_cancelar'], FILTER_VALIDATE_INT);

    if ($id_pedido_para_cancelar) {
        $pdo->beginTransaction();
        try {
            // Verificar o status atual do pedido e se pertence ao cliente
            $stmt_check = $pdo->prepare("SELECT status_pedido, id_cliente FROM pedidos WHERE id_pedido = :id_pedido");
            $stmt_check->execute([':id_pedido' => $id_pedido_para_cancelar]);
            $pedido_check = $stmt_check->fetch();

            if ($pedido_check && $pedido_check['id_cliente'] == $id_cliente_logado) {
                // Definir quais status permitem cancelamento pelo cliente
                $status_permitidos_cancelamento = ['pendente', 'processando_pagamento', 'pago', 'em_separacao'];

                if (in_array($pedido_check['status_pedido'], $status_permitidos_cancelamento)) {
                    
                    // 1. Reverter stock dos produtos (IMPORTANTE FAZER ANTES DE APAGAR ITENS OU ATUALIZAR PEDIDO)
                    $stmt_itens_para_reverter = $pdo->prepare("SELECT id_produto, quantidade FROM itens_pedido WHERE id_pedido = :id_pedido");
                    $stmt_itens_para_reverter->execute([':id_pedido' => $id_pedido_para_cancelar]);
                    $itens_do_pedido_a_cancelar = $stmt_itens_para_reverter->fetchAll();

                    foreach ($itens_do_pedido_a_cancelar as $item_cancelado) {
                        if ($item_cancelado['id_produto']) { 
                            $stmt_reverter_stock = $pdo->prepare("UPDATE produtos SET quantidade_stock = quantidade_stock + :quantidade WHERE id = :id_produto");
                            $stmt_reverter_stock->execute([
                                ':quantidade' => $item_cancelado['quantidade'],
                                ':id_produto' => $item_cancelado['id_produto']
                            ]);
                        }
                    }
                    
                    // 2. Apagar itens da tabela itens_pedido (NOVA ETAPA)
                    $stmt_delete_itens = $pdo->prepare("DELETE FROM itens_pedido WHERE id_pedido = :id_pedido");
                    $stmt_delete_itens->execute([':id_pedido' => $id_pedido_para_cancelar]);

                    // 3. Atualizar status do pedido para 'cancelado'
                    $stmt_cancel = $pdo->prepare("UPDATE pedidos SET status_pedido = 'cancelado' WHERE id_pedido = :id_pedido");
                    $stmt_cancel->execute([':id_pedido' => $id_pedido_para_cancelar]);
                    
                    $pdo->commit();
                    $mensagem_status = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4'>Pedido #" . htmlspecialchars($id_pedido_para_cancelar) . " cancelado com sucesso. Os itens foram removidos e o stock atualizado.</div>";
                } else {
                    $pdo->rollBack(); // Não precisa fazer rollback se nenhuma query de escrita foi executada
                    $mensagem_status = "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md mb-4'>Não é possível cancelar o pedido #" . htmlspecialchars($id_pedido_para_cancelar) . " pois ele já está no status '" . htmlspecialchars(ucfirst(str_replace('_', ' ', $pedido_check['status_pedido']))) . "'.</div>";
                }
            } else {
                $pdo->rollBack(); // Não precisa fazer rollback se nenhuma query de escrita foi executada
                $mensagem_status = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Pedido não encontrado ou não pertence a você.</div>";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Erro ao cancelar pedido: " . $e->getMessage());
            $mensagem_status = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao processar o cancelamento. Tente novamente. Detalhe: " . $e->getMessage() ."</div>";
        }
    } else {
        $mensagem_status = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>ID de pedido inválido para cancelamento.</div>";
    }
}


// Buscar pedidos do cliente logado
$pedidos_cliente = [];
try {
    $stmt = $pdo->prepare(
        "SELECT id_pedido, data_pedido, valor_total, status_pedido 
         FROM pedidos 
         WHERE id_cliente = :id_cliente 
         ORDER BY data_pedido DESC"
    );
    $stmt->execute([':id_cliente' => $id_cliente_logado]);
    $pedidos_cliente = $stmt->fetchAll();

    // Para cada pedido, buscar seus itens (se ainda existirem)
    if ($pedidos_cliente) {
        $stmt_itens = $pdo->prepare(
            "SELECT id_produto, nome_produto_historico, quantidade, preco_unitario_historico 
             FROM itens_pedido 
             WHERE id_pedido = :id_pedido"
        );
        foreach ($pedidos_cliente as $key => $pedido) {
            // Só busca itens se o pedido não estiver cancelado (pois já teriam sido apagados)
            // Ou você pode sempre buscar e a lista virá vazia para cancelados.
            // Para este exemplo, vamos sempre buscar. Se foi cancelado e itens apagados, a lista virá vazia.
            $stmt_itens->execute([':id_pedido' => $pedido['id_pedido']]);
            $pedidos_cliente[$key]['itens'] = $stmt_itens->fetchAll();
        }
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar pedidos do cliente: " . $e->getMessage());
    $mensagem_status = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao carregar seus pedidos. Tente novamente mais tarde.</div>";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - Venda de Baterias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-purple-600 mb-8">Meus Pedidos</h1>

        <?php if ($mensagem_status): echo $mensagem_status; endif; ?>

        <?php if (empty($pedidos_cliente)): ?>
            <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <p class="mt-4 text-xl text-gray-600">Você ainda não fez nenhum pedido.</p>
                <a href="products.php" class="mt-6 inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                    Começar a comprar
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach ($pedidos_cliente as $pedido): ?>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <div class="flex flex-wrap justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-purple-700">Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h2>
                                <p class="text-sm text-gray-500">Data: <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['data_pedido']))); ?></p>
                            </div>
                            <div class="mt-2 sm:mt-0">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    <?php 
                                        switch ($pedido['status_pedido']) {
                                            case 'pago': case 'entregue': echo 'bg-green-100 text-green-800'; break;
                                            case 'enviado': case 'em_separacao': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'cancelado': case 'reembolsado': echo 'bg-red-100 text-red-800'; break;
                                            case 'pendente': case 'processando_pagamento': echo 'bg-yellow-100 text-yellow-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pedido['status_pedido']))); ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if (!empty($pedido['itens'])): // Só mostra a seção de itens se houver itens ?>
                        <div class="mb-4">
                            <h3 class="text-md font-semibold text-gray-700 mb-2">Itens do Pedido:</h3>
                            <ul class="divide-y divide-gray-200 border rounded-md">
                                <?php foreach ($pedido['itens'] as $item): ?>
                                    <li class="px-4 py-3 flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($item['nome_produto_historico']); ?></p>
                                            <p class="text-xs text-gray-500">Qtd: <?php echo htmlspecialchars($item['quantidade']); ?> | Preço Unit.: R$ <?php echo htmlspecialchars(number_format($item['preco_unitario_historico'], 2, ',', '.')); ?></p>
                                        </div>
                                        <p class="text-sm text-gray-700 font-medium">R$ <?php echo htmlspecialchars(number_format($item['preco_unitario_historico'] * $item['quantidade'], 2, ',', '.')); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php elseif ($pedido['status_pedido'] === 'cancelado'): ?>
                            <p class="mb-4 text-sm text-gray-500 italic">Os itens deste pedido foram removidos pois o pedido foi cancelado.</p>
                        <?php endif; ?>


                        <div class="flex flex-wrap justify-between items-center border-t pt-4">
                            <p class="text-lg font-bold text-gray-800">Total do Pedido: R$ <?php echo htmlspecialchars(number_format($pedido['valor_total'], 2, ',', '.')); ?></p>
                            
                            <?php 
                            $status_permitidos_cancelamento_cliente = ['pendente', 'processando_pagamento']; 
                            if (in_array($pedido['status_pedido'], $status_permitidos_cancelamento_cliente)): 
                            ?>
                                <form action="meus_pedidos.php" method="POST" class="mt-2 sm:mt-0">
                                    <input type="hidden" name="acao" value="cancelar_pedido">
                                    <input type="hidden" name="id_pedido_cancelar" value="<?php echo $pedido['id_pedido']; ?>">
                                    <button type="submit" 
                                            onclick="return confirm('Tem certeza que deseja cancelar este pedido? Esta ação não pode ser desfeita.');"
                                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-300">
                                        Cancelar Pedido
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
         <div class="mt-8 text-center">
            <a href="products.php" class="text-purple-600 hover:text-purple-800 font-medium">&larr; Voltar aos Produtos</a>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
