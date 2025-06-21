<?php
session_start();
require_once '../config.php'; 

$acao = $_GET['acao'] ?? 'listar'; 
$id_produto = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

$mensagem_crud = '';
if (isset($_SESSION['mensagem_crud'])) {
    $mensagem_crud = $_SESSION['mensagem_crud'];
    unset($_SESSION['mensagem_crud']);
}
$mensagem_erro_formulario = '';
if (isset($_SESSION['mensagem_erro_formulario'])) {
    $mensagem_erro_formulario = $_SESSION['mensagem_erro_formulario'];
    unset($_SESSION['mensagem_erro_formulario']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_formulario'])) {
    $acao_formulario = $_POST['acao_formulario'];
    $id_produto_form = isset($_POST['id_produto']) ? filter_var($_POST['id_produto'], FILTER_VALIDATE_INT) : null;

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = filter_var(str_replace(',', '.', trim($_POST['preco'] ?? '')), FILTER_VALIDATE_FLOAT);
    $quantidade_stock = filter_var(trim($_POST['quantidade_stock'] ?? ''), FILTER_VALIDATE_INT);
    $url_imagem = trim($_POST['url_imagem'] ?? '');

    // Validações básicas
    if (empty($nome) || $preco === false || $preco < 0 || $quantidade_stock === false || $quantidade_stock < 0) {
        $_SESSION['mensagem_erro_formulario'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro: Nome, preço válido (use ponto ou vírgula para decimais) e quantidade em stock são obrigatórios.</div>";
        $_SESSION['dados_formulario_erro'] = $_POST; 
        
        if ($acao_formulario === 'editar' && $id_produto_form) {
            header('Location: gerenciar_produtos.php?acao=editar_formulario&id=' . $id_produto_form);
        } else {
            header('Location: gerenciar_produtos.php?acao=adicionar_formulario');
        }
        exit;
    }

    try {
        if ($acao_formulario === 'adicionar') {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, quantidade_stock, url_imagem) VALUES (:nome, :descricao, :preco, :quantidade_stock, :url_imagem)");
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':quantidade_stock' => $quantidade_stock,
                ':url_imagem' => $url_imagem
            ]);
            $_SESSION['mensagem_crud'] = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4'>Produto adicionado com sucesso!</div>";
        } elseif ($acao_formulario === 'editar' && $id_produto_form) {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, quantidade_stock = :quantidade_stock, url_imagem = :url_imagem WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':quantidade_stock' => $quantidade_stock,
                ':url_imagem' => $url_imagem,
                ':id' => $id_produto_form
            ]);
            $_SESSION['mensagem_crud'] = "<div class='bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-md mb-4'>Produto atualizado com sucesso!</div>";
        }
    } catch (PDOException $e) {
        error_log("Erro ao salvar produto: " . $e->getMessage());
        $_SESSION['mensagem_crud'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao salvar produto no banco de dados.</div>";
        $_SESSION['dados_formulario_erro'] = $_POST;
        if ($acao_formulario === 'editar' && $id_produto_form) {
            header('Location: gerenciar_produtos.php?acao=editar_formulario&id=' . $id_produto_form);
        } else {
            header('Location: gerenciar_produtos.php?acao=adicionar_formulario');
        }
        exit;
    }
    header('Location: gerenciar_produtos.php?acao=listar');
    exit;
}

if ($acao === 'apagar' && $id_produto) {
    try {
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id_produto]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem_crud'] = "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md mb-4'>Produto apagado com sucesso!</div>";
        } else {
            $_SESSION['mensagem_crud'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Produto não encontrado ou já foi apagado.</div>";
        }
    } catch (PDOException $e) {
        error_log("Erro ao apagar produto: " . $e->getMessage());
        $_SESSION['mensagem_crud'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao apagar o produto.</div>";
    }
    header('Location: gerenciar_produtos.php?acao=listar');
    exit;
}

$dados_formulario = [
    'id' => null,
    'nome' => '',
    'descricao' => '',
    'preco' => '',
    'quantidade_stock' => 0,
    'url_imagem' => ''
];
$titulo_pagina_form = "Adicionar Novo Produto";
$acao_form_valor = "adicionar";

if ($acao === 'editar_formulario' && $id_produto) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id_produto]);
        $produto_existente = $stmt->fetch();

        if ($produto_existente) {
            $dados_formulario = $produto_existente;
            $titulo_pagina_form = "Editar Produto: " . htmlspecialchars($dados_formulario['nome']);
            $acao_form_valor = "editar";
        } else {
            $_SESSION['mensagem_crud'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Produto não encontrado para edição.</div>";
            header('Location: gerenciar_produtos.php?acao=listar');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar produto para edição: " . $e->getMessage());
        $_SESSION['mensagem_crud'] = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao carregar produto para edição.</div>";
        header('Location: gerenciar_produtos.php?acao=listar');
        exit;
    }
}

