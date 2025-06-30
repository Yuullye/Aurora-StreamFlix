-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jun-2025 às 13:04
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
-- Banco de dados: `streamflix`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `amigos`
--

CREATE TABLE `amigos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `amigo_id` int(11) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pendente','aceito','recusado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `amigos`
--

INSERT INTO `amigos` (`id`, `usuario_id`, `amigo_id`, `criado_em`, `status`) VALUES
(64, 1, 4, '2025-06-23 13:49:19', 'pendente'),
(65, 4, 1, '2025-06-23 13:49:19', 'pendente'),
(66, 2, 4, '2025-06-24 22:03:06', 'pendente'),
(67, 2, 1, '2025-06-24 22:03:08', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `filme_id`, `usuario_id`, `comentario`, `parent_id`, `criado_em`) VALUES
(44, 18, 1, 'ola', NULL, '2025-02-10 10:07:26'),
(63, 19, 5, 'olá', NULL, '2025-02-11 17:30:46'),
(64, 19, 5, 'jhagfwfjgfak', NULL, '2025-03-10 09:45:14'),
(65, 19, 5, 'jsgsfshgdhf', 64, '2025-03-10 09:45:26'),
(66, 20, 1, 'admin123', NULL, '2025-06-23 09:58:13'),
(67, 20, 4, 'oi', NULL, '2025-06-23 10:04:29'),
(68, 18, 4, 'zjgvjzslk,mvbds,xkn', 44, '2025-06-23 13:59:33'),
(69, 17, 2, 'jsssssssssssssss', NULL, '2025-06-24 12:50:30');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `comentarios_formatados`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `comentarios_formatados` (
`id` int(11)
,`filme_id` int(11)
,`usuario_id` int(11)
,`username` varchar(255)
,`comentario` text
,`parent_id` int(11)
,`criado_em` timestamp
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id` int(11) NOT NULL,
  `comentario_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curtidas`
--

