<?php
session_start(); // Adicionado para gerenciar sessões
$total_itens_carrinho = 0;

// Verifica itens no carrinho
// if (isset($_SESSION['carrinho_cursos']) { 
//     $total_itens_carrinho = count($_SESSION['carrinho_cursos']);
// } else if (isset($_SESSION['carrinho'])) {
//     $total_itens_carrinho = count($_SESSION['carrinho']);
// }

$curso = $_GET['curso'] ?? 'criminalistica';

$titulos = [
    'criminalistica' => 'Criminalística',
    'papiloscopia' => 'Papiloscopia',
    'balistica' => 'Balística Forense'
];

$descricoes = [
    'criminalistica' => 'Aprenda técnicas modernas de investigação criminal, coleta de evidências e análise forense para resolver casos complexos.',
    'papiloscopia' => 'Domine a arte da identificação humana através das impressões digitais com técnicas avançadas de análise papiloscópica.',
    'balistica' => 'Explore o mundo da balística forense, análise de projéteis e reconstrução de cenas de crime envolvendo armas de fogo.'
];

$videos = [
    'criminalistica' => [
        ['title' => 'Introdução à Criminalística', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '12:35'],
        ['title' => 'Investigação Criminal', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '18:20'],
        ['title' => 'Análise Forense', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '15:42'],
        ['title' => 'Coleta de Evidências', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '22:10'],
        ['title' => 'Reconstrução de Cena', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '19:55']
    ],
    'papiloscopia' => [
        ['title' => 'O que é Papiloscopia?', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '14:30'],
        ['title' => 'Identificação Digital', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '16:45'],
        ['title' => 'Técnicas de Levantamento', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '20:15'],
        ['title' => 'Casos Famosos', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '25:40'],
        ['title' => 'Análise Comparativa', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '18:20']
    ],
    'balistica' => [
        ['title' => 'Fundamentos de Balística', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '17:25'],
        ['title' => 'Tipos de Munição', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '19:10'],
        ['title' => 'Armas de Fogo', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '21:35'],
        ['title' => 'Reconstrução de Trajetórias', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '23:50'],
        ['title' => 'Balística Forense na Prática', 'url' => 'https://www.youtube.com/embed/guDJvZp5Bqk?list=RDguDJvZp5Bqk', 'duracao' => '26:15']
    ],
];

$certificados = [
    'criminalistica' => 'pdfs/Certificado1.png',
    'papiloscopia' => 'pdfs/Certificado2.png',
    'balistica' => 'pdfs/Certificado3.png'
];

$titulo = $titulos[$curso] ?? 'Curso Desconhecido';
$descricao = $descricoes[$curso] ?? '';
$playlist = $videos[$curso] ?? [];
$certificado = $certificados[$curso] ?? '';

