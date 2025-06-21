<?php
session_start();
require_once 'config.php'; // Conexão com o banco

// Se o cliente não estiver logado, redireciona para a página de login
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php?redirect=' . urlencode('editar_conta.php'));
    exit;
}

$id_cliente_logado = $_SESSION['client_id'];
$mensagem_feedback = '';
$tipo_mensagem = ''; // 'sucesso' ou 'erro'

// Buscar informações atuais do cliente para preencher o formulário
try {
    $stmt = $pdo->prepare("SELECT nome_utilizador, email, telefone, senha FROM clientes WHERE id = :id_cliente");
    $stmt->execute([':id_cliente' => $id_cliente_logado]);
    $cliente_atual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente_atual) {
        // Algo muito errado, cliente da sessão não existe no BD
        unset($_SESSION['client_id'], $_SESSION['client_name'], $_SESSION['client_email']);
        session_destroy();
        header('Location: login.php?mensagem=erro_inesperado_conta');
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar dados do cliente para edição: " . $e->getMessage());
    $mensagem_feedback = "Erro ao carregar seus dados. Tente novamente mais tarde.";
    $tipo_mensagem = 'erro';
    // Não exibir o formulário se não puder carregar os dados
}

// Processar o formulário de atualização
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($cliente_atual)) {
    $nome_utilizador_novo = trim($_POST['nome_utilizador']);
    $email_novo = trim($_POST['email']);
    $telefone_novo = trim($_POST['telefone'] ?? '');
    
    $senha_atual_form = trim($_POST['senha_atual'] ?? '');
    $nova_senha_form = trim($_POST['nova_senha'] ?? '');
    $confirmar_nova_senha_form = trim($_POST['confirmar_nova_senha'] ?? '');

    $erros = [];
    $campos_para_atualizar_sql = [];
    $params_para_atualizar = [':id_cliente' => $id_cliente_logado];

    // Validação básica
    if (empty($nome_utilizador_novo)) {
        $erros[] = "O nome de utilizador não pode ficar vazio.";
    }
    if (empty($email_novo)) {
        $erros[] = "O email não pode ficar vazio.";
    } elseif (!filter_var($email_novo, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Formato de email inválido.";
    }

    // Verificar se houve tentativa de mudar email ou senha, para exigir senha atual
    $precisa_senha_atual = false;
    if ($email_novo !== $cliente_atual['email'] || !empty($nova_senha_form)) {
        $precisa_senha_atual = true;
    }

    if ($precisa_senha_atual && empty($senha_atual_form)) {
        $erros[] = "Para alterar o email ou a senha, você precisa fornecer sua senha atual.";
    } elseif ($precisa_senha_atual && !password_verify($senha_atual_form, $cliente_atual['senha'])) {
        $erros[] = "A senha atual fornecida está incorreta.";
    }

    // Preparar atualizações se não houver erros de senha atual (se necessária)
    if (empty($erros) || !$precisa_senha_atual || ($precisa_senha_atual && password_verify($senha_atual_form, $cliente_atual['senha'])) ) {
        
        // Atualizar nome de utilizador se diferente
        if ($nome_utilizador_novo !== $cliente_atual['nome_utilizador']) {
            // Verificar se o novo nome de utilizador já existe (se for diferente do atual)
            $stmt_check_user = $pdo->prepare("SELECT id FROM clientes WHERE nome_utilizador = :nome AND id != :id_cliente");
            $stmt_check_user->execute([':nome' => $nome_utilizador_novo, ':id_cliente' => $id_cliente_logado]);
            if ($stmt_check_user->fetch()) {
                $erros[] = "Este nome de utilizador já está em uso por outra conta.";
            } else {
                $campos_para_atualizar_sql[] = "nome_utilizador = :nome_utilizador";
                $params_para_atualizar[':nome_utilizador'] = $nome_utilizador_novo;
            }
        }

        // Atualizar email se diferente
        if ($email_novo !== $cliente_atual['email']) {
            // Verificar se o novo email já existe (se for diferente do atual)
            $stmt_check_email = $pdo->prepare("SELECT id FROM clientes WHERE email = :email AND id != :id_cliente");
            $stmt_check_email->execute([':email' => $email_novo, ':id_cliente' => $id_cliente_logado]);
            if ($stmt_check_email->fetch()) {
                $erros[] = "Este email já está em uso por outra conta.";
            } else {
                $campos_para_atualizar_sql[] = "email = :email";
                $params_para_atualizar[':email'] = $email_novo;
            }
        }
        
        // Atualizar telefone se diferente
        $telefone_db_format = !empty($telefone_novo) ? $telefone_novo : null;
        if ($telefone_db_format !== $cliente_atual['telefone']) {
            $campos_para_atualizar_sql[] = "telefone = :telefone";
            $params_para_atualizar[':telefone'] = $telefone_db_format;
        }

        // Atualizar senha se nova senha foi fornecida e confirmada
        if (!empty($nova_senha_form)) {
            if ($nova_senha_form !== $confirmar_nova_senha_form) {
                $erros[] = "A nova senha e a confirmação da nova senha não coincidem.";
            } elseif (strlen($nova_senha_form) < 6) {
                $erros[] = "A nova senha deve ter pelo menos 6 caracteres.";
            } else {
                $campos_para_atualizar_sql[] = "senha = :nova_senha";
                $params_para_atualizar[':nova_senha'] = password_hash($nova_senha_form, PASSWORD_DEFAULT);
            }
        }
    }


    if (empty($erros) && !empty($campos_para_atualizar_sql)) {
        try {
            $sql_update = "UPDATE clientes SET " . implode(", ", $campos_para_atualizar_sql) . " WHERE id = :id_cliente";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute($params_para_atualizar);

            $_SESSION['mensagem_conta_atualizada'] = "Suas informações foram atualizadas com sucesso!";
            // Atualizar dados da sessão se nome ou email mudaram
            if (isset($params_para_atualizar[':nome_utilizador'])) {
                $_SESSION['client_name'] = $params_para_atualizar[':nome_utilizador'];
            }
            if (isset($params_para_atualizar[':email'])) {
                $_SESSION['client_email'] = $params_para_atualizar[':email'];
            }
            header('Location: account.php');
            exit;

        } catch (PDOException $e) {
            error_log("Erro ao atualizar conta: " . $e->getMessage());
            $mensagem_feedback = "Erro ao atualizar suas informações. Tente novamente.";
            $tipo_mensagem = 'erro';
        }
    } elseif (empty($erros) && empty($campos_para_atualizar_sql)) {
        $mensagem_feedback = "Nenhuma alteração detectada para salvar.";
        $tipo_mensagem = 'info'; // Usar 'info' ou 'aviso'
    }
    
    if (!empty($erros)) {
        $mensagem_feedback = "<ul>";
        foreach ($erros as $erro) {
            $mensagem_feedback .= "<li>" . htmlspecialchars($erro) . "</li>";
        }
        $mensagem_feedback .= "</ul>";
        $tipo_mensagem = 'erro';
        // Preencher os campos do formulário com os dados submetidos para correção
        $cliente_atual['nome_utilizador'] = $nome_utilizador_novo;
        $cliente_atual['email'] = $email_novo;
        $cliente_atual['telefone'] = $telefone_novo;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Conta - Meu Site de Baterias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-6 py-12 md:py-20">
        <div class="max-w-lg mx-auto">
            <h1 class="text-3xl font-bold text-center text-purple-600 mb-8">Editar Minhas Informações</h1>

            <?php if ($mensagem_feedback): ?>
                <div class="mb-6 p-4 text-sm rounded-lg
                    <?php if ($tipo_mensagem === 'sucesso'): echo 'text-green-700 bg-green-100'; 
                          elseif ($tipo_mensagem === 'erro'): echo 'text-red-700 bg-red-100'; 
                          else: echo 'text-blue-700 bg-blue-100'; endif; ?>" 
                     role="alert">
                    <?php echo $mensagem_feedback; // Se for lista de erros, já tem <ul><li> ?>
                </div>
            <?php endif; ?>

            <?php if ($cliente_atual && $tipo_mensagem !== 'erro_fatal_carregamento'): // Não mostra formulário se dados iniciais não puderam ser carregados ?>
            <form action="editar_conta.php" method="POST" class="bg-white p-8 rounded-lg shadow-xl space-y-6">
                <div>
                    <label for="nome_utilizador" class="block text-sm font-medium text-gray-700 mb-1">Nome de Utilizador</label>
                    <input type="text" id="nome_utilizador" name="nome_utilizador" value="<?php echo htmlspecialchars($cliente_atual['nome_utilizador']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente_atual['email']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                </div>
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">Telefone (Opcional)</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" value="<?php echo htmlspecialchars($cliente_atual['telefone'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                </div>

                <hr class="my-6 border-gray-300">
                <p class="text-sm text-gray-600">Para alterar seu email ou senha, por favor, insira sua senha atual. Deixe os campos de nova senha em branco se não desejar alterá-la.</p>
                
                <div>
                    <label for="senha_atual" class="block text-sm font-medium text-gray-700 mb-1">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" autocomplete="current-password">
                </div>

                <div>
                    <label for="nova_senha" class="block text-sm font-medium text-gray-700 mb-1">Nova Senha (mínimo 6 caracteres)</label>
                    <input type="password" id="nova_senha" name="nova_senha" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" autocomplete="new-password">
                </div>
                <div>
                    <label for="confirmar_nova_senha" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" autocomplete="new-password">
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="account.php" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                        Salvar Alterações
                    </button>
                </div>
            </form>
            <?php elseif($tipo_mensagem === 'erro_fatal_carregamento'): ?>
                <p class="text-center text-red-500">Não foi possível carregar os dados para edição. Tente novamente mais tarde ou contacte o suporte.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
