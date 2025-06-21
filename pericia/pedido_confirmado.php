<?php
session_start();
require_once 'config.php';

// Se o cliente não estiver logado, ou não houver ID de pedido, redireciona
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id_pedido'])) {
    header('Location: index.php'); // Ou para uma página de 'meus pedidos'
    exit;
}

$id_pedido_confirmado = filter_var($_GET['id_pedido'], FILTER_VALIDATE_INT);
$pedido = null;
$itens_pedido_confirmado = [];

if ($id_pedido_confirmado) {
    try {
        // Busca dados do pedido
        $stmt = $pdo->prepare(
            "SELECT p.*, c.nome_utilizador AS nome_cliente, c.email AS email_cliente 
             FROM pedidos p 
             JOIN clientes c ON p.id_cliente = c.id
             WHERE p.id_pedido = :id_pedido AND p.id_cliente = :id_cliente"
        ); // Garante que o pedido pertence ao cliente logado
        $stmt->execute([':id_pedido' => $id_pedido_confirmado, ':id_cliente' => $_SESSION['client_id']]);
        $pedido = $stmt->fetch();

        if ($pedido) {
            // Busca itens do pedido
            $stmt_itens = $pdo->prepare(
                "SELECT nome_produto_historico, quantidade, preco_unitario_historico 
                 FROM itens_pedido 
                 WHERE id_pedido = :id_pedido"
            );
            $stmt_itens->execute([':id_pedido' => $id_pedido_confirmado]);
            $itens_pedido_confirmado = $stmt_itens->fetchAll();
        } else {
            // Pedido não encontrado ou não pertence ao cliente - redirecionar ou mostrar erro
             $_SESSION['mensagem_geral'] = "Pedido não encontrado ou acesso não autorizado.";
             header('Location: index.php'); // Ou para meus_pedidos.php com mensagem de erro
             exit;
        }

    } catch (PDOException $e) {
        error_log("Erro ao buscar pedido confirmado: " . $e->getMessage());
        // Tratar erro, talvez redirecionar para uma página de erro
        $pedido = null; // Garante que não tenta exibir dados se houver erro
        // Poderia definir uma mensagem de erro para exibir na página
        $_SESSION['mensagem_geral'] = "Erro ao carregar detalhes do pedido.";
        // Não redirecionar daqui para não perder o contexto do erro, a menos que seja para uma página de erro genérica.
    }
} else {
    // ID de pedido inválido
     $_SESSION['mensagem_geral'] = "ID de pedido inválido.";
    header('Location: index.php'); // Ou para meus_pedidos.php com mensagem de erro
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Venda de Baterias</title>
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
            <?php 
            // Exibir mensagem geral se houver (ex: vinda de um redirecionamento com erro)
            if (isset($_SESSION['mensagem_geral'])) {
                echo "<div class='mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg' role='alert'>" . htmlspecialchars($_SESSION['mensagem_geral']) . "</div>";
                unset($_SESSION['mensagem_geral']);
            }
            ?>

            <?php if ($pedido): ?>
                <div class="text-center mb-8">
                    <svg class="mx-auto h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h1 class="mt-4 text-3xl font-bold text-green-600">Obrigado pelo seu pedido!</h1>
                    <p class="mt-2 text-gray-600">O seu pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?> foi recebido e está como "<?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $pedido['status_pedido']))); ?>".</p>
                </div>

                <div class="mb-6 border-t border-b py-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-3">Resumo do Pedido</h2>
                    <p><strong>Data do Pedido:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['data_pedido']))); ?></p>
                    <p><strong>Valor Total:</strong> R$ <?php echo htmlspecialchars(number_format($pedido['valor_total'], 2, ',', '.')); ?></p>
                </div>

                <?php if(!empty($itens_pedido_confirmado)): ?>
                <div class="mb-6">
                     <h3 class="text-lg font-semibold text-gray-700 mb-2">Itens Comprados:</h3>
                    <ul class="divide-y divide-gray-200">
                        <?php foreach ($itens_pedido_confirmado as $item): ?>
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($item['nome_produto_historico']); ?></p>
                                    <p class="text-sm text-gray-500">Quantidade: <?php echo htmlspecialchars($item['quantidade']); ?></p>
                                </div>
                                <p class="text-sm text-gray-700">R$ <?php echo htmlspecialchars(number_format($item['preco_unitario_historico'] * $item['quantidade'], 2, ',', '.')); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Endereço de Entrega:</h3>
                    <address class="not-italic text-gray-600">
                        <?php echo htmlspecialchars($pedido['nome_entrega']); ?><br>
                        <?php echo htmlspecialchars($pedido['endereco_entrega']); ?><br>
                        <?php echo htmlspecialchars($pedido['cidade_entrega']); ?>, <?php echo htmlspecialchars($pedido['estado_entrega']); ?><br>
                        CEP: <?php echo htmlspecialchars($pedido['cep_entrega']); ?>
                    </address>
                    <?php if(!empty($pedido['notas_pedido'])): ?>
                        <p class="mt-2 text-sm text-gray-500"><strong>Notas:</strong> <?php echo htmlspecialchars($pedido['notas_pedido']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-8">
                    <a href="products.php" class="text-purple-600 hover:text-purple-800 font-medium">Continuar a comprar</a>
                    <span class="mx-2 text-gray-400">|</span>
                    <a href="meus_pedidos.php" class="text-purple-600 hover:text-purple-800 font-medium">Ver Meus Pedidos</a>
                </div>

            <?php elseif (!$id_pedido_confirmado): ?>
                 <h1 class="text-2xl font-bold text-red-600 text-center">ID de Pedido Inválido</h1>
                 <p class="text-gray-600 text-center mt-4">Não foi possível carregar os detalhes do pedido.</p>
                 <div class="text-center mt-6">
                    <a href="index.php" class="text-purple-600 hover:text-purple-800 font-medium">Voltar à Página Inicial</a>
                </div>
            <?php else: // $pedido é null ou false, mas id_pedido_confirmado era válido (erro na busca, por exemplo) ?>
                <h1 class="text-2xl font-bold text-red-600 text-center">Erro ao Carregar Pedido</h1>
                <p class="text-gray-600 text-center mt-4">Não foi possível carregar os detalhes do seu pedido #<?php echo htmlspecialchars($id_pedido_confirmado); ?>. Por favor, tente novamente mais tarde ou contacte o suporte.</p>
                 <div class="text-center mt-6">
                    <a href="index.php" class="text-purple-600 hover:text-purple-800 font-medium">Voltar à Página Inicial</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