$videoAtualUrl = $playlist[0]['url'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> | Academia Forense</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #6a0dad;
            --secondary: #9b59b6;
            --accent: #8e44ad;
            --light: #f5f3ff;
            --dark: #4b0082;
            --success: #27ae60;
            --gray: #95a5a6;
            --light-gray: #f8f9fa;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #333;
            line-height: 1.6;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #6a0dad 0%, #4b0082 100%);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        header {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Course Header */
        .course-header {
            background: linear-gradient(rgba(106, 13, 173, 0.9), rgba(75, 0, 130, 0.9)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 20px;
            text-align: center;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .course-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--accent);
        }

        .course-title {
            font-size: 2.8rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .course-description {
            max-width: 800px;
            margin: 0 auto 25px;
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .course-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.95rem;
        }

        /* Course Content */
        .course-container {
            display: flex;
            gap: 30px;
            margin: 40px 0;
        }

        @media (max-width: 992px) {
            .course-container {
                flex-direction: column;
            }
        }

        .video-section {
            flex: 3;
        }

        .sidebar {
            flex: 1;
        }

        /* Video Player */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: var(--shadow);
            background: #000;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-info {
            background: white;
            padding: 20px;
            border-radius: 0 0 10px 10px;
            margin-top: -5px;
        }

        .video-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .video-meta {
            display: flex;
            justify-content: space-between;
            color: var(--gray);
            font-size: 0.9rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .video-description {
            color: #555;
            line-height: 1.7;
        }

        /* Playlist */
        .playlist-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .playlist-header {
            background: var(--primary);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .progress-container {
            background: rgba(255,255,255,0.2);
            height: 6px;
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--accent);
            width: 0%;
            transition: width 0.5s ease;
        }

        .playlist-items {
            max-height: 500px;
            overflow-y: auto;
        }

        .playlist-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .playlist-item:hover {
            background: #f9f9f9;
        }

        .playlist-item.active {
            background: #f0ebff;
            border-left: 4px solid var(--secondary);
        }

        .playlist-item.active .item-title {
            color: var(--secondary);
        }

        .item-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f5f3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--secondary);
            flex-shrink: 0;
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .item-duration {
            color: var(--gray);
            font-size: 0.85rem;
        }

        .item-status {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ddd;
            flex-shrink: 0;
            align-self: center;
            margin-left: 10px;
        }

        .playlist-item.completed .item-status {
            background: var(--success);
            border-color: var(--success);
            position: relative;
        }

        .playlist-item.completed .item-status::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
        }

        /* Certificate */
        .certificate-section {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 25px;
            text-align: center;
        }

        .certificate-icon {
            font-size: 3.5rem;
            color: var(--accent);
            margin-bottom: 15px;
        }

        .certificate-title {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .certificate-desc {
            color: #666;
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-certificate {
            background: linear-gradient(to right, var(--secondary), #7d3c98);
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-certificate:hover::before {
            left: 100%;
        }

        .btn-certificate:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
        }

        /* Course Tabs */
        .course-tabs {
            display: flex;
            gap: 10px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .course-tab {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            border: 2px solid transparent;
        }

        .course-tab:hover {
            background: rgba(255,255,255,0.2);
        }

        .course-tab.active {
            background: var(--accent);
            border-color: white;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated {
            animation: fadeIn 0.6s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        /* Responsive */
        @media (max-width: 768px) {
            .course-title {
                font-size: 2rem;
            }
            
            .course-meta {
                gap: 15px;
            }
            
            .meta-item {
                font-size: 0.85rem;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-purple-600 text-white shadow-md sticky top-0 z-50">
        <nav class="container mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between">
                <a href="index.php" class="text-2xl font-bold hover:text-purple-200">Perícia Academy</a>
                
                <!-- Desktop Navigation -->
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
                    <a href="index.php#contato" class="hover:text-purple-200 px-3 py-2 rounded-md text-sm font-medium">Contato</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button id="nav-toggle" class="text-white p-2 rounded-md hover:text-purple-200 hover:bg-purple-700 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </nav>
        
        <!-- Mobile Menu -->
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
        <!-- Mobile Menu Overlay -->
        <div id="nav-overlay" class="hidden fixed inset-0 bg-black opacity-50 z-40 lg:hidden"></div>
    </header>
    
    <div class="container">
        <!-- Course Tabs -->
        <div class="course-tabs">
            <div class="course-tab <?= $curso === 'criminalistica' ? 'active' : '' ?>" onclick="location.href='?curso=criminalistica'">
                Criminalística
            </div>
            <div class="course-tab <?= $curso === 'papiloscopia' ? 'active' : '' ?>" onclick="location.href='?curso=papiloscopia'">
                Papiloscopia
            </div>
            <div class="course-tab <?= $curso === 'balistica' ? 'active' : '' ?>" onclick="location.href='?curso=balistica'">
                Balística
            </div>
        </div>
        
        <!-- Course Header -->
        <div class="course-header animated">
            <h1 class="course-title"><?= htmlspecialchars($titulo) ?></h1>
            <p class="course-description"><?= htmlspecialchars($descricao) ?></p>
            
            <div class="course-meta">
                <div class="meta-item">
                    <i class="fas fa-play-circle"></i>
                    <span><?= count($playlist) ?> Módulos</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>4 horas de conteúdo</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Nível <?= $curso === 'balistica' ? 'Avançado' : 'Intermediário' ?></span>
                </div>
            </div>
        </div>
        
        <!-- Course Content -->
        <div class="course-container">
            <div class="video-section">
                <div class="video-container">
                    <iframe id="video-player" src="<?= htmlspecialchars($videoAtualUrl) ?>" allowfullscreen></iframe>
                </div>
                
                <div class="video-info animated delay-1">
                    <h2 class="video-title"><?= htmlspecialchars($playlist[0]['title'] ?? '') ?></h2>
                    <div class="video-meta">
                        <span><i class="fas fa-play-circle"></i> Aula 1 de <?= count($playlist) ?></span>
                        <span><i class="fas fa-clock"></i> <?= $playlist[0]['duracao'] ?? '' ?></span>
                    </div>
                    <p class="video-description">
                        Esta aula introdutória aborda os conceitos fundamentais e as técnicas essenciais para compreensão do tema. Você aprenderá as bases teóricas e práticas necessárias para avançar nos módulos seguintes.
                    </p>
                </div>
            </div>
            
            <div class="sidebar">
                <!-- Playlist -->
                <div class="playlist-card animated delay-2">
                    <div class="playlist-header">
                        <h3>Conteúdo do Curso</h3>
                        <span id="progress-text">0/<?= count($playlist) ?> concluídos</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" id="progress-bar"></div>
                    </div>
                    <div class="playlist-items">
                        <?php foreach ($playlist as $index => $videoItem): ?>
                            <div class="playlist-item <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>" onclick="changeVideo('<?= htmlspecialchars($videoItem['url']) ?>', this, '<?= htmlspecialchars($videoItem['title']) ?>', '<?= $videoItem['duracao'] ?>')">
                                <div class="item-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="item-info">
                                    <div class="item-title"><?= htmlspecialchars($videoItem['title']) ?></div>
                                    <div class="item-duration"><?= $videoItem['duracao'] ?></div>
                                </div>
                                <div class="item-status"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Certificate -->
                <div class="certificate-section animated delay-3">
                    <div class="certificate-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="certificate-title">Certificado de Conclusão</h3>
                    <p class="certificate-desc">
                        Após assistir todas as aulas, você poderá baixar seu certificado reconhecido internacionalmente.
                    </p>
                    <button class="btn-certificate" id="certificate-btn" onclick="completeCourse()">
                        <i class="fas fa-download"></i> Liberar Certificado
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-fingerprint text-2xl text-purple-300"></i>
                        <h3 class="text-xl font-bold tracking-tight">Forensic<span class="text-purple-300">X</span></h3>
                    </div>
                    <p class="opacity-80">Excelência em perícia criminal com tecnologia de ponta e equipe altamente qualificada.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="opacity-80 hover:text-purple-300 transition duration-300">Início</a></li>
                        <li><a href="servicos.php" class="opacity-80 hover:text-purple-300 transition duration-300">Serviços</a></li>
                        <li><a href="#team" class="opacity-80 hover:text-purple-300 transition duration-300">Equipe</a></li>
                        <li><a href="#contact" class="opacity-80 hover:text-purple-300 transition duration-300">Contato</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Serviços</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="opacity-80 hover:text-purple-300 transition duration-300">Análise Forense Digital</a></li>
                        <li><a href="#" class="opacity-80 hover:text-purple-300 transition duration-300">Identificação Biométrica</a></li>
                        <li><a href="#" class="opacity-80 hover:text-purple-300 transition duration-300">Análise Química</a></li>
                        <li><a href="#" class="opacity-80 hover:text-purple-300 transition duration-300">Perícia de Local de Crime</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Redes Sociais</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-purple-700 flex items-center justify-center hover:bg-purple-600 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-purple-700 flex items-center justify-center hover:bg-purple-600 transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-purple-700 flex items-center justify-center hover:bg-purple-600 transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-purple-700 flex items-center justify-center hover:bg-purple-600 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <div class="mt-6">
                        <h5 class="font-medium mb-2">Assine nossa newsletter</h5>
                        <form action="index.php#contact" method="POST">
                            <div class="flex">
                                <input type="email" name="newsletter_email" placeholder="Seu email" class="px-4 py-2 rounded-l-lg text-gray-800 bg-purple-100 border border-purple-300 border-opacity-30 focus:outline-none w-full">
                                <button type="submit" class="bg-purple-600 hover:bg-purple-500 px-4 rounded-r-lg transition duration-300">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-purple-400 border-opacity-30 mt-12 pt-8 text-center opacity-80">
                <p>&copy; 2025 ForensicX. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Track watched videos
        let watchedVideos = [0];
        const totalVideos = <?= count($playlist) ?>;
        
        function changeVideo(url, element, title, duration) {
            // Update video player
            document.getElementById('video-player').src = url;
            
            // Update video info
            document.querySelector('.video-title').textContent = title;
            document.querySelector('.video-meta').innerHTML = `
                <span><i class="fas fa-play-circle"></i> Aula ${parseInt(element.dataset.index) + 1} de ${totalVideos}</span>
                <span><i class="fas fa-clock"></i> ${duration}</span>
            `;
            
            // Update active class in playlist
            document.querySelectorAll('.playlist-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
            
            // Mark as watched
            if (!watchedVideos.includes(parseInt(element.dataset.index))) {
                watchedVideos.push(parseInt(element.dataset.index));
                element.classList.add('completed');
                updateProgress();
            }
        }
        
        function updateProgress() {
            const progress = (watchedVideos.length / totalVideos) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = `${watchedVideos.length}/${totalVideos} concluídos`;
            
            // Enable certificate button if all videos are watched
            if (watchedVideos.length === totalVideos) {
                document.getElementById('certificate-btn').disabled = false;
                document.getElementById('certificate-btn').innerHTML = '<i class="fas fa-download"></i> Baixar Certificado';
            }
        }
        
        function completeCourse() {
            if (watchedVideos.length === totalVideos) {
                // Show download animation
                const btn = document.getElementById('certificate-btn');
                btn.innerHTML = '<i class="fas fa-check"></i> Certificado Gerado!';
                btn.style.background = 'linear-gradient(to right, #27ae60, #2ecc71)';
                
                // Simulate download after 1.5 seconds
                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-download"></i> Certificado Baixado!';
                    
                    // Here would be the actual download code
                    // window.location.href = '<?= htmlspecialchars($certificado) ?>';
                }, 1500);
            } else {
                alert('Por favor, assista a todas as aulas antes de solicitar o certificado.');
            }
        }
        
        // Initialize progress
        document.addEventListener('DOMContentLoaded', () => {
            updateProgress();
            
            // Mark first video as watched
            document.querySelector('.playlist-item[data-index="0"]').classList.add('completed');
            
            // Mobile menu functionality
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
                    document.body.classList.add('overflow-hidden');
                }
            }

            function closeMobileMenu() {
                if (navContentMobile && navOverlay) {
                    navContentMobile.classList.add('-translate-x-full');
                    navContentMobile.classList.remove('translate-x-0');
                    setTimeout(() => {
                        navContentMobile.classList.add('hidden');
                    }, 300);
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
        });
    </script>
</body>
</html>