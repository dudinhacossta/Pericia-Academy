<?php
session_start();
require 'config.php'; 

$message = '';

if (isset($_SESSION['client_id'])) { 
    header('Location: cursos.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_utilizador_form = trim($_POST['username']); 
    $email_form = trim($_POST['email']);
    $telefone_form = trim($_POST['telefone'] ?? '');
    $tipo_usuario_form = trim($_POST['tipo_usuario'] ?? 'aluno');
    $palavra_passe_form = trim($_POST['password']);
    $confirmar_palavra_passe_form = trim($_POST['password_confirm']);

    if (empty($nome_utilizador_form) || empty($email_form) || empty($palavra_passe_form) || !in_array($tipo_usuario_form, ['aluno', 'professor'])) {
        $message = "<div class='bg-purple-50 border-l-4 border-purple-600 text-purple-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Campos obrigatórios!</span> Preencha todos os campos, incluindo o tipo de conta.</div>";
    } elseif ($palavra_passe_form !== $confirmar_palavra_passe_form) {
        $message = "<div class='bg-purple-50 border-l-4 border-purple-600 text-purple-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Senhas divergentes!</span> As senhas não coincidem.</div>";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = :email OR nome_utilizador = :nome_utilizador_db");
        $stmt->execute(['email' => $email_form, 'nome_utilizador_db' => $nome_utilizador_form]);
        if ($stmt->fetch()) {
            $message = "<div class='bg-purple-50 border-l-4 border-purple-600 text-purple-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Dados existentes!</span> Email ou nome de utilizador já cadastrado.</div>";
        } else {
            $senha_hashed = password_hash($palavra_passe_form, PASSWORD_DEFAULT); 

            try {
                $stmt_insert = $pdo->prepare("INSERT INTO clientes (nome_utilizador, email, tipo_usuario, telefone, senha) VALUES (:nome_utilizador_db, :email, :tipo_usuario, :telefone, :senha_db)");
                $stmt_insert->execute([
                    ':nome_utilizador_db' => $nome_utilizador_form,
                    ':email' => $email_form,
                    ':tipo_usuario' => $tipo_usuario_form,
                    ':telefone' => !empty($telefone_form) ? $telefone_form : null,
                    ':senha_db' => $senha_hashed
                ]);
                $message = "<div class='bg-green-50 border-l-4 border-green-600 text-green-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Sucesso!</span> Cadastro realizado! Pode agora fazer <a href='login.php' class='font-bold hover:underline text-purple-800'>login</a>.</div>";
                $_POST = []; 
            } catch (PDOException $e) {
                error_log("Erro ao registar cliente: " . $e->getMessage());
                // MENSAGEM DE ERRO DETALHADA PARA DEPURAÇÃO
                $detailed_error = htmlspecialchars($e->getMessage());
                $message = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-md'><span class='font-bold'>Erro!</span> Ocorreu um erro ao tentar registar. <br><small class='mt-2 block'><strong>Detalhe técnico:</strong> {$detailed_error}</small></div>";
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
    <title>Cadastro - ForensicX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-light': '#f5f3ff',
                        'purple-primary': '#7e22ce',
                        'purple-dark': '#4c1d95',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f9fafb 0%, #f5f3ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .form-container {
            box-shadow: 0 10px 30px rgba(126, 34, 206, 0.15);
            border-radius: 16px;
            overflow: hidden;
        }
        .account-type {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }
        .account-type label {
            flex: 1;
            text-align: center;
            padding: 12px 15px;
            border: 2px solid #e9d5ff;
            border-radius: 10px;
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
        .account-type input {
            display: none;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a78bfa;
        }
        .input-group {
            position: relative;
        }
        .input-group input {
            padding-left: 45px;
        }
        .btn-purple-gradient {
            background: linear-gradient(135deg, #8b5cf6 0%, #7e22ce 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(126, 34, 206, 0.3);
        }
        .btn-purple-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(126, 34, 206, 0.4);
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #a78bfa;
            margin: 20px 0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9d5ff;
        }
        .divider:not(:empty)::before {
            margin-right: 1em;
        }
        .divider:not(:empty)::after {
            margin-left: 1em;
        }
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            border: 2px solid #e9d5ff;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            background-color: #f5f3ff;
            border-color: #c4b5fd;
        }
    </style>
</head>
<body class="bg-purple-light">
    <?php include 'header.php'; ?>

    <main class="flex-grow container mx-auto px-4 py-8 md:py-16">
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row bg-white form-container">
            <!-- Left Illustration Section -->
            <div class="w-full md:w-2/5 bg-gradient-to-br from-purple-800 to-purple-600 p-10 hidden md:flex flex-col justify-center text-white">
                <div class="text-center">
                    <div class="bg-white/20 p-5 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-user-plus text-6xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-4">Junte-se à Comunidade ForensicX</h2>
                    <p class="opacity-90 mb-6">Aprenda com os melhores profissionais e expanda seus conhecimentos em investigação forense.</p>
                    <div class="flex justify-center space-x-3">
                        <div class="w-3 h-3 bg-white rounded-full"></div>
                        <div class="w-3 h-3 bg-white/30 rounded-full"></div>
                        <div class="w-3 h-3 bg-white/30 rounded-full"></div>
                    </div>
                </div>
            </div>
            
            <!-- Right Form Section -->
            <div class="w-full md:w-3/5 p-8 md:p-12">
                <div class="text-center mb-2">
                    <h1 class="text-3xl font-bold text-purple-900 mb-3">Crie sua Conta</h1>
                    <p class="text-gray-600">Preencha os campos abaixo para se registrar</p>
                </div>
                
                <?php echo $message; ?>
                
                <form action="register.php" method="POST" class="mt-6">
                    <!-- Account Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Conta</label>
                        <div class="account-type">
                            <div>
                                <input type="radio" name="tipo_usuario" value="aluno" id="aluno" class="peer" checked>
                                <label for="aluno" class="peer-checked:bg-purple-primary peer-checked:text-white">
                                    <i class="fas fa-user-graduate mr-2"></i> Aluno
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="tipo_usuario" value="professor" id="professor" class="peer">
                                <label for="professor" class="peer-checked:bg-purple-primary peer-checked:text-white">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i> Professor
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Username -->
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="username" name="username" placeholder="Nome de utilizador" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="Endereço de email" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                        
                        <!-- Phone -->
                        <div class="input-group">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="telefone" name="telefone" placeholder="Telefone (opcional)" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
                        </div>
                        
                        <!-- Password -->
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Senha" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="input-group md:col-span-2">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirmar senha" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="w-full btn-purple-gradient text-white font-bold py-3 px-4 rounded-lg">
                            Criar Conta <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

                <div class="divider text-sm">ou continue com</div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <a href="#" class="social-btn">
                        <i class="fab fa-google text-red-500"></i>
                        Google
                    </a>
                    <a href="#" class="social-btn">
                        <i class="fab fa-facebook text-blue-600"></i>
                        Facebook
                    </a>
                </div>

                <p class="text-center text-gray-600 text-sm">
                    Ao se registrar, você concorda com nossos <a href="#" class="text-purple-700 font-medium hover:underline">Termos de Serviço</a> e <a href="#" class="text-purple-700 font-medium hover:underline">Política de Privacidade</a>.
                </p>

                <p class="text-center text-gray-700 mt-6">
                    Já tem uma conta? <a href="login.php" class="text-purple-700 font-bold hover:underline">Faça login</a>
                </p>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
