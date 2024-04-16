-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 17/04/2024 às 00:30
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
-- Banco de dados: `jbb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`Email`, `Senha`) VALUES
('admin@admin.com', '$2y$10$SXSPMKPliyAH6GT5DtIm1.f76it4Fptxpg7BEpK.jbqztUqrLeOVa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_pf`
--

CREATE TABLE `usuarios_pf` (
  `ID` int(11) NOT NULL,
  `PrimeiroNome` varchar(50) DEFAULT NULL,
  `SegundoNome` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DataNascimento` date DEFAULT NULL,
  `CPF` varchar(14) DEFAULT NULL,
  `Genero` varchar(20) DEFAULT NULL,
  `Telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios_pf`
--

INSERT INTO `usuarios_pf` (`ID`, `PrimeiroNome`, `SegundoNome`, `Email`, `DataNascimento`, `CPF`, `Genero`, `Telefone`) VALUES
(6, 'Admin', 'Admin', 'admin@admin.com', '2001-01-01', '095.545.019-51', 'Masculino', '41992865506');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios_pj`
--

CREATE TABLE `usuarios_pj` (
  `ID` int(11) NOT NULL,
  `NomeFantasia` varchar(100) DEFAULT NULL,
  `RazaoSocial` varchar(100) DEFAULT NULL,
  `CNPJ` varchar(18) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DataAbertura` date DEFAULT NULL,
  `InscricaoEstadual` varchar(20) DEFAULT NULL,
  `Telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Email`);

--
-- Índices de tabela `usuarios_pf`
--
ALTER TABLE `usuarios_pf`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `usuarios_pj`
--
ALTER TABLE `usuarios_pj`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios_pf`
--
ALTER TABLE `usuarios_pf`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios_pj`
--
ALTER TABLE `usuarios_pj`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
