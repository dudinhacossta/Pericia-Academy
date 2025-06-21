<?php
session_start();
require_once 'config.php';

// 1) Login obrigatório
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php?redirect=' . urlencode('cursos.php'));
    exit;
}

// 2) Feedback de ação no carrinho
$mensagem_carrinho = '';
if (isset($_SESSION['mensagem_carrinho'])) {
    $mensagem_carrinho = "
      <div class='mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg'>
        " . htmlspecialchars($_SESSION['mensagem_carrinho']) . "
      </div>";
    unset($_SESSION['mensagem_carrinho']);
}

// 3) Busca cursos ativos na tabela `cursos`
try {
    $stmt = $pdo->query("
      SELECT id, titulo, subtitulo, instrutor, preco, url_imagem_capa, nivel
      FROM cursos
      WHERE ativo = 1
      ORDER BY titulo ASC
    ");
    $cursos_db = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Erro ao buscar cursos: " . $e->getMessage());
    $mensagem_carrinho = "
      <div class='mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg'>
        Erro ao carregar os cursos. Tente novamente mais tarde.
      </div>";
    $cursos_db = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Nossos Cursos – Perícia Academy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 font-sans">
  <?php include 'header.php'; ?>

  <main class="container mx-auto p-6">
    <h1 class="text-4xl text-center mb-6">Nossos <span class="text-purple-700">Cursos</span></h1>
    <?= $mensagem_carrinho ?>

    <?php if (empty($cursos_db)): ?>
      <p class="text-center text-gray-600">Nenhum curso disponível.</p>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($cursos_db as $curso): ?>
          <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col hover:-translate-y-2 transition">
            <img src="<?= htmlspecialchars($curso['url_imagem_capa'] ?: 'https://placehold.co/400x250/4c1d95/FFFFFF?text=Curso') ?>"
                 alt="Capa <?= htmlspecialchars($curso['titulo']) ?>"
                 class="w-full h-48 object-cover">
            <div class="p-4 flex-grow flex flex-col">
              <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mb-2">
                <?= htmlspecialchars($curso['nivel']) ?>
              </span>
              <h2 class="text-xl font-bold mb-1"><?= htmlspecialchars($curso['titulo']) ?></h2>
              <p class="text-gray-600 flex-grow"><?= htmlspecialchars($curso['subtitulo']) ?></p>
              <div class="mt-4 flex justify-between items-center">
                <span class="text-2xl font-bold text-purple-700">
                  R$ <?= number_format($curso['preco'],2,',','.') ?>
                </span>
                <form action="carrinho.php" method="POST">
                  <input type="hidden" name="id_produto" value="<?= $curso['id'] ?>">
                  <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-shopping-cart"></i> Adicionar
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
