<?php
require_once 'config.php'; // Se ainda não tiver sido incluído

$total_itens_carrinho = 0;

if (isset($_SESSION['client_id'])) {
    $cli = $_SESSION['client_id'];

    // Conta o total de itens no carrinho (soma as quantidades)
    $stmt = $pdo->prepare("SELECT SUM(quantidade) FROM carrinho_itens WHERE id_cliente = :cli");
    $stmt->execute([':cli' => $cli]);
    $total_itens_carrinho = $stmt->fetchColumn() ?? 0;
}
?>

<header class="bg-purple-600 text-white shadow-md sticky top-0 z-50">
    <nav class="container mx-auto px-4 sm:px-6 py-3">
        <div class="flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold hover:text-purple-200">Perícia Academy</a>
            
            <!-- Navegação para ecrãs maiores (Desktop) -->
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                <a href="index.php#inicio" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Início</a>
                <a href="cursos.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Cursos</a>
                <?php if (isset($_SESSION['client_id'])): ?>
                    <a href="meus_pedidos.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Minhas Inscrições</a>
                    <a href="account.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Minha Conta</a>
                    <a href="carrinho.php?acao=ver" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium relative">
                        Carrinho 
                        <?php if ($total_itens_carrinho > 0): ?>
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"><?php echo $total_itens_carrinho; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="logout.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                <?php else: ?>
                     <a href="carrinho.php?acao=ver" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium relative">
                        Carrinho 
                        <?php if ($total_itens_carrinho > 0): ?>
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"><?php echo $total_itens_carrinho; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="login.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    <a href="register.php" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Cadastro</a>
                <?php endif; ?>
                <a href="index.php#contact" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Contato</a>
            </div>

            <!-- Botão Hambúrguer para ecrãs menores -->
            <div class="lg:hidden">
                <button id="nav-toggle" class="text-white p-2 rounded-md hover:text-purple-200 hover:bg-purple-700 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Menu Lateral (Mobile) -->
    <div id="nav-content-mobile" class="hidden lg:hidden fixed top-0 left-0 w-64 h-full bg-purple-700 shadow-lg z-50 transform -translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-5">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-white">Menu</span>
                <button id="nav-close-mobile" class="text-white p-2 rounded-md hover:bg-purple-800">
                     <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col space-y-2">
                <a href="index.php#inicio" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Início</a>
                <a href="cursos.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Cursos</a>
                <hr class="border-purple-500 my-2">
                <?php if (isset($_SESSION['client_id'])): ?>
                    <a href="meus_pedidos.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Minhas Inscrições</a>
                    <a href="account.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Minha Conta</a>
                    <a href="carrinho.php?acao=ver" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium relative">
                        Carrinho 
                        <?php if ($total_itens_carrinho > 0): ?>
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"><?php echo $total_itens_carrinho; ?></span>
                        <?php endif; ?>
                    </a>
                    <hr class="border-purple-500 my-2">
                    <a href="logout.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="carrinho.php?acao=ver" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium relative">
                        Carrinho 
                        <?php if ($total_itens_carrinho > 0): ?>
                             <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"><?php echo $total_itens_carrinho; ?></span>
                        <?php endif; ?>
                    </a>
                    <hr class="border-purple-500 my-2">
                    <a href="login.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="register.php" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Cadastro</a>
                <?php endif; ?>
                <hr class="border-purple-500 my-2">
                <a href="index.php#contato" class="block hover:bg-purple-800 text-white px-3 py-2 rounded-md text-base font-medium">Contato</a>
            </nav>
        </div>
    </div>
    <!-- Overlay para o menu lateral -->
    <div id="nav-overlay" class="hidden fixed inset-0 bg-black opacity-50 z-40 lg:hidden"></div>
</header>
<script>
    const navToggle = document.getElementById('nav-toggle');
    const navContentMobile = document.getElementById('nav-content-mobile');
    const navCloseMobile = document.getElementById('nav-close-mobile');
    const navOverlay = document.getElementById('nav-overlay');
    const mobileMenuLinks = navContentMobile.querySelectorAll('a');

    function openMobileMenu() {
        if (navContentMobile && navOverlay) {
            navContentMobile.classList.remove('hidden', '-translate-x-full');
            navContentMobile.classList.add('translate-x-0');
            navOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Impede scroll da página principal
        }
    }

    function closeMobileMenu() {
        if (navContentMobile && navOverlay) {
            navContentMobile.classList.add('-translate-x-full');
            navContentMobile.classList.remove('translate-x-0');
            // Adiciona um pequeno delay para a animação antes de esconder completamente
            setTimeout(() => {
                navContentMobile.classList.add('hidden');
            }, 300); // Deve corresponder à duração da transição
            navOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    if (navToggle) {
        navToggle.addEventListener('click', function(event) {
            event.stopPropagation();
            openMobileMenu();
        });
    }

    if (navCloseMobile) {
        navCloseMobile.addEventListener('click', closeMobileMenu);
    }
    
    if (navOverlay) {
        navOverlay.addEventListener('click', closeMobileMenu);
    }
    
    // Fechar o menu se um link dentro dele for clicado
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (link.getAttribute('href') && link.getAttribute('href') !== '#') {
                closeMobileMenu();
            }
        });
    });
</script>
