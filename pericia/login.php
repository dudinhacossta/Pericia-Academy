<?php
session_start();
require_once 'config.php'; 

$message = '';

if (isset($_SESSION['client_id'])) { 
    // Se já está logado, redireciona para o painel apropriado
    if ($_SESSION['client_type'] === 'professor') {
        header('Location: professor.php');
    } else {
        header('Location: cursos.php');
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_form = trim($_POST['email']);
    $palavra_passe_form = trim($_POST['password']);
    $tipo_usuario_form = trim($_POST['tipo_usuario'] ?? 'aluno'); // Pega o tipo de utilizador do formulário

    if (empty($email_form) || empty($palavra_passe_form) || !in_array($tipo_usuario_form, ['aluno', 'professor'])) {
        $message = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Erro!</span> Preencha todos os campos e selecione o tipo de login.</div>";
    } else {
        try {
            // Adiciona a verificação do tipo_usuario na consulta SQL
            $stmt = $pdo->prepare("SELECT id, nome_utilizador, email, senha, tipo_usuario FROM clientes WHERE email = :email AND tipo_usuario = :tipo_usuario");
            $stmt->execute([':email' => $email_form, ':tipo_usuario' => $tipo_usuario_form]);
            $cliente = $stmt->fetch(); 

            if ($cliente && password_verify($palavra_passe_form, $cliente['senha'])) {
                session_regenerate_id(true); 

                $_SESSION['client_id'] = $cliente['id']; 
                $_SESSION['client_email'] = $cliente['email']; 
                $_SESSION['client_name'] = $cliente['nome_utilizador']; 
                $_SESSION['client_type'] = $cliente['tipo_usuario'];

                session_write_close(); 

                // Lógica de Redirecionamento Baseada no Tipo
                if ($cliente['tipo_usuario'] === 'professor') {
                    // Professores são levados para sua página de painel
                    header('Location: professor.php');
                } else {
                    // Alunos são levados para a página de cursos
                    header('Location: cursos.php');
                }
                exit;
            } else {
                $message = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Acesso negado!</span> Email, senha ou tipo de conta incorretos.</div>";
            }
        } catch (PDOException $e) {
            error_log("Erro no login: " . $e->getMessage());
            $message = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Erro!</span> Ocorreu um erro ao processar o seu pedido.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ForensicX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f9fafb 0%, #f5f3ff 100%);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .login-container {
            box-shadow: 0 10px 30px rgba(126, 34, 206, 0.15);
        }
        .account-type label {
            flex: 1;
            text-align: center;
            padding: 10px 15px;
            border: 2px solid #e9d5ff;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .account-type label:hover {
            background-color: #f3e8ff;
        }
        .account-type input:checked + label {
            background-color: #7e22ce;
            color: white;
            border-color: #7e22ce;
        }
        .account-type input { display: none; }
    </style>
</head>
<body class="bg-purple-light">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-4 py-8 md:py-16 flex items-center justify-center">
        <div class="max-w-md w-full">
            <div class="bg-white p-8 md:p-12 rounded-2xl login-container">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-purple-900">Acessar Conta</h1>
                    <p class="text-gray-600 mt-2">Seja bem-vindo(a) de volta!</p>
                </div>

                <?php if (!empty($message)) { echo $message; } ?>

                <form action="login.php" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Entrar como:</label>
                        <div class="flex items-center space-x-4 account-type">
                            <input type="radio" name="tipo_usuario" value="aluno" id="aluno" class="peer" checked>
                            <label for="aluno">
                                <i class="fas fa-user-graduate mr-2"></i> Aluno
                            </label>
                            <input type="radio" name="tipo_usuario" value="professor" id="professor" class="peer">
                            <label for="professor">
                                <i class="fas fa-chalkboard-teacher mr-2"></i> Professor
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                            Entrar
                        </button>
                    </div>
                </form>

                <p class="text-center text-sm text-gray-600 mt-6">
                    Ainda não tem uma conta? <a href="register.php" class="text-purple-700 font-bold hover:underline">Cadastre-se aqui</a>.
                </p>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
