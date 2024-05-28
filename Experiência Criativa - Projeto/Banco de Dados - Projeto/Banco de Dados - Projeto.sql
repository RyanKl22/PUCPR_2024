-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/05/2024 às 15:57
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

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
-- Estrutura para tabela `comunicacoesviagem`
--

CREATE TABLE `comunicacoesviagem` (
  `id` int(11) NOT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcao_user`
--

CREATE TABLE `funcao_user` (
  `ID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Primeiro_nome` varchar(100) DEFAULT NULL,
  `PJ_PF` tinyint(1) DEFAULT NULL,
  `ADM` tinyint(1) DEFAULT NULL,
  `Anunciante` tinyint(1) DEFAULT NULL,
  `Id_geral` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcao_user`
--

INSERT INTO `funcao_user` (`ID`, `email`, `Primeiro_nome`, `PJ_PF`, `ADM`, `Anunciante`, `Id_geral`) VALUES
(1, 'ryankloss2002@gmail.com', 'Ryan', 0, 0, 0, 1),
(2, 'ryankloss@gmail.com', 'Empresa Ryan', 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `gruposviagem`
--

CREATE TABLE `gruposviagem` (
  `id` int(11) NOT NULL,
  `id_roteiro` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `nome` varchar(75) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `id_roteiro` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `localizacoesvalores`
--

CREATE TABLE `localizacoesvalores` (
  `id` int(11) NOT NULL,
  `id_roteiro` int(11) DEFAULT NULL,
  `nome_local` varchar(150) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `valor_gasto` decimal(10,2) DEFAULT NULL,
  `moeda` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `membrosgrupo`
--

CREATE TABLE `membrosgrupo` (
  `id_grupo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `roteirosviagem`
--

CREATE TABLE `roteirosviagem` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(65) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `publico_privado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `ID` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`ID`, `Email`, `Senha`) VALUES
(1, 'ryankloss2002@gmail.com', '$2y$10$dmyYPpXn7Z0I9Vu3NUxcWOO7jmC/Ysl8ElY4ijUdwp9bzRF9XEUI2'),
(2, 'ryankloss@gmail.com', '$2y$10$ID/S5GxXSmhJQMzoZi5q7un7/AqvAh0RhZzn/2KdB7XWzqECxVQDC');

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
(1, 'Ryan', 'Kloss', 'ryankloss2002@gmail.com', '2002-10-30', '095.545.019-51', 'Masculino', '(41) 99286-5508');

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
-- Despejando dados para a tabela `usuarios_pj`
--

INSERT INTO `usuarios_pj` (`ID`, `NomeFantasia`, `RazaoSocial`, `CNPJ`, `Email`, `DataAbertura`, `InscricaoEstadual`, `Telefone`) VALUES
(1, 'Empresa Ryan', 'Ryan Empresa Teste', '12.345.678/9012-34', 'ryankloss@gmail.com', '2002-10-30', '411.830.857.724', '(41) 99286-5506');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comunicacoesviagem`
--
ALTER TABLE `comunicacoesviagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `funcao_user`
--
ALTER TABLE `funcao_user`
  ADD PRIMARY KEY (`ID`);

--
-- Índices de tabela `gruposviagem`
--
ALTER TABLE `gruposviagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_roteiro` (`id_roteiro`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Índices de tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_roteiro` (`id_roteiro`);

--
-- Índices de tabela `localizacoesvalores`
--
ALTER TABLE `localizacoesvalores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_roteiro` (`id_roteiro`);

--
-- Índices de tabela `membrosgrupo`
--
ALTER TABLE `membrosgrupo`
  ADD PRIMARY KEY (`id_grupo`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `roteirosviagem`
--
ALTER TABLE `roteirosviagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID`,`Email`);

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
-- AUTO_INCREMENT de tabela `comunicacoesviagem`
--
ALTER TABLE `comunicacoesviagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcao_user`
--
ALTER TABLE `funcao_user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `gruposviagem`
--
ALTER TABLE `gruposviagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `localizacoesvalores`
--
ALTER TABLE `localizacoesvalores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `roteirosviagem`
--
ALTER TABLE `roteirosviagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios_pf`
--
ALTER TABLE `usuarios_pf`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios_pj`
--
ALTER TABLE `usuarios_pj`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comunicacoesviagem`
--
ALTER TABLE `comunicacoesviagem`
  ADD CONSTRAINT `comunicacoesviagem_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `gruposviagem` (`id`),
  ADD CONSTRAINT `comunicacoesviagem_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`ID`);

--
-- Restrições para tabelas `gruposviagem`
--
ALTER TABLE `gruposviagem`
  ADD CONSTRAINT `gruposviagem_ibfk_1` FOREIGN KEY (`id_roteiro`) REFERENCES `roteirosviagem` (`id`),
  ADD CONSTRAINT `gruposviagem_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `usuario` (`ID`);

--
-- Restrições para tabelas `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `imagens_ibfk_1` FOREIGN KEY (`id_roteiro`) REFERENCES `roteirosviagem` (`id`);

--
-- Restrições para tabelas `localizacoesvalores`
--
ALTER TABLE `localizacoesvalores`
  ADD CONSTRAINT `localizacoesvalores_ibfk_1` FOREIGN KEY (`id_roteiro`) REFERENCES `roteirosviagem` (`id`);

--
-- Restrições para tabelas `membrosgrupo`
--
ALTER TABLE `membrosgrupo`
  ADD CONSTRAINT `membrosgrupo_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `gruposviagem` (`id`),
  ADD CONSTRAINT `membrosgrupo_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`ID`);

--
-- Restrições para tabelas `roteirosviagem`
--
ALTER TABLE `roteirosviagem`
  ADD CONSTRAINT `roteirosviagem_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
