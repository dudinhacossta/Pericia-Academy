-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/06/2025 às 20:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pericia_cursos_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_itens`
--

CREATE TABLE `carrinho_itens` (
  `id_item_carrinho` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `adicionado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome_utilizador` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome_utilizador`, `email`, `telefone`, `senha`, `criado_em`) VALUES
(0, 'Admin', 'admin@gmail.com', '61998246659', '$2y$10$LhmYkvcWf4wptlT11RATkOxIYw9vBXa5d2ev0jvlIDRZZfwtNcsZO', '2025-06-07 12:17:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `descricao_completa` text DEFAULT NULL,
  `instrutor` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `url_imagem_capa` varchar(255) DEFAULT NULL,
  `nivel` enum('Iniciante','Intermediário','Avançado') NOT NULL DEFAULT 'Iniciante',
  `duracao_horas` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `subtitulo`, `descricao_completa`, `instrutor`, `preco`, `url_imagem_capa`, `nivel`, `duracao_horas`, `criado_em`, `ativo`) VALUES
(1, 'Introdução à Criminologia e Perícia Forense', 'Entenda os fundamentos da ciência forense e as principais áreas da criminologia.', 'Este curso abrange os conceitos básicos, história da perícia, e introduz as diversas disciplinas forenses como documentoscopia, balística e análise de locais de crime.', 'Dr. Vítor Santos', 299.90, 'imagens_produtos/introdução_pericia.jpg', 'Iniciante', 20, '2025-06-07 12:12:01', 1),
(2, 'Técnicas Avançadas em Análise de DNA Forense', 'Aprenda a coletar, processar e analisar amostras de DNA em investigações criminais.', 'Curso prático focado em metodologias de PCR, sequenciamento, e interpretação de perfis genéticos para identificação humana.', 'Dra. Ana Pereira', 749.50, 'imagens_produtos/dna.jpg', 'Avançado', 45, '2025-06-07 12:12:01', 1),
(3, 'Documentoscopia: Análise de Fraudes e Falsificações', 'Torne-se um especialista em identificar adulterações em documentos, assinaturas e moedas.', 'Estudo de casos reais, uso de equipamentos como lupas e luzes especiais, e análise de características de segurança em documentos oficiais.', 'Perito Carlos Andrade', 499.00, 'imagens_produtos/falsicicacao.webp', 'Intermediário', 30, '2025-06-07 12:12:01', 1),
(4, 'Balística Forense e Análise de Armas de Fogo', 'O guia completo sobre a análise de projéteis, armas e cenas de tiro.', 'Desde a identificação de armas e munições até a reconstrução de trajetórias e análise de resíduos de disparos. Essencial para peritos criminais.', 'Instrutor Roberto Lima', 590.00, 'imagens_produtos/bala.jpg', 'Intermediário', 25, '2025-06-07 12:12:01', 1),
(5, 'Psicologia Forense e Criminal Profiling', 'Mergulhe na mente criminosa e aprenda técnicas de perfilamento de suspeitos.', 'Este curso explora a intersecção entre psicologia e o sistema legal, abordando a avaliação de testemunhas, sanidade mental e a criação de perfis comportamentais.', 'Psic. Laura Mendes', 620.00, 'imagens_produtos/Especializacao-em-Criminal-Profilling-aprovada-1-e1586920658311.jpg', 'Avançado', 35, '2025-06-07 12:12:01', 1),
(6, 'Análise de Manchas de Sangue (Bloodstain Pattern Analysis)', 'A Análise de Padrões de Manchas de Sangue\r\n\r\n', 'A Análise de Padrões de Manchas de Sangue é uma técnica da criminalística utilizada para estudar os formatos, tamanhos, direções e distribuição de manchas de sangue encontradas em uma cena de crime.\r\n\r\n', 'Dr. Vítor Santos', 299.90, 'imagens_produtos/bloodstain.webp', 'Iniciante', 20, '2025-06-07 12:12:33', 1),
(7, 'Perícia Ambiental', 'Envolve análise científica e técnica para identificar responsáveis, causas e consequências de impactos ambientais.', 'A Perícia Ambiental é o ramo da perícia técnica que investiga danos ou crimes ambientais, como desmatamento ilegal, poluição de rios, contaminação do solo, emissão de poluentes atmosféricos, entre outros. ', 'Dra. Ana Pereira', 749.50, 'imagens_produtos/pericia_ambiental.jpg', 'Avançado', 45, '2025-06-07 12:12:33', 1),
(8, 'Informática Forense: Fundamentos e Ferramentas', 'A Informática Forense, ou Perícia Forense Digital, é a área responsável por identificar, preservar, analisar e apresentar evidências digitais de forma válida e legal.', 'Com foco na investigação de crimes cibernéticos ou na obtenção de provas digitais em investigações criminais, civis ou administrativas.', 'Perito Carlos Andrade', 499.00, 'imagens_produtos/ferramentas_cyber.jpg', 'Intermediário', 30, '2025-06-07 12:12:33', 1),
(9, 'Cadeia de Custódia ', 'A Cadeia de Custódia é o conjunto de procedimentos utilizados para garantir que um vestígio coletado em uma cena de crime permaneça íntegro, autêntico e confiável desde sua localização até sua apresentação em juízo.', 'A preservação da cena do crime, por sua vez, diz respeito às ações realizadas para proteger os vestígios e evitar sua contaminação ou perda, garantindo uma análise pericial válida e eficaz.', 'Instrutor Roberto Lima', 590.00, 'imagens_produtos/cadeia.jpg', 'Intermediário', 25, '2025-06-07 12:12:33', 1),
(10, 'Tanatologia Forense: Estudo da Morte e Seus Sinais', 'A Tanatologia Forense é a área da Medicina Legal que estuda a morte, seus processos, sinais e consequências, com foco na identificação da causa mortis, estimativa do tempo de morte e análise das alterações cadavéricas.', 'É uma disciplina fundamental na reconstrução de eventos criminais em que há vítimas fatais.\r\n\r\nAtravés de conhecimentos técnicos, peritos tanatologistas auxiliam na determinação da dinâmica da morte, distinguindo entre homicídio, suicídio, acidente ou morte natural.', 'Psic. Laura Mendes', 620.00, 'imagens_produtos/tatologia.jpg', 'Avançado', 35, '2025-06-07 12:12:33', 1),
(11, 'Perícia Contábil e Financeira: Investigando Fraudes', 'A Perícia Contábil e Financeira é uma área da perícia criminal e judicial dedicada à investigação de fraudes econômicas, desvios financeiros, lavagem de dinheiro e crimes contra o patrimônio. ', 'O perito contábil atua como um técnico especializado na interpretação e análise de documentos, registros contábeis e movimentações bancárias, com foco na produção de provas periciais.', 'Instrutor Roberto Lima', 590.00, 'imagens_produtos/contabil.webp', 'Intermediário', 25, '2025-06-07 12:12:01', 1),
(12, 'Luminol e Química da Investigação na Cena do Crime', 'O Luminol é um reagente químico utilizado para detectar vestígios de sangue invisíveis a olho nu, mesmo que tenham sido lavados ou tentados ocultar. ', 'Quando entra em contato com o ferro da hemoglobina, o luminol reage com um brilho azul característico (quimiluminescência), revelando padrões de manchas que ajudam a reconstituir a dinâmica do crime.', 'Psic. Laura Mendes', 620.00, 'imagens_produtos/luminol.webp', 'Avançado', 35, '2025-06-07 12:12:01', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_itens_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `nome_produto_historico` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario_historico` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) NOT NULL,
  `status_pedido` enum('pendente','processando_pagamento','pago','cancelado','reembolsado') NOT NULL DEFAULT 'pendente',
  `nome_entrega` varchar(100) DEFAULT NULL,
  `endereco_entrega` varchar(255) DEFAULT NULL,
  `cidade_entrega` varchar(100) DEFAULT NULL,
  `estado_entrega` varchar(50) DEFAULT NULL,
  `cep_entrega` varchar(20) DEFAULT NULL,
  `codigo_rastreio` varchar(50) DEFAULT NULL,
  `notas_pedido` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho_itens`
--
ALTER TABLE `carrinho_itens`
  ADD PRIMARY KEY (`id_item_carrinho`),
  ADD UNIQUE KEY `uq_cliente_produto` (`id_cliente`,`id_produto`),
  ADD KEY `idx_cliente_produto` (`id_cliente`,`id_produto`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_utilizador` (`nome_utilizador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_itens_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
