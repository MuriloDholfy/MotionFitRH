-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2024 às 15:23
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
-- Banco de dados: `bdmotion`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbadmin`
--

CREATE TABLE `tbadmin` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nivel_acesso` int(11) DEFAULT NULL,
  `permissao_gerenciar` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcandidato`
--

CREATE TABLE `tbcandidato` (
  `idCandidato` int(11) NOT NULL,
  `nomeCandidato` varchar(255) DEFAULT NULL,
  `triagemCandidato` char(10) DEFAULT NULL,
  `emailCandidato` varchar(255) DEFAULT NULL,
  `telefoneCandidato` char(15) DEFAULT NULL,
  `idUnidade` int(11) DEFAULT NULL,
  `idVaga` int(11) DEFAULT NULL,
  `dataEntrevista` date DEFAULT NULL,
  `dataAprovacaoEntrevista` date DEFAULT NULL,
  `dataRegistro` date DEFAULT NULL,
  `registro` char(255) DEFAULT NULL,
  `caju` char(255) DEFAULT NULL,
  `ponto` char(255) DEFAULT NULL,
  `contratoAssinado` char(255) DEFAULT NULL,
  `uniforme` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbcandidato`
--

INSERT INTO `tbcandidato` (`idCandidato`, `nomeCandidato`, `triagemCandidato`, `emailCandidato`, `telefoneCandidato`, `idUnidade`, `idVaga`, `dataEntrevista`, `dataAprovacaoEntrevista`, `dataRegistro`, `registro`, `caju`, `ponto`, `contratoAssinado`, `uniforme`) VALUES
(18, 'cleiton', 'Não', 'murilo@gmail.com', '11985645677', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Alex', 'Não', 'murilo@gmail.co', '1111111111111', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Alex', 'Não', 'murilo@gmail.co', '1111111111111', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'Alex', 'Não', 'murilo@gmail.co', '1111111111111', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'carlos', 'Não', 'carlos@gmail.com', '11985895677', 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'carlos', 'Não', 'murilo@gmail.com', '11985645677', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'carlos', 'Não', 'murilo@gmail.com', '1111111111111', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'Murilo', 'Não', 'murilo@gmail.com', '11985645677', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'carlos', 'Sim', 'carlos@gmail.com', '11985895677', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Murilo', 'Sim', 'murilo@gmail.com', '11985645677', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'Murilo Dholfy Silveira Ferreira', 'Sim', 'murilo@gmail.com', '1111111111111', 3, 53, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbdp_pessoal`
--

CREATE TABLE `tbdp_pessoal` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `setor_responsavel` varchar(100) DEFAULT NULL,
  `numero_funcionarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbgerente`
--

CREATE TABLE `tbgerente` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbgerente`
--

INSERT INTO `tbgerente` (`id`, `usuario_id`, `departamento`) VALUES
(1, 1, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbgerenteregional`
--

CREATE TABLE `tbgerenteregional` (
  `idGerenteRegional` int(11) NOT NULL,
  `nomeGerenteRegional` varchar(255) DEFAULT NULL,
  `emailGerenteRegional` varchar(255) DEFAULT NULL,
  `senhaGerenteRegional` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbgerenteregional`
--

INSERT INTO `tbgerenteregional` (`idGerenteRegional`, `nomeGerenteRegional`, `emailGerenteRegional`, `senhaGerenteRegional`) VALUES
(1, 'Isaias Dos Santos', 'isaiassantosmotionfit@gmail.com', '1234'),
(2, 'Raquel Souza', 'raquel.sousa@motionfitacademia.com.br', '1234'),
(3, 'Marcos Vinicios', 'marcos.vinicios@motionfitacademia.com.br', '1234'),
(4, 'Erika Justino', 'erika.justino@motionfitacademia.com.br', '1234');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbrecrutamento`
--

CREATE TABLE `tbrecrutamento` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `experiencia_anos` int(11) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbregiao`
--

CREATE TABLE `tbregiao` (
  `idRegiao` int(11) NOT NULL,
  `nomeRegiao` varchar(255) NOT NULL,
  `idGerenteRegional` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbregiao`
--

INSERT INTO `tbregiao` (`idRegiao`, `nomeRegiao`, `idGerenteRegional`) VALUES
(1, 'Litoral', 1),
(2, 'Zona Leste', 4),
(3, 'Alto Tietê', 3),
(4, 'São Miguel', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbselecao`
--

CREATE TABLE `tbselecao` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `certificacoes` varchar(255) DEFAULT NULL,
  `nivel_formacao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbunidade`
--

CREATE TABLE `tbunidade` (
  `idUnidade` int(11) NOT NULL,
  `nomeUnidade` varchar(255) DEFAULT NULL,
  `idRegiao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbunidade`
--

INSERT INTO `tbunidade` (`idUnidade`, `nomeUnidade`, `idRegiao`) VALUES
(1, 'itanhaem', 1),
(2, 'savoy', 2),
(3, 'suzano', 3),
(4, 'belas artes', 1),
(6, 'jacui', 4),
(7, 'jardim helena', 3),
(8, 'pires do rio', 4),
(9, 'tatuape', 2),
(10, 'Nome Atual', 1),
(11, 'limoeiro', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `idUsuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipoUsuario` enum('gerenteRegional','gerente','recrutamento','selecao','dpPessoal','adm') NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbusuario`
--

INSERT INTO `tbusuario` (`idUsuario`, `nome`, `email`, `senha`, `tipoUsuario`, `data_criacao`) VALUES
(1, 'murilo', 'murilo@gmail.com', '1234', 'adm', '2024-08-16 13:58:21'),
(3, 'Isaias Dos Santos', 'isaiassantosmotionfit@gmail.com', '1234', 'gerenteRegional', '2024-09-04 16:16:09'),
(4, 'Raquel Souza', 'raquel.sousa@motionfitacademia.com.br', '1234', 'gerenteRegional', '2024-09-04 16:16:09'),
(5, 'Marcos Vinicios', 'marcos.vinicios@motionfitacademia.com.br', '1234', 'gerenteRegional', '2024-09-04 16:16:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbvaga`
--

CREATE TABLE `tbvaga` (
  `idVaga` int(11) NOT NULL,
  `nomeVaga` varchar(255) DEFAULT NULL,
  `cargoVaga` varchar(255) DEFAULT NULL,
  `especialidadeVaga` varchar(255) DEFAULT NULL,
  `tipoVaga` varchar(255) DEFAULT NULL,
  `tipoContrato` varchar(255) DEFAULT NULL,
  `grauEmergencia` varchar(255) DEFAULT NULL,
  `horarioVaga` time DEFAULT NULL,
  `diaSemana` varchar(255) DEFAULT NULL,
  `dataAberturaVaga` date DEFAULT NULL,
  `idUnidade` int(11) DEFAULT NULL,
  `statusVaga` varchar(255) DEFAULT 'Pendente',
  `aprovadorVaga` varchar(255) DEFAULT NULL,
  `ProcessoVaga` enum('Pedido em Análise','Pedido Aprovado','Recrutamento','Seleção','DP Pessoal') DEFAULT NULL,
  `idUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbvaga`
--

INSERT INTO `tbvaga` (`idVaga`, `nomeVaga`, `cargoVaga`, `especialidadeVaga`, `tipoVaga`, `tipoContrato`, `grauEmergencia`, `horarioVaga`, `diaSemana`, `dataAberturaVaga`, `idUnidade`, `statusVaga`, `aprovadorVaga`, `ProcessoVaga`, `idUsuario`) VALUES
(35, 'VAGA 83', 'estagiárioasdas', 'Musculação', 'REPOSIÇÃO', 'ESTÁGIO', '15 dias', '16:00:00', 'SEG A SEXTA', '2024-06-15', 1, 'Rejeitado', NULL, '', NULL),
(36, 'VAGA 81', 'estagiário', 'MUSculação', 'NOVA VAGA', 'ESTÁGIO', '7 dias', '18:00:00', 'SEG A SEXTA', '2024-06-17', 11, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(37, 'VAGA 10', 'prof. modalidade', 'JUMP', 'NOVA VAGA', 'TERCEIRIZADO', '7 DIAS', '08:00:00', 'QUARTA', '2024-08-27', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(38, 'VAGA 10', 'prof. modalidade', 'STEP', 'NOVA VAGA', 'TERCEIRIZADO', '7 DIAS', '18:00:00', 'QUARTA', '2024-08-27', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(39, 'VAGA 10', 'prof. modalidade', 'ZUMBA', 'NOVA VAGA', 'TERCEIRIZADO', '7 DIAS', '19:00:00', 'QUARTA', '2024-08-27', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(40, 'VAGA 18', 'estagiário', 'Musculação', 'NOVA VAGA', 'ESTÁGIO', '15', '16:00:00', 'SEG A SEXTA', '2024-08-19', 3, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(41, 'VAGA 10', 'prof. modalidade', 'FUNCIONAL', 'NOVA VAGA', 'TERCEIRIZADO', '7 DIAS', '20:00:00', 'QUARTA', '2024-08-27', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(42, 'VAGA 80', 'prof. musculação', 'Musculação', 'REPOSIÇÃO', 'MENSAL', '15 dias', '19:00:00', 'SEG A SEXTA', '2024-06-18', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(43, 'VAGA 80', 'gerente', 'Gestão / ADM', 'REPOSIÇÃO', 'MENSAL', '15 dias', '09:00:00', 'SEG A SEXTA', '2024-06-18', 4, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(44, 'VAGA 79', 'estagiário', 'Musculação', 'NOVA VAGA', 'ESTÁGIO', '30 dias', '05:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(45, 'VAGA 79', 'estagiário', 'Musculação', 'NOVA VAGA', 'ESTÁGIO', '30 dias', '06:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(46, 'VAGA 79', 'prof. musculação', 'Musculação', 'NOVA VAGA', 'HORISTA', '30 dias', '10:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(47, 'VAGA 79', 'estagiário', 'Musculação', 'NOVA VAGA', 'ESTÁGIO', '30 dias', '13:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(48, 'VAGA 79', 'prof. musculação', 'Musculação', 'NOVA VAGA', 'HORISTA', '30 dias', '19:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(49, 'VAGA 3', 'estagiário', 'Muculação', 'NOVA VAGA', 'ESTÁGIO', '7 dias', '17:00:00', 'SEG A SEXTA', '2024-09-03', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(50, 'VAGA 79', 'estagiário', 'MUSculação', 'NOVA VAGA', 'ESTÁGIO', '30 dias', '17:00:00', 'SEG A SEXTA', '2024-06-19', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(51, 'VAGA 8', 'prof. modalidade', 'jump', 'REPOSIÇÃO', 'TERCEIRIZADO', '7 dias', '00:00:00', '2x na semana', '2024-08-29', NULL, 'Pendente', NULL, 'Pedido em Análise', NULL),
(52, 'VAGA 77', 'prof. musculação', 'MUSCULAÇÃO', 'REPOSIÇÃO', 'HORISTA', '15 dias', '20:00:00', 'SEG A SEXTA', '2024-06-21', 10, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(53, 'VAGA 11', 'prof. modalidade', 'PILATES', 'REPOSIÇÃO', 'TERCEIRIZADO', '15 dias', '18:00:00', 'Terça e quinta', '2024-08-26', 2, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(54, 'VAGA 70', 'estagiário', 'MUSCULAÇÃO', 'REPOSIÇÃO', 'ESTÁGIO', '7 dias', '13:00:00', 'SEG A SEXTA', '2024-06-28', 1, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(55, 'VAGA 66', 'prof. modalidade', 'JUMP', 'REPOSIÇÃO', 'TERCEIRIZADO', '7 dias', '07:00:00', '', '2024-07-02', 3, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(56, 'VAGA 66', 'prof. modalidade', 'Musculação', 'REPOSIÇÃO', 'TERCEIRIZADO', '7 dias', '18:00:00', '', '2024-07-02', 3, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(57, 'VAGA 66', 'prof. modalidade', 'MUSCULAÇÃO', 'REPOSIÇÃO', 'TERCEIRIZADO', '7 dias', '19:00:00', '', '2024-07-02', 3, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(58, 'VAGA 20', 'prof. musculação', 'MUSCULAÇÃO', 'REPOSIÇÃO', 'HORISTA', '7 dias', '17:00:00', 'TERÇA-FEIRA', '2024-08-28', 1, 'Rejeitado', NULL, '', NULL),
(59, 'VAGA 20', 'prof. modalidade', 'FUNCIONAL', 'REPOSIÇÃO', 'HORISTA', '7 dias', '18:00:00', 'TERÇA-FEIRA', '2024-08-28', 1, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(60, 'VAGA 20', 'prof. modalidade', 'ZUMBA', 'REPOSIÇÃO', 'HORISTA', '7 dias', '19:00:00', 'TERÇA-FEIRA', '2024-08-28', 1, 'Aprovado', NULL, 'Pedido Aprovado', NULL),
(61, NULL, 'Consultor', 'nenhuma', 'NovaVaga', 'Horista', 'alta', '10:00:00', 'Terça-feira', '2024-09-12', 3, 'Aprovado', NULL, 'Pedido Aprovado', 2),
(73, NULL, 'Professor', 'Fit Dance', 'Reposicao', 'Tercerizado', 'media', '13:52:00', 'Segunda-feira', '2024-09-12', 1, 'Pendente', NULL, NULL, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbadmin`
--
ALTER TABLE `tbadmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbcandidato`
--
ALTER TABLE `tbcandidato`
  ADD PRIMARY KEY (`idCandidato`),
  ADD KEY `fk_idVaga` (`idVaga`);

--
-- Índices de tabela `tbdp_pessoal`
--
ALTER TABLE `tbdp_pessoal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbgerente`
--
ALTER TABLE `tbgerente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbgerenteregional`
--
ALTER TABLE `tbgerenteregional`
  ADD PRIMARY KEY (`idGerenteRegional`);

--
-- Índices de tabela `tbrecrutamento`
--
ALTER TABLE `tbrecrutamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbregiao`
--
ALTER TABLE `tbregiao`
  ADD PRIMARY KEY (`idRegiao`),
  ADD KEY `idGerenteRegional` (`idGerenteRegional`);

--
-- Índices de tabela `tbselecao`
--
ALTER TABLE `tbselecao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `tbunidade`
--
ALTER TABLE `tbunidade`
  ADD PRIMARY KEY (`idUnidade`),
  ADD KEY `idRegiao` (`idRegiao`);

--
-- Índices de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `tbvaga`
--
ALTER TABLE `tbvaga`
  ADD PRIMARY KEY (`idVaga`),
  ADD KEY `idUnidade` (`idUnidade`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbadmin`
--
ALTER TABLE `tbadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbcandidato`
--
ALTER TABLE `tbcandidato`
  MODIFY `idCandidato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `tbdp_pessoal`
--
ALTER TABLE `tbdp_pessoal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbgerente`
--
ALTER TABLE `tbgerente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbgerenteregional`
--
ALTER TABLE `tbgerenteregional`
  MODIFY `idGerenteRegional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbrecrutamento`
--
ALTER TABLE `tbrecrutamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbregiao`
--
ALTER TABLE `tbregiao`
  MODIFY `idRegiao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbselecao`
--
ALTER TABLE `tbselecao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbunidade`
--
ALTER TABLE `tbunidade`
  MODIFY `idUnidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1047;

--
-- AUTO_INCREMENT de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `tbvaga`
--
ALTER TABLE `tbvaga`
  MODIFY `idVaga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbadmin`
--
ALTER TABLE `tbadmin`
  ADD CONSTRAINT `tbadmin_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tbusuario` (`idUsuario`);

--
-- Restrições para tabelas `tbcandidato`
--
ALTER TABLE `tbcandidato`
  ADD CONSTRAINT `fk_idVaga` FOREIGN KEY (`idVaga`) REFERENCES `tbvaga` (`idVaga`);

--
-- Restrições para tabelas `tbdp_pessoal`
--
ALTER TABLE `tbdp_pessoal`
  ADD CONSTRAINT `tbdp_pessoal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tbusuario` (`idUsuario`);

--
-- Restrições para tabelas `tbgerente`
--
ALTER TABLE `tbgerente`
  ADD CONSTRAINT `tbgerente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tbusuario` (`idUsuario`);

--
-- Restrições para tabelas `tbrecrutamento`
--
ALTER TABLE `tbrecrutamento`
  ADD CONSTRAINT `tbrecrutamento_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tbusuario` (`idUsuario`);

--
-- Restrições para tabelas `tbregiao`
--
ALTER TABLE `tbregiao`
  ADD CONSTRAINT `tbregiao_ibfk_1` FOREIGN KEY (`idGerenteRegional`) REFERENCES `tbgerenteregional` (`idGerenteRegional`);

--
-- Restrições para tabelas `tbselecao`
--
ALTER TABLE `tbselecao`
  ADD CONSTRAINT `tbselecao_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tbusuario` (`idUsuario`);

--
-- Restrições para tabelas `tbunidade`
--
ALTER TABLE `tbunidade`
  ADD CONSTRAINT `tbunidade_ibfk_1` FOREIGN KEY (`idRegiao`) REFERENCES `tbregiao` (`idRegiao`);

--
-- Restrições para tabelas `tbvaga`
--
ALTER TABLE `tbvaga`
  ADD CONSTRAINT `tbvaga_ibfk_1` FOREIGN KEY (`idUnidade`) REFERENCES `tbunidade` (`idUnidade`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
