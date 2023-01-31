-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 07/07/2022 às 08:22
-- Versão do servidor: 8.0.29-0ubuntu0.22.04.2
-- Versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `php-mysqli-redis`
--
CREATE DATABASE IF NOT EXISTS `php-mysql-redis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `php-mysql-redis`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id_material` int NOT NULL,
  `reagente` varchar(255) NOT NULL,
  `lote` varchar(255) NOT NULL,
  `fabricante` varchar(255) NOT NULL,
  `fabricacao` varchar(20) NOT NULL,
  `validade` varchar(20) NOT NULL,
  `embalagem_original` varchar(10) NOT NULL,
  `quantidade` int NOT NULL,
  `cas` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente`
--

CREATE TABLE `paciente` (
  `id` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contato` varchar(20) NOT NULL,
  `genero` varchar(5) NOT NULL,
  `situacao` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` varchar(10) NOT NULL DEFAULT 'aluno'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `matricula`, `senha`, `tipo_usuario`) VALUES
(1, 'Admin', 'admin@gmail.com', '0013970', '$2y$10$c47NbrGr3Cr4p8uvtQHkcuHKIjhvPGIjr1U0jaGtiHyRvdsZUBjwu', 'admin'),
(2, 'Aluno Teste', 'teste@teste.com', '0013970', '$2y$10$xj0MSSCxXBD6fkSiGfyUlu2/FVNc31HEWRm4aYhi7KlPb2o5DjzFG', 'aluno');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id_material`);

--
-- Índices de tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id_material` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
