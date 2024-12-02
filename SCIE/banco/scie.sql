-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/12/2024 às 02:37
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `scie`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `historicoestoque`
--

CREATE TABLE `historicoestoque` (
  `id` int(11) NOT NULL,
  `responsavel` varchar(255) DEFAULT NULL,
  `codigoInterno` varchar(255) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `operacao` varchar(255) DEFAULT NULL,
  `data_acao` datetime DEFAULT current_timestamp(),
  `tipo_movimentacao` enum('Entrada','Saída') NOT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `codigoInterno` varchar(50) NOT NULL,
  `codigoCliente` varchar(50) NOT NULL,
  `revDesenho` varchar(50) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `quantidade` int(11) DEFAULT 0,
  `cliente` varchar(100) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `quantidade_total` int(11) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `data_entrada` date DEFAULT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `status` enum('Em processo','Em estoque') DEFAULT 'Em processo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('Engenharia','TI') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `tipo_usuario`) VALUES
(23, 'testeTI', '$2y$10$GsW3J6Lh7EbqbQbKlNcqReJz62oNK9jY/Xjd8Onz87fT3d/IMw8Re', 'TI'),
(24, 'testeEngenharia', '$2y$10$HexLvmf7e10DyGPu/d0vM.Ihg63qOKecKbsEkmx6xXF7RFJyfGesC', 'Engenharia');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `historicoestoque`
--
ALTER TABLE `historicoestoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigoInterno` (`codigoInterno`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `historicoestoque`
--
ALTER TABLE `historicoestoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