if (isset($_SESSION['dados_formulario_erro'])) {
    $dados_formulario = array_merge($dados_formulario, $_SESSION['dados_formulario_erro']);
    unset($_SESSION['dados_formulario_erro']);
}



$produtos = [];
if ($acao === 'listar') {
    try {
        $stmt = $pdo->query("SELECT id, nome, preco, quantidade_stock, url_imagem FROM produtos ORDER BY nome ASC");
        $produtos = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar produtos para admin: " . $e->getMessage());
        $mensagem_crud .= "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4'>Erro ao carregar produtos.</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'admin_header.php'; ?>

    <div class="container mx-auto mt-10 px-4">

        <?php if ($acao === 'listar'): ?>
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-700">Gerenciar Produtos</h1>
                <a href="gerenciar_produtos.php?acao=adicionar_formulario" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                    Adicionar Novo Produto
                </a>
            </div>

            <?php if ($mensagem_crud): echo $mensagem_crud; endif; ?>

            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Imagem</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produtos)): ?>
                            <tr><td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">Nenhum produto cadastrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($produtos as $produto_item): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <img src="../<?php echo htmlspecialchars(!empty($produto_item['url_imagem']) ? $produto_item['url_imagem'] : 'https://placehold.co/60x40/cccccc/333333?text=N/A'); ?>" 
                                         alt="Imagem de <?php echo htmlspecialchars($produto_item['nome']); ?>" 
                                         class="w-16 h-10 object-cover rounded-md"
                                         onerror="this.onerror=null;this.src='https://placehold.co/60x40/cccccc/333333?text=Erro';">
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($produto_item['nome']); ?></p></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">R$ <?php echo htmlspecialchars(number_format($produto_item['preco'], 2, ',', '.')); ?></p></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($produto_item['quantidade_stock']); ?></p></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <a href="gerenciar_produtos.php?acao=editar_formulario&id=<?php echo $produto_item['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                    <a href="gerenciar_produtos.php?acao=apagar&id=<?php echo $produto_item['id']; ?>" 
                                       class="text-red-600 hover:text-red-900" 
                                       onclick="return confirm('Tem certeza que deseja apagar este produto?');">Apagar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($acao === 'adicionar_formulario' || ($acao === 'editar_formulario' && $id_produto)): ?>
            <h1 class="text-2xl font-semibold text-gray-700 mb-6"><?php echo $titulo_pagina_form; ?></h1>
            <?php if ($mensagem_erro_formulario): echo $mensagem_erro_formulario; endif; ?>
            <?php if ($mensagem_crud && $acao_form_valor === 'editar'): echo $mensagem_crud; endif; // Mostrar mensagem de erro de BD ao tentar carregar para edição ?>


            <div class="bg-white p-8 rounded-lg shadow-md">
                <form action="gerenciar_produtos.php" method="POST">
                    <input type="hidden" name="acao_formulario" value="<?php echo $acao_form_valor; ?>">
                    <?php if ($dados_formulario['id']): ?>
                        <input type="hidden" name="id_produto" value="<?php echo $dados_formulario['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Produto</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($dados_formulario['nome']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"><?php echo htmlspecialchars($dados_formulario['descricao']); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (R$)</label>
                            <input type="text" id="preco" name="preco" value="<?php echo htmlspecialchars(str_replace('.', ',', $dados_formulario['preco'])); // Exibe com vírgula ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required placeholder="Ex: 29,90">
                        </div>
                        <div>
                            <label for="quantidade_stock" class="block text-sm font-medium text-gray-700 mb-1">Quantidade em Stock</label>
                            <input type="number" id="quantidade_stock" name="quantidade_stock" value="<?php echo htmlspecialchars($dados_formulario['quantidade_stock']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required placeholder="Ex: 10">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="url_imagem" class="block text-sm font-medium text-gray-700 mb-1">URL da Imagem</label>
                        <input type="text" id="url_imagem" name="url_imagem" value="<?php echo htmlspecialchars($dados_formulario['url_imagem']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" placeholder="Ex: imagens_produtos/nome_imagem.jpg">
                        <?php if ($acao_form_valor === 'editar' && !empty($dados_formulario['url_imagem'])): ?>
                            <img src="../<?php echo htmlspecialchars($dados_formulario['url_imagem']); ?>" alt="Pré-visualização" class="mt-2 h-20 w-auto rounded-md border" onerror="this.style.display='none'">
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="gerenciar_produtos.php?acao=listar" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50 transition duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                            Salvar Produto
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <p class="text-center text-red-500">Ação inválida ou não especificada.</p>
            <div class="text-center mt-4">
                <a href="gerenciar_produtos.php?acao=listar" class="text-purple-600 hover:underline">Voltar para a lista de produtos</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'admin_footer.php'; ?>
</body>
</html>
