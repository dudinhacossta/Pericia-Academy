<?php
// account.php
session_start();
require_once 'config.php'; // Precisamos para buscar dados atualizados do cliente

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$id_cliente_logado = $_SESSION['client_id'];
$cliente_info = null;
$mensagem_conta = '';

if(isset($_SESSION['mensagem_conta_atualizada'])){
    $mensagem_conta = "<div class='mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg' role='alert'>" . $_SESSION['mensagem_conta_atualizada'] . "</div>";
    unset($_SESSION['mensagem_conta_atualizada']);
}


// Buscar informações atualizadas do cliente no banco
try {
    $stmt = $pdo->prepare("SELECT nome_utilizador, email, telefone FROM clientes WHERE id = :id_cliente");
    $stmt->execute([':id_cliente' => $id_cliente_logado]);
    $cliente_info = $stmt->fetch();

    if ($cliente_info) {
        // Atualizar a sessão com os dados mais recentes (opcional, mas bom para consistência)
        $_SESSION['client_name'] = $cliente_info['nome_utilizador'];
        $_SESSION['client_email'] = $cliente_info['email'];
        // $_SESSION['client_phone'] = $cliente_info['telefone']; // Poderia adicionar à sessão também
    } else {
        // Cliente não encontrado no BD, algo estranho. Forçar logout.
        unset($_SESSION['client_id'], $_SESSION['client_name'], $_SESSION['client_email']);
        session_destroy();
        header('Location: login.php?mensagem=erro_conta');
        exit;
    }

} catch (PDOException $e) {
    error_log("Erro ao buscar dados da conta: " . $e->getMessage());
    $mensagem_conta = "<div class='mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg' role='alert'>Erro ao carregar os dados da sua conta.</div>";
 
}


$client_name_display = $cliente_info['nome_utilizador'] ?? ($_SESSION['client_name'] ?? 'Cliente');
$client_email_display = $cliente_info['email'] ?? ($_SESSION['client_email'] ?? 'Não informado');
$client_phone_display = $cliente_info['telefone'] ?? 'Não informado';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Meu Site de Baterias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-6 py-12 md:py-20">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-xl">
            <h1 class="text-3xl font-bold text-purple-600 mb-6">Minha Conta</h1>
            
            <?php if ($mensagem_conta): echo $mensagem_conta; endif; ?>

            <?php if ($cliente_info): ?>
            <div class="space-y-3 mb-6">
                <p><strong>Nome de Usuário:</strong> <?php echo htmlspecialchars($client_name_display); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($client_email_display); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($client_phone_display); ?></p>
            </div>
            <a href="editar_conta.php" class="inline-block w-full text-center bg-purple-500 hover:bg-purple-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 mb-4">
                Editar Informações da Conta
            </a>
            <?php else: ?>
                <p>Não foi possível carregar as informações da sua conta.</p>
            <?php endif; ?>
            
            <div class="mt-8 border-t pt-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ações Rápidas</h2>
                <div class="space-y-3">
                    <a href="meus_pedidos.php" class="block w-full text-center bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                        Ver Meus Pedidos
                    </a>
                    <a href="cursos.php" class="block w-full text-center bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                        Ver Produtos
                    </a>
                    <a href="logout.php" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>