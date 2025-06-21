<?php
session_start();
require_once 'config.php';

// Lógica para o formulário de contato
$form_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_form'])) {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message_content = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($subject) && !empty($message_content)) {
        // Aqui iria a lógica para enviar o email ou salvar no banco
        $form_message = "<div class='p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg' role='alert'><strong>Obrigado, {$name}!</strong> Sua mensagem foi enviada com sucesso.</div>";
        $_POST = [];
    } else {
        $form_message = "<div class='p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg' role='alert'><strong>Erro!</strong> Por favor, preencha todos os campos corretamente.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForensicX | Perícia Criminal de Excelência</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #6b21a8 0%, #4c1d95 100%); }
        .fade-in { animation: fadeIn 1s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .hero-pattern { background-image: radial-gradient(circle, rgba(000,000,000,) 1px, transparent 1px); background-size: 30px 30px; }
        .floating { animation: floating 6s ease-in-out infinite; }
        @keyframes floating { 0% { transform: translateY(0px); } 50% { transform: translateY(-15px); } 100% { transform: translateY(0px); } }
    </style>
</head>
<body class="bg-gray-300">
    
    <?php include 'header.php'; // Inclui o novo cabeçalho do site ?>

    <!-- Hero Section -->
    <section id="home" class="gradient-bg hero-pattern text-white py-20 md:py-32 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="grid grid-cols-8 grid-rows-4 w-full h-full">
                <div class="col-span-1 row-span-1"></div>
                <div class="col-span-6 row-span-2 border-t-2 border-l-2 border-purple-300"></div>
                <div class="col-span-1 row-span-3 border-b-2 border-r-2 border-purple-300"></div>
            </div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 fade-in">
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Perícia Criminal <span class="text-black">De Elite</span></h2>
                    <p class="text-lg mb-8 opacity-90">Tecnologia avançada e especialistas renomados trabalhando para revelar a verdade por trás de cada caso.</p>
                    <div class="flex space-x-4">
                        <a href="#contact" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition duration-300 shadow-lg">Solicitar Perícia</a>
                        <a href="#services" class="border border-white hover:border-purple-300 hover:text-purple-300 text-white px-6 py-3 rounded-lg font-medium transition duration-300">Nossos Serviços</a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative floating">
                        <img src="imagens_produtos/duda.webp" alt="[Logo da ForensicX]" class="w-full h-auto" onerror="this.onerror=null;this.src='https://placehold.co/400x400/111827/FFFFFF?text=ForensicX';">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 shadow-md">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-xl bg-purple-50 border border-purple-100">
                    <div class="text-4xl font-bold text-purple-800 mb-2">+500</div>
                    <div class="text-purple-600 font-medium">Casos Solucionados</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-purple-50 border border-purple-100">
                    <div class="text-4xl font-bold text-purple-800 mb-2">98.7%</div>
                    <div class="text-purple-600 font-medium">Taxa de Precisão</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-purple-50 border border-purple-100">
                    <div class="text-4xl font-bold text-purple-800 mb-2">15+</div>
                    <div class="text-purple-600 font-medium">Anos de Experiência</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nossos <span class="text-purple-700">Serviços</span></h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition duration-500">
                    <div class="h-48 bg-purple-700 flex items-center justify-center">
                        <i class="fas fa-dna text-6xl text-white opacity-90"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Análise Forense Digital</h3>
                        <p class="text-gray-600 mb-4">Recuperação e análise de provas digitais de dispositivos eletrônicos com tecnologia de ponta.</p>
                        <a href="#" class="text-purple-600 font-medium hover:text-purple-800 transition duration-300 flex items-center">
                            Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition duration-500">
                    <div class="h-48 bg-purple-800 flex items-center justify-center">
                        <i class="fas fa-fingerprint text-6xl text-white opacity-90"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Identificação Biométrica</h3>
                        <p class="text-gray-600 mb-4">Técnicas avançadas de identificação por impressões digitais, reconhecimento facial e DNA.</p>
                        <a href="#" class="text-purple-600 font-medium hover:text-purple-800 transition duration-300 flex items-center">
                            Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Service 3 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition duration-500">
                    <div class="h-48 bg-purple-900 flex items-center justify-center">
                        <i class="fas fa-vial text-6xl text-white opacity-90"></i>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Análise Química</h3>
                        <p class="text-gray-600 mb-4">Exames laboratoriais precisos para detectar substâncias químicas e toxicológicas.</p>
                        <a href="#" class="text-purple-600 font-medium hover:text-purple-800 transition duration-300 flex items-center">
                            Saiba mais <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-20 bg-gradient-to-br from-purple-900 to-purple-700 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nosso <span class="text-purple-300">Processo</span></h2>
                <div class="w-20 h-1 bg-purple-300 mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center p-6 rounded-xl bg-purple-800 bg-opacity-30 border border-purple-300 border-opacity-30">
                    <div class="w-16 h-16 rounded-full bg-purple-700 flex items-center justify-center mx-auto mb-4"><span class="text-white text-xl font-bold">1</span></div>
                    <h3 class="text-xl font-semibold mb-2">Coleta de Evidências</h3>
                    <p class="opacity-90">Documentação e preservação rigorosa do local do crime.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center p-6 rounded-xl bg-purple-800 bg-opacity-30 border border-purple-300 border-opacity-30">
                    <div class="w-16 h-16 rounded-full bg-purple-700 flex items-center justify-center mx-auto mb-4"><span class="text-white text-xl font-bold">2</span></div>
                    <h3 class="text-xl font-semibold mb-2">Análise Laboratorial</h3>
                    <p class="opacity-90">Exame detalhado com tecnologias de última geração.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center p-6 rounded-xl bg-purple-800 bg-opacity-30 border border-purple-300 border-opacity-30">
                    <div class="w-16 h-16 rounded-full bg-purple-700 flex items-center justify-center mx-auto mb-4"><span class="text-white text-xl font-bold">3</span></div>
                    <h3 class="text-xl font-semibold mb-2">Interpretação Científica</h3>
                    <p class="opacity-90">Correlação e contextualização das descobertas.</p>
                </div>
                
                <!-- Step 4 -->
                <div class="text-center p-6 rounded-xl bg-purple-800 bg-opacity-30 border border-purple-300 border-opacity-30">
                    <div class="w-16 h-16 rounded-full bg-purple-700 flex items-center justify-center mx-auto mb-4"><span class="text-white text-xl font-bold">4</span></div>
                    <h3 class="text-xl font-semibold mb-2">Relatório Técnico</h3>
                    <p class="opacity-90">Documentação detalhada para uso processual.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nossa <span class="text-purple-700">Equipe</span></h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Member 1 -->
                <div class="text-center transform hover:scale-105 transition duration-500">
                    <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-purple-200 mb-4">
                        <img src="https://randomuser.me/api/portraits/women/63.jpg" alt="[Imagem de Dra. Ana Silva]" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Dra. Ana Silva</h3>
                    <p class="text-purple-600 font-medium">Perita Criminal Forense</p>
                    <p class="text-gray-600 mt-2">15 anos de experiência em análise de cena de crime</p>
                </div>
                
                <!-- Member 2 -->
                <div class="text-center transform hover:scale-105 transition duration-500">
                    <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-purple-200 mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="[Imagem de Dr. Carlos Mendes]" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Dr. Carlos Mendes</h3>
                    <p class="text-purple-600 font-medium">Especialista em DNA</p>
                    <p class="text-gray-600 mt-2">PhD em Genética Forense</p>
                </div>
                
                <!-- Member 3 -->
                <div class="text-center transform hover:scale-105 transition duration-500">
                    <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-purple-200 mb-4">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="[Imagem de Dra. Juliana Freitas]" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Dra. Juliana Freitas</h3>
                    <p class="text-purple-600 font-medium">Perita Digital</p>
                    <p class="text-gray-600 mt-2">Especialista em cibercrimes e análise forense digital</p>
                </div>
                
                <!-- Member 4 -->
                <div class="text-center transform hover:scale-105 transition duration-500">
                    <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-purple-200 mb-4">
                        <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="[Imagem de Dr. Roberto Alencar]" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Dr. Roberto Alencar</h3>
                    <p class="text-purple-600 font-medium">Toxicologista Forense</p>
                    <p class="text-gray-600 mt-2">Especialista em análise de substâncias químicas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Depoimentos</h2>
                <div class="w-20 h-1 bg-purple-600 mx-auto"></div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg p-8 relative">
                    <div class="text-purple-400 text-5xl absolute top-4 left-4">"</div>
                    <p class="text-lg text-gray-700 mb-6 px-8">A equipe da ForensicX foi essencial no nosso caso mais complexo deste ano. O relatório técnico apresentado foi determinante para elucidar o crime com precisão científica irrefutável.</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="[Imagem de Delegado Sérgio Andrade]" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Delegado Sérgio Andrade</h4>
                            <p class="text-purple-600 text-sm">Polícia Civil - Departamento de Homicídios</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Entre em <span class="text-purple-700">Contato</span></h2>
                    <div class="w-20 h-1 bg-purple-600 mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-purple-100 rounded-xl p-8 h-full">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Informações de Contato</h3>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="text-purple-700 mr-4 mt-1"><i class="fas fa-map-marker-alt text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Endereço</h4>
                                        <p class="text-gray-600">Av. Brasil, 2100 - Centro<br>São Paulo - SP, 01431-000</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="text-purple-700 mr-4 mt-1"><i class="fas fa-phone-alt text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Telefone</h4>
                                        <p class="text-gray-600">(11) 3224-5800</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="text-purple-700 mr-4 mt-1"><i class="fas fa-envelope text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Email</h4>
                                        <p class="text-gray-600">contato@forensicx.com.br</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="text-purple-700 mr-4 mt-1"><i class="fas fa-clock text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">Horário de Atendimento</h4>
                                        <p class="text-gray-600">Seg-Sex: 8h às 19h<br>Sábado: 8h às 12h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <?php if ($form_message): echo $form_message; endif; ?>
                        <form action="index.php#contact" method="POST" class="bg-white rounded-xl shadow-lg p-8 border border-purple-100">
                            <input type="hidden" name="contact_form" value="1">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Envie sua Mensagem</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-gray-700 mb-2">Nome Completo</label>
                                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                </div>
                                <div>
                                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                                <div>
                                    <label for="subject" class="block text-gray-700 mb-2">Assunto</label>
                                    <select id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                                        <option value="" <?php if(empty($_POST['subject'])) echo 'selected'; ?>>Selecione um assunto</option>
                                        <option value="Solicitação de perícia" <?php if(isset($_POST['subject']) && $_POST['subject'] == 'Solicitação de perícia') echo 'selected'; ?>>Solicitação de perícia</option>
                                        <option value="Informações sobre serviços" <?php if(isset($_POST['subject']) && $_POST['subject'] == 'Informações sobre serviços') echo 'selected'; ?>>Informações sobre serviços</option>
                                        <option value="Parcerias" <?php if(isset($_POST['subject']) && $_POST['subject'] == 'Parcerias') echo 'selected'; ?>>Parcerias</option>
                                        <option value="Outros" <?php if(isset($_POST['subject']) && $_POST['subject'] == 'Outros') echo 'selected'; ?>>Outros</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="message" class="block text-gray-700 mb-2">Mensagem</label>
                                    <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300">
                                    Enviar Mensagem <i class="fas fa-paper-plane ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; // Inclui o novo rodapé do site ?>
    
</body>
</html>
