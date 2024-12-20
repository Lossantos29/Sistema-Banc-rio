-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Dez-2024 às 21:41
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco_digital`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `contas`
--

CREATE TABLE `contas` (
  `id` int(11) NOT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `conta_origem` int(11) DEFAULT NULL,
  `conta_destino` int(11) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo_transacao` varchar(50) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_hora` datetime DEFAULT current_timestamp(),
  `detalhes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `bi_passaporte` varchar(255) NOT NULL,
  `codigo_unico` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `morada` varchar(255) DEFAULT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `saldo` int(11) NOT NULL DEFAULT 0,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `bi_passaporte`, `codigo_unico`, `email`, `morada`, `nome_completo`, `saldo`, `senha`, `telefone`) VALUES
(1, '005199592ue047', 'USER67646f818c778', 'valeriolosantos@gmail.com', 'uige', 'Valerio dos Santos', 9900, '$2y$10$EBAbsmRckm4j6J9mjAMs3uyF7kDwerQMdib.54ccY2Q2zJEshQ6Km', '926563301'),
(2, '005199592ue048', 'USER67646fbd949af', 'dhgdhdg@gg.com', 'uige', 'Andre Santos', 0, '$2y$10$HW3M0BV2VOAVARQmuGxrH.YNqrK31TpWpc9St5aXsivke1yhB/W02', '999999999');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conta_origem` (`conta_origem`),
  ADD KEY `conta_destino` (`conta_destino`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `contas`
--
ALTER TABLE `contas`
  ADD CONSTRAINT `contas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`conta_origem`) REFERENCES `contas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`conta_destino`) REFERENCES `contas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transacoes_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
