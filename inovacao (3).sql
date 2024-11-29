-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Nov-2024 às 12:16
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `inovacao`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `mes_ano` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `educacao_financeira`
--

CREATE TABLE `educacao_financeira` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `conteudo` text DEFAULT NULL,
  `data_publicacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento_mes`
--

CREATE TABLE `orcamento_mes` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `alimentacao` decimal(10,2) NOT NULL,
  `transporte` decimal(10,2) NOT NULL,
  `lazer` decimal(10,2) NOT NULL,
  `moradia` decimal(10,2) NOT NULL,
  `outros` decimal(10,2) NOT NULL,
  `renda` decimal(10,2) NOT NULL,
  `moeda` enum('BRL','USD','KRW') NOT NULL,
  `total_gastos` decimal(10,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `orcamento_mes`
--

INSERT INTO `orcamento_mes` (`id`, `id_usuarios`, `alimentacao`, `transporte`, `lazer`, `moradia`, `outros`, `renda`, `moeda`, `total_gastos`, `saldo`, `data_criacao`) VALUES
(2, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 12:36:10'),
(3, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 12:53:04'),
(4, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 16:22:45'),
(5, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 16:23:50'),
(6, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 16:52:36'),
(7, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 18:25:07'),
(8, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-22 19:28:29'),
(9, 1, 670.00, 340.00, 230.00, 1200.00, 234.00, 4000.00, '', 2674.00, 1326.00, '2024-11-25 11:56:15'),
(10, 1, 200.00, 150.00, 300.00, 1200.00, 200.00, 4000.00, '', 2050.00, 1950.00, '2024-11-25 14:23:17'),
(11, 1, 200.00, 150.00, 300.00, 1200.00, 200.00, 4000.00, '', 2050.00, 1950.00, '2024-11-25 14:23:47'),
(12, 1, 200.00, 150.00, 300.00, 1200.00, 200.00, 4000.00, '', 2050.00, 1950.00, '2024-11-25 14:24:46'),
(13, 1, 800.00, 200.00, 250.00, 1500.00, 500.00, 4500.00, '', 3250.00, 1250.00, '2024-11-25 19:05:41');

-- --------------------------------------------------------

--
-- Estrutura da tabela `renda`
--

CREATE TABLE `renda` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `mes_ano` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `tipo` enum('despesa','receita') NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `nome`, `email`, `senha`, `data_cadastro`) VALUES
(1, 'sofia', 'sofialuizateixeira859@gmail.com', '$2y$10$VSrjBaMcO47BuDwyZYOjoOD741WAwwnvxjfNFJykW4ZC2hYEBuKKC', '2024-11-18 12:18:51');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `educacao_financeira`
--
ALTER TABLE `educacao_financeira`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `orcamento_mes`
--
ALTER TABLE `orcamento_mes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices para tabela `renda`
--
ALTER TABLE `renda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `educacao_financeira`
--
ALTER TABLE `educacao_financeira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `orcamento_mes`
--
ALTER TABLE `orcamento_mes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `renda`
--
ALTER TABLE `renda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `despesas`
--
ALTER TABLE `despesas`
  ADD CONSTRAINT `despesas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `orcamento_mes`
--
ALTER TABLE `orcamento_mes`
  ADD CONSTRAINT `orcamento_mes_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `renda`
--
ALTER TABLE `renda`
  ADD CONSTRAINT `renda_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
