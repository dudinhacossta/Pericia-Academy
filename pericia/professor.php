<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cursos | Investigação Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f3ff;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #7e22ce 0%, #3b0764 100%);
        }
        
        .input-focus:focus {
            border-color: #9333ea;
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.3);
        }
        
        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        
        .floating-label {
            position: absolute;
            top: 0;
            left: 0;
            transform: translate(12px, 12px);
            transition: all 0.2s ease-out;
            pointer-events: none;
            color: #9ca3af;
            background-color: white;
            padding: 0 4px;
            border-radius: 4px;
        }
        
        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label {
            transform: translate(12px, -8px) scale(0.85);
            color: #9333ea;
            background-color: white;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-fingerprint text-3xl"></i>
                    <h1 class="text-2xl font-bold">Investigação<span class="text-purple-300">Digital</span></h1>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="hover:text-purple-200 transition">Dashboard</a>
                    <a href="#" class="hover:text-purple-200 transition">Cursos</a>
                    <a href="#" class="hover:text-purple-200 transition">Alunos</a>
                    <a href="#" class="hover:text-purple-200 transition">Configurações</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <button class="md:hidden text-xl">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="relative">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Perfil" class="w-10 h-10 rounded-full border-2 border-purple-300 cursor-pointer">
                        <span class="absolute top-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-xl font-semibold text-purple-900 mb-6">Painel do Professor</h2>
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg bg-purple-100 text-purple-800">
                            <i class="fas fa-plus-circle w-5 text-center"></i>
                            <span>Novo Curso</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-800 transition">
                            <i class="fas fa-book w-5 text-center"></i>
                            <span>Meus Cursos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-800 transition">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span>Alunos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-800 transition">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span>Estatísticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-purple-50 text-gray-700 hover:text-purple-800 transition">
                            <i class="fas fa-cog w-5 text-center"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                </ul>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-question-circle text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Precisa de ajuda?</h4>
                            <a href="#" class="text-sm text-purple-600 hover:underline">Central de Ajuda</a>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Form Section -->
            <section class="flex-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="gradient-bg px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">Cadastrar Novo Curso</h2>
                        <p class="text-purple-200 text-sm">Preencha os detalhes do curso abaixo</p>
                    </div>
                    
                    <form id="courseForm" class="p-6">
                        <!-- Course Basic Info -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> Informações Básicas
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Course Name -->
                                <div class="relative">
                                    <input type="text" id="courseName" class="floating-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder=" " required>
                                    <label for="courseName" class="floating-label">Nome do Curso*</label>
                                </div>
                                
                                <!-- Course Code -->
                                <div class="relative">
                                    <input type="text" id="courseCode" class="floating-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder=" " required>
                                    <label for="courseCode" class="floating-label">Código do Curso*</label>
                                </div>
                                
                                <!-- Category -->
                                <div class="relative">
                                    <select id="courseCategory" class="floating-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus appearance-none" required>
                                        <option value="" disabled selected></option>
                                        <option value="forense">Forense Digital</option>
                                        <option value="cybersecurity">Cybersecurity</option>
                                        <option value="hacking">Hacking Ético</option>
                                        <option value="analise">Análise de Dados</option>
                                        <option value="outro">Outra Categoria</option>
                                    </select>
                                    <label for="courseCategory" class="floating-label">Categoria*</label>
                                    <i class="fas fa-chevron-down absolute right-3 top-4 text-gray-400 pointer-events-none"></i>
                                </div>
                                
                                <!-- Difficulty Level -->
                                <div class="relative">
                                    <select id="courseLevel" class="floating-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus appearance-none" required>
                                        <option value="" disabled selected></option>
                                        <option value="beginner">Iniciante</option>
                                        <option value="intermediate">Intermediário</option>
                                        <option value="advanced">Avançado</option>
                                        <option value="expert">Especialista</option>
                                    </select>
                                    <label for="courseLevel" class="floating-label">Nível de Dificuldade*</label>
                                    <i class="fas fa-chevron-down absolute right-3 top-4 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="mt-6">
                                <label for="courseDescription" class="block text-sm font-medium text-gray-700 mb-2">Descrição do Curso*</label>
                                <textarea id="courseDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder="Descreva o conteúdo do curso, objetivos e público-alvo..." required></textarea>
                            </div>
                        </div>
                        
                        <!-- Course Media -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-images mr-2"></i> Mídia do Curso
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Thumbnail Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail do Curso</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                        <div class="space-y-1 text-center">
                                            <div class="flex justify-center text-gray-400">
                                                <i class="fas fa-image text-4xl"></i>
                                            </div>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="courseThumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                                    <span>Envie uma imagem</span>
                                                    <input id="courseThumbnail" name="courseThumbnail" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">ou arraste e solte</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF até 5MB</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Promo Video -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Vídeo Promocional (URL)</label>
                                    <div class="mt-1">
                                        <input type="url" id="promoVideo" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder="https://youtube.com/embed/...">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Cole o URL de incorporação do YouTube ou Vimeo</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Course Structure -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-sitemap mr-2"></i> Estrutura do Curso
                            </h3>
                            
                            <!-- Modules -->
                            <div id="modulesContainer">
                                <div class="module-item mb-6 p-4 border border-gray-200 rounded-lg">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-medium text-purple-800">Módulo 1</h4>
                                        <button type="button" class="text-red-500 hover:text-red-700" onclick="removeModule(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Título do Módulo*</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none input-focus" placeholder="Ex: Introdução à Forense Digital" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição do Módulo</label>
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none input-focus" rows="2" placeholder="Descreva o conteúdo deste módulo..."></textarea>
                                    </div>
                                    
                                    <!-- Lessons -->
                                    <div class="lessons-container ml-4 pl-4 border-l-2 border-purple-100">
                                        <div class="lesson-item mb-4 p-3 bg-gray-50 rounded">
                                            <div class="flex justify-between items-center mb-2">
                                                <h5 class="text-sm font-medium text-gray-800">Aula 1</h5>
                                                <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="removeLesson(this)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Título da Aula*</label>
                                                    <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="Ex: Conceitos Básicos" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de Conteúdo*</label>
                                                    <select class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" required>
                                                        <option value="">Selecione...</option>
                                                        <option value="video">Vídeo</option>
                                                        <option value="text">Texto</option>
                                                        <option value="quiz">Quiz</option>
                                                        <option value="file">Arquivo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-2">
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Conteúdo/URL*</label>
                                                <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="URL do vídeo ou texto do conteúdo" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="add-lesson-btn text-xs text-purple-600 hover:text-purple-800 flex items-center mt-2" onclick="addLesson(this)">
                                        <i class="fas fa-plus-circle mr-1"></i> Adicionar Aula
                                    </button>
                                </div>
                            </div>
                            
                            <button type="button" id="addModuleBtn" class="text-purple-600 hover:text-purple-800 flex items-center" onclick="addModule()">
                                <i class="fas fa-plus-circle mr-2"></i> Adicionar Módulo
                            </button>
                        </div>
                        
                        <!-- Pricing & Access -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-tag mr-2"></i> Preço e Acesso
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Price -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">R$</span>
                                    </div>
                                    <input type="number" id="coursePrice" class="floating-input w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder=" " min="0" step="0.01">
                                    <label for="coursePrice" class="floating-label">Preço do Curso</label>
                                </div>
                                
                                <!-- Discount -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">%</span>
                                    </div>
                                    <input type="number" id="courseDiscount" class="floating-input w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus" placeholder=" " min="0" max="100">
                                    <label for="courseDiscount" class="floating-label">Desconto Promocional</label>
                                </div>
                                
                                <!-- Duration -->
                                <div class="relative">
                                    <select id="courseDuration" class="floating-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus appearance-none">
                                        <option value="" disabled selected></option>
                                        <option value="1week">1 Semana</option>
                                        <option value="1month">1 Mês</option>
                                        <option value="3months">3 Meses</option>
                                        <option value="6months">6 Meses</option>
                                        <option value="lifetime">Acesso Vitalício</option>
                                    </select>
                                    <label for="courseDuration" class="floating-label">Duração do Acesso</label>
                                    <i class="fas fa-chevron-down absolute right-3 top-4 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded focus:ring-purple-500 border-gray-300">
                                    <span class="ml-2 text-gray-700">Disponibilizar curso gratuitamente</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Additional Settings -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-cog mr-2"></i> Configurações Adicionais
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Certificate -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificado</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus">
                                        <option value="none">Não emitir certificado</option>
                                        <option value="basic">Certificado Básico</option>
                                        <option value="premium">Certificado Premium</option>
                                    </select>
                                </div>
                                
                                <!-- Prerequisites -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pré-requisitos</label>
                                    <select multiple class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus h-[52px]">
                                        <option value="basic">Conhecimentos básicos de computação</option>
                                        <option value="network">Noções de redes</option>
                                        <option value="programming">Lógica de programação</option>
                                    </select>
                                </div>
                                
                                <!-- Visibility -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibilidade</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus">
                                        <option value="public">Público</option>
                                        <option value="private">Privado (somente com link)</option>
                                        <option value="hidden">Oculto</option>
                                    </select>
                                </div>
                                
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus">
                                        <option value="draft">Rascunho</option>
                                        <option value="published">Publicado</option>
                                        <option value="archived">Arquivado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Cancelar
                            </button>
                            <button type="button" class="px-6 py-3 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition">
                                Salvar como Rascunho
                            </button>
                            <button type="submit" class="px-6 py-3 gradient-bg text-white rounded-lg hover:opacity-90 transition flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i> Publicar Curso
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>


    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Curso criado com sucesso!</h3>
                <p class="text-gray-600 mb-6">Seu curso foi cadastrado e está disponível para os alunos.</p>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeModal()" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Voltar ao Dashboard
                    </button>
                    <button onclick="closeModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Ver Curso
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Module and Lesson Management
        let moduleCount = 1;
        let lessonCounts = [1]; // Track lesson counts per module
        
        function addModule() {
            moduleCount++;
            lessonCounts.push(1); // Initialize lesson count for new module
            
            const modulesContainer = document.getElementById('modulesContainer');
            const newModule = document.createElement('div');
            newModule.className = 'module-item mb-6 p-4 border border-gray-200 rounded-lg';
            newModule.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-medium text-purple-800">Módulo ${moduleCount}</h4>
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeModule(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título do Módulo*</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none input-focus" placeholder="Ex: Introdução à Forense Digital" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição do Módulo</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none input-focus" rows="2" placeholder="Descreva o conteúdo deste módulo..."></textarea>
                </div>
                
                <div class="lessons-container ml-4 pl-4 border-l-2 border-purple-100">
                    <div class="lesson-item mb-4 p-3 bg-gray-50 rounded">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="text-sm font-medium text-gray-800">Aula 1</h5>
                            <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="removeLesson(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Título da Aula*</label>
                                <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="Ex: Conceitos Básicos" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de Conteúdo*</label>
                                <select class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" required>
                                    <option value="">Selecione...</option>
                                    <option value="video">Vídeo</option>
                                    <option value="text">Texto</option>
                                    <option value="quiz">Quiz</option>
                                    <option value="file">Arquivo</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Conteúdo/URL*</label>
                            <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="URL do vídeo ou texto do conteúdo" required>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="add-lesson-btn text-xs text-purple-600 hover:text-purple-800 flex items-center mt-2" onclick="addLesson(this)">
                    <i class="fas fa-plus-circle mr-1"></i> Adicionar Aula
                </button>
            `;
            
            modulesContainer.appendChild(newModule);
        }
        
        function removeModule(button) {
            if (moduleCount > 1) {
                const moduleItem = button.closest('.module-item');
                const moduleIndex = Array.from(moduleItem.parentNode.children).indexOf(moduleItem);
                
                moduleItem.remove();
                moduleCount--;
                lessonCounts.splice(moduleIndex, 1);
                
                // Update module numbers
                const modules = document.querySelectorAll('.module-item');
                modules.forEach((module, index) => {
                    module.querySelector('h4').textContent = Módulo ${index + 1};
                });
            } else {
                alert('O curso deve ter pelo menos um módulo.');
            }
        }
        
        function addLesson(button) {
            const moduleItem = button.closest('.module-item');
            const moduleIndex = Array.from(moduleItem.parentNode.children).indexOf(moduleItem);
            
            lessonCounts[moduleIndex]++;
            const lessonCount = lessonCounts[moduleIndex];
            
            const lessonsContainer = moduleItem.querySelector('.lessons-container');
            const newLesson = document.createElement('div');
            newLesson.className = 'lesson-item mb-4 p-3 bg-gray-50 rounded';
            newLesson.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-sm font-medium text-gray-800">Aula ${lessonCount}</h5>
                    <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="removeLesson(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título da Aula*</label>
                        <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="Ex: Conceitos Básicos" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de Conteúdo*</label>
                        <select class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" required>
                            <option value="">Selecione...</option>
                            <option value="video">Vídeo</option>
                            <option value="text">Texto</option>
                            <option value="quiz">Quiz</option>
                            <option value="file">Arquivo</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Conteúdo/URL*</label>
                    <input type="text" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none input-focus" placeholder="URL do vídeo ou texto do conteúdo" required>
                </div>
            `;
            
            lessonsContainer.appendChild(newLesson);
        }
        
        function removeLesson(button) {
            const lessonItem = button.closest('.lesson-item');
            const lessonsContainer = lessonItem.parentNode;
            
            if (lessonsContainer.children.length > 1) {
                lessonItem.remove();
                
                // Update lesson numbers in this module
                const moduleItem = button.closest('.module-item');
                const moduleIndex = Array.from(moduleItem.parentNode.children).indexOf(moduleItem);
                lessonCounts[moduleIndex]--;
                
                const lessons = moduleItem.querySelectorAll('.lesson-item');
                lessons.forEach((lesson, index) => {
                    lesson.querySelector('h5').textContent = Aula ${index + 1};
                });
            } else {
                alert('Cada módulo deve ter pelo menos uma aula.');
            }
        }
        
        // Form Submission
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const courseName = document.getElementById('courseName').value;
            const courseCode = document.getElementById('courseCode').value;
            
            if (!courseName || !courseCode) {
                alert('Por favor, preencha os campos obrigatórios.');
                return;
            }
            
            // Show success modal
            document.getElementById('successModal').classList.remove('hidden');
        });
        
        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
        }
        
        // Initialize floating labels
        document.querySelectorAll('.floating-input').forEach(input => {
            // Check if input has value on page load
            if (input.value) {
                input.nextElementSibling.classList.add('floating-label-active');
            }
            
            // Add event listeners
            input.addEventListener('focus', () => {
                input.nextElementSibling.classList.add('floating-label-active');
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.nextElementSibling.classList.remove('floating-label-active');
                }
            });
        });
    
    </script>
      <?php include 'footer.php'; ?>
</body>
</html>