INSERT INTO `curtidas` (`id`, `comentario_id`, `usuario_id`) VALUES
(19, 44, 4),
(20, 68, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curtidas_filme`
--

CREATE TABLE `curtidas_filme` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `tipo` enum('like','dislike') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `curtidas_filme`
--

INSERT INTO `curtidas_filme` (`id`, `usuario_id`, `filme_id`, `tipo`) VALUES
(4, 1, 18, 'like'),
(5, 3, 18, 'like'),
(6, 4, 18, 'like'),
(8, 5, 18, 'like'),
(11, 5, 17, 'like'),
(12, 5, 19, 'like'),
(13, 1, 17, 'like'),
(14, 1, 21, 'dislike'),
(15, 1, 19, 'like'),
(16, 1, 20, 'like'),
(17, 4, 21, 'dislike'),
(18, 4, 20, 'like'),
(19, 2, 17, 'like');

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `usuario_id`, `filme_id`, `criado_em`) VALUES
(16, 1, 19, '2025-02-10 17:32:13'),
(18, 5, 18, '2025-03-10 09:47:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `filmes`
--

CREATE TABLE `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `sinopse` text NOT NULL,
  `data_lancamento` date DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `genero` varchar(255) NOT NULL,
  `visualizacoes` int(11) DEFAULT 0,
  `trailer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `filmes`
--

INSERT INTO `filmes` (`id`, `titulo`, `descricao`, `imagem`, `arquivo`, `categoria`, `sinopse`, `data_lancamento`, `criado_em`, `genero`, `visualizacoes`, `trailer`) VALUES
(17, 'O Conde de Monte Cristo', '', 'uploads/imagens/le-comte-de-monte-cristo58030.webp', 'uploads/filmes/single-gunshot-54-40780.mp3', 'Drama', 'Um jovem marinheiro é injustamente preso e planeja vingança após escapar da prisão em uma ilha sombria.', '2024-07-10', '2025-02-10 10:05:33', '', 4, NULL),
(18, 'Inexplicável', '', 'uploads/imagens/cover.jpeg', 'uploads/filmes/080884_bullet-hit-39872.mp3', 'Ação', 'Um menino de 8 anos, apaixonado por futebol, enfrenta uma grave doença. Sua família busca força e fé para lidar com esta adversidade.', '2025-02-11', '2025-02-10 10:06:50', '', 4, NULL),
(19, 'long beauty', '', 'uploads/imagens/thumb-1920-881338.jpg', 'uploads/filmes/grunt1-68324.mp3', 'Fantasia científica', 'produto que ajuda no crescimento do cabelo e unhas e tambem ajuda com a saude da pele', '2025-02-05', '2025-02-10 17:31:50', '', 3, NULL),
(20, 'shdfhd', '', 'uploads/imagens/luffys-resolve-under-the-night-sky_800.gif', 'uploads/filmes/battle-of-the-dragons-8037.mp3', 'Cinema de arte', 'sdfdsfsfsfsfsfs', '2025-03-11', '2025-03-10 09:49:22', '', 4, NULL),
(21, 'la ele', '', 'uploads/imagens/imagem_2025-04-09_212103675-removebg-preview.png', 'uploads/filmes/202504091226.mp4', 'Comédia de ação', 'asdasfagagagagagagag', '2025-05-01', '2025-04-10 13:55:30', '', 3, 'uploads/trailers/TÁ DADO O RECADO! 🔥🔥🔥🔥🔥.mp4');

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `assistido_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `historico`
--

INSERT INTO `historico` (`id`, `usuario_id`, `filme_id`, `assistido_em`) VALUES
(489, 1, 17, '2025-06-23 08:59:07'),
(490, 1, 18, '2025-06-23 08:58:55'),
(498, 5, 18, '2025-05-05 15:05:06'),
(500, 4, 18, '2025-06-23 13:59:41'),
(547, 3, 18, '2025-02-10 10:48:06'),
(559, 5, 17, '2025-05-05 08:33:12'),
(578, 5, 19, '2025-05-05 15:04:56'),
(639, 1, 19, '2025-06-24 22:00:59'),
(649, 1, 21, '2025-06-25 16:20:20'),
(659, 5, 20, '2025-04-10 16:35:55'),
(666, 5, 21, '2025-05-06 16:38:46'),
(683, 1, 20, '2025-06-25 16:20:02'),
(686, 4, 19, '2025-06-23 09:58:35'),
(687, 4, 20, '2025-06-23 13:58:48'),
(695, 4, 21, '2025-06-23 13:58:29'),
(705, 3, 20, '2025-06-23 12:26:07'),
(711, 4, 17, '2025-06-23 13:54:01'),
(719, 2, 17, '2025-06-24 22:06:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `remetente_id`, `destinatario_id`, `mensagem`, `data_envio`, `lida`) VALUES
(1, 5, 4, 'olá', '2025-02-04 15:21:12', 0),
(2, 5, 4, 'olá', '2025-02-04 15:32:38', 0),
(3, 4, 5, 'olá', '2025-02-04 16:09:46', 0),
(4, 4, 5, 'estive pensando se poderia vir aqui hoje', '2025-02-04 16:26:31', 0),
(5, 4, 5, 'mas não precisa se não quiser', '2025-02-04 16:26:42', 0),
(6, 5, 4, 'passo aí mais tarde', '2025-02-04 16:56:46', 0),
(7, 4, 5, 'de boa', '2025-02-05 00:22:42', 0),
(8, 4, 5, 'oh god man', '2025-02-10 09:39:10', 0),
(9, 5, 4, 'yes bro very crazy', '2025-02-10 09:39:46', 0),
(10, 1, 5, 'oi', '2025-02-10 09:41:21', 0),
(11, 5, 1, 'oh god man', '2025-02-10 09:41:57', 1),
(12, 1, 5, 'oi', '2025-02-10 17:35:52', 0),
(13, 5, 1, 'oi', '2025-02-10 17:37:06', 1),
(14, 5, 1, 'oi', '2025-02-10 22:33:36', 1),
(15, 1, 5, 'oi', '2025-02-10 22:34:09', 0),
(16, 5, 4, 'asfsafasfasfasasfsafsa', '2025-02-11 13:43:08', 0),
(17, 4, 5, 'tenha um bom dia', '2025-02-11 13:43:44', 0),
(18, 5, 1, 'OLÁ', '2025-02-24 11:06:55', 1),
(19, 5, 1, 'BADSJBASKFAS', '2025-02-24 11:07:01', 1),
(20, 1, 5, 'boiola', '2025-06-23 10:21:52', 0),
(21, 4, 1, 'iae meu nobre', '2025-06-23 10:59:48', 1),
(22, 1, 4, 'oi', '2025-06-23 12:33:03', 1),
(23, 4, 1, 'oi', '2025-06-23 12:35:48', 1),
(24, 1, 4, 'oiii', '2025-06-23 12:38:38', 1),
(25, 4, 1, 'oiiiii', '2025-06-23 12:41:53', 1),
(26, 1, 4, 'oi', '2025-06-23 12:43:48', 1),
(27, 1, 4, 'oi', '2025-06-23 12:43:53', 1),
(28, 1, 4, 'oi', '2025-06-23 12:43:59', 1),
(29, 1, 4, 'oi', '2025-06-23 12:58:28', 1),
(30, 4, 1, 'oi', '2025-06-23 13:01:54', 1),
(31, 1, 2, 'oi', '2025-06-23 14:29:44', 1),
(32, 1, 4, 'iae meu nobre7', '2025-06-23 14:50:37', 1),
(33, 1, 4, 'e aquela cervejinha hein?', '2025-06-23 14:50:50', 1),
(34, 1, 4, 'sai quando?', '2025-06-23 14:50:58', 1),
(35, 1, 4, 'oi', '2025-06-24 23:01:30', 0),
(36, 2, 1, 'oi', '2025-06-24 23:03:34', 1),
(37, 1, 2, 'oi', '2025-06-24 23:04:01', 1),
(38, 1, 2, 'tudo ben', '2025-06-24 23:04:07', 1),
(39, 1, 2, 'aooooo', '2025-06-24 23:04:11', 1),
(40, 1, 2, 'aaaaaaaa', '2025-06-24 23:04:15', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_amizade`
--

CREATE TABLE `pedidos_amizade` (
  `id` int(11) NOT NULL,
  `solicitante_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `status` enum('pendente','aceito','recusado') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pedidos_amizade`
--

INSERT INTO `pedidos_amizade` (`id`, `solicitante_id`, `destinatario_id`, `status`, `criado_em`) VALUES
(44, 4, 1, 'aceito', '2025-06-23 13:48:55'),
(45, 4, 2, 'aceito', '2025-06-23 14:00:06'),
(46, 1, 5, 'pendente', '2025-06-24 22:01:09'),
(47, 1, 3, 'pendente', '2025-06-24 22:02:35'),
(48, 1, 2, 'aceito', '2025-06-24 22:02:36');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password`, `criado_em`, `foto_perfil`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$17YNWU9e7YvTS2CG.T9eI.j6tWymkRmqTwfY3f7XHEBXMqCCi4iZK', '2024-12-07 01:54:36', 'uploads/fotos_perfil/R (1).jpg'),
(2, 'fe', 'fe@gmail.com', '$2y$10$3Jw5a1aaf5doD0qwPwSQ2uIKkuyDB5ehOdMKHKxsKH6ysm9MDsDX2', '2024-12-07 02:20:14', 'uploads/perfis/perfil_675f9345e25973.41755429.png'),
(3, 'usuario teste', 'usuarioteste@gmail.com', '$2y$10$7lUlr1SQWnqSh2jiYzKPZ.3Aen7tQx8uruVaLLyJC6CFwzmd43sCm', '2024-12-15 15:17:38', 'uploads/fotos_perfil/pngwing.com (6).png'),
(4, 'usu', 'usu@gmail.com', '$2y$10$EMs./ouSFZmcp9zW3yS3IOl3zdUKzai74l.qmEjINOG1HaVpIVAPu', '2024-12-16 16:38:56', 'uploads/fotos_perfil/fanta.png'),
(5, 'osakodsa@gmail.com', 'osakodsa@gmail.com', '$2y$10$MKv3jSWJx8mXhPDafyj/SuPZrQGP30RJy3VaUAOp5KTFciiavmDSa', '2025-01-10 18:41:04', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `visualizacoes`
--

CREATE TABLE `visualizacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `visualizado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `visualizacoes`
--

INSERT INTO `visualizacoes` (`id`, `usuario_id`, `filme_id`, `visualizado_em`) VALUES
(1, 4, 18, '2025-02-10 10:14:57'),
(2, 5, 18, '2025-02-10 10:25:24'),
(3, 1, 18, '2025-02-10 10:46:11'),
(4, 3, 18, '2025-02-10 10:47:55'),
(5, 5, 17, '2025-02-10 17:26:32'),
(6, 5, 19, '2025-02-11 17:29:25'),
(7, 1, 17, '2025-02-12 15:45:12'),
(8, 1, 19, '2025-03-10 09:50:12'),
(9, 1, 21, '2025-04-10 13:56:05'),
(10, 5, 20, '2025-04-10 16:35:55'),
(11, 5, 21, '2025-05-06 16:38:46'),
(12, 1, 20, '2025-06-23 09:58:05'),
(13, 4, 19, '2025-06-23 09:58:35'),
(14, 4, 20, '2025-06-23 09:58:41'),
(15, 4, 21, '2025-06-23 11:46:50'),
(16, 3, 20, '2025-06-23 12:25:51'),
(17, 4, 17, '2025-06-23 13:54:01'),
(18, 2, 17, '2025-06-24 12:50:12');

-- --------------------------------------------------------

--
-- Estrutura para vista `comentarios_formatados`
--
DROP TABLE IF EXISTS `comentarios_formatados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comentarios_formatados`  AS SELECT `c`.`id` AS `id`, `c`.`filme_id` AS `filme_id`, `c`.`usuario_id` AS `usuario_id`, `u`.`username` AS `username`, `c`.`comentario` AS `comentario`, `c`.`parent_id` AS `parent_id`, `c`.`criado_em` AS `criado_em` FROM (`comentarios` `c` join `usuarios` `u` on(`c`.`usuario_id` = `u`.`id`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `amigos`
--
ALTER TABLE `amigos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filme_id` (`filme_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comentario_id` (`comentario_id`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `curtidas_filme`
--
ALTER TABLE `curtidas_filme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_curtida` (`usuario_id`,`filme_id`);

--
-- Índices para tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `filme_id` (`filme_id`);

--
-- Índices para tabela `filmes`
--
ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unico_usuario_filme` (`usuario_id`,`filme_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `filme_id` (`filme_id`);

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remetente_id` (`remetente_id`),
  ADD KEY `destinatario_id` (`destinatario_id`);

--
-- Índices para tabela `pedidos_amizade`
--
ALTER TABLE `pedidos_amizade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitante_id` (`solicitante_id`),
  ADD KEY `destinatario_id` (`destinatario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `visualizacoes`
--
ALTER TABLE `visualizacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_view` (`usuario_id`,`filme_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `amigos`
--
ALTER TABLE `amigos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `curtidas_filme`
--
ALTER TABLE `curtidas_filme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `filmes`
--
ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=733;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `pedidos_amizade`
--
ALTER TABLE `pedidos_amizade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `visualizacoes`
--
ALTER TABLE `visualizacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`remetente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `pedidos_amizade`
--
ALTER TABLE `pedidos_amizade`
  ADD CONSTRAINT `pedidos_amizade_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pedidos_amizade_ibfk_2` FOREIGN KEY (`destinatario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